<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    
    private $total_on_page = 96;//результатов на странице
    private $total_live_search = 5;//результатов в живом поиске
    
    /**
     * Живой поиск
     * @param Request $request
     * @return type
     */
    public function get(Request $request)
    { 
        
        $result = [
            'categories' => [],
            'products' => [],
        ];
        
        if($request->has('query')){
            
            $query = trim(strip_tags(stripcslashes(htmlspecialchars($request->get('query')))));
            //ищем по 2 вариантам lat and rus
            $queryRus = self::toRus($query);
            $queryLat = self::toLat($query);
            
            $db = \DB::connection('sphinx');
            
            //товары
            $result['products'] = $db->select("SELECT * FROM idx_tollyru_products "
                . "WHERE MATCH('$queryRus|$queryLat') AND stock > 0 AND enabled = 1 "
                . "LIMIT $this->total_live_search OPTION max_matches=1000000");
            //категории и метки
            $result['categories'] = $db->select("SELECT * FROM idx_tollyru_c_mc "
                . "WHERE MATCH('$queryRus|$queryLat') "
                . "LIMIT $this->total_live_search OPTION max_matches=1000000");
            
        } 
        
        return response()->json($result);

    }
    
    /**
     * Страница поиска
     * @param type $query
     * @param type $url
     * @param type $page
     * @return type
     */
    public function all($query, $url, $page=1){
        
        //получаемые поля
        $fields = [
            'vid', 
            'id', 
            'category_id',
            'image',
            'vname', 
            'pname name',
            'seo',
            'weight() weight',
            'price', 
         ];
        
        $db = \DB::connection('sphinx');
        $search = new \stdClass();
        $ids = [];
        $query = trim(strip_tags(stripcslashes(htmlspecialchars($query))));
        $search->url = "/search/".preg_replace('|\s+|', '%20', $query);
        $search->name = $query;
        
        //поиск в индексе
        if(!empty($query)){
            
            //ищем по 2 вариантам lat and rus
            $queryRus = self::toRus($query);
            $queryLat = self::toLat($query);
            
            //всего по запросу
            $allProducts = $db->select("SELECT category_id FROM idx_tollyru_products "
                . "WHERE MATCH('$queryRus|$queryLat') "
                . "AND stock > 0 AND enabled = 1 "
                . "LIMIT 1000000 OPTION max_matches=1000000");
            
            //диапазон прайса
            $minMaxPrice = $db->select("SELECT min(price) min_price, max(price) max_price FROM idx_tollyru_products "
                . "WHERE MATCH('$queryRus|$queryLat') "
                . "AND stock > 0 AND enabled = 1 "
                . "LIMIT 1000000 OPTION max_matches=1000000");
            $search->min_price = $minMaxPrice[0]->min_price ?? 0;
            $search->max_price = $minMaxPrice[0]->max_price ?? 0;
            
            $query = "SELECT ".implode(', ', $fields)." FROM idx_tollyru_products "
                    . "WHERE MATCH('$queryRus|$queryLat') "
                    . "AND stock > 0 AND enabled = 1 "
                    . "ORDER BY popularity DESC, weight DESC LIMIT $this->total_on_page OPTION max_matches=1000000, field_weights=(pname=4, vname=3, seo=2, body=1), ranker=expr('sum(user_weight)')";
            //товары
            $products = $db->select($query);
            
            //id категорий 
            $cids = $this->getCategoriesIds($allProducts);

            //кол-во товаров в категории
            $countPositions = $this->getCountPositionsOnCategory($allProducts);
            //сколько найдено
            $search->count = count($allProducts);
   
        }

        $geo_id = 1;
            
        $categories = \DB::table('categories')
            ->select('id','name')
            ->whereIn('id', $cids)
            ->get();
        
        foreach ($categories as &$category){
            $category->countPositions = $countPositions[$category->id] ?? 0;
        }
        
        foreach ($products as &$p){
            $p->imgs = json_decode($p->image);
            if($p->imgs)
                $p->img = $this->imgSize(320, 200, $p->imgs[0]);
            else
                $p->img= false;

        }

        return view('search/all', compact(['search', 'products', 'categories', 'page']));
    
    }

    
    /**
     * Фильтр и подгрузка
     * @param Request $request
     * @return type
     */
    public function more(Request $request)
    {
        
        //получаемые поля
        $fields = [
            'vid', 
            'id', 
            'category_id',
            'image',
            'vname', 
            'pname name',
            'seo',
            'weight() weight',
            'price', 
         ];
        $ids = [];
        $query = trim(strip_tags(stripcslashes(htmlspecialchars($request->get('query')))));
        $page = $request->get('page');

        if($request->has('sort')){
            switch ($request->get('sort')){
                case 'popular':
                    $orderBySphinx = "popularity DESC";
                    break;
                case 'price_asc':
                    $orderBySphinx = "price ASC";
                    break;
                case 'price_desc':
                    $orderBySphinx = "price DESC";
                    break;
                case 'rating':
                    $orderBySphinx = "pname ASC";
                    break;
                case "discount":
                    $orderBySphinx = "price DESC";
                    break;
                default:
                    $orderBySphinx = "popularity DESC";
                    break;
            }
        } else {
            $orderBySphinx = "popularity DESC";
        }
        
        $min_price      = $request->get('min_price');
        $max_price      = $request->get('max_price');
        $category_id    = $request->get('category_id');
        
        $db = \DB::connection('sphinx');
        
        //поиск в индексе с сортировкой и диапазоном цен
        if(!empty($query)){

            //ищем по 2 вариантам lat and rus
            $queryRus = self::toRus($query);
            $queryLat = self::toLat($query);
            
            $category = '';
            
            if($category_id > 0)
                $category = " AND category_id = $category_id";
            
            $offset = ($page * $this->total_on_page) - $this->total_on_page;
            //всего по запросу
            $allProducts = $db->select("SELECT category_id FROM idx_tollyru_products "
                . "WHERE MATCH('$queryRus|$queryLat') AND price >= $min_price AND price <= $max_price$category "
                . "AND stock > 0 AND enabled = 1 "
                . "LIMIT 1000000 OPTION max_matches=1000000");
            
            $query = "SELECT ".implode(', ', $fields)." FROM idx_tollyru_products "
                    . "WHERE MATCH('$queryRus|$queryLat') AND price >= $min_price AND price <= $max_price$category "
                    . "AND stock > 0 AND enabled = 1 "
                    . "ORDER BY $orderBySphinx LIMIT $offset, $this->total_on_page OPTION max_matches=1000000, field_weights=(pname=4, vname=3, seo=2, body=1), ranker=expr('sum(user_weight)')";
            
            //товары
            $products = $db->select($query);

        }

        $geo_id = 1;

        foreach ($products as &$p){
            $p->imgs = json_decode($p->image);
            if($p->imgs)
                $p->img = $this->imgSize(320, 200, $p->imgs[0]);
            else
                $p->img= false;
        }

        //id категорий 
        $cids = $this->getCategoriesIds($allProducts);
        //кол-во товаров в категории
        $countPositions = $this->getCountPositionsOnCategory($allProducts);

//        \Debugbar::info(count($products));
        
        $categories = \DB::table('categories')
            ->select('id','name')
            ->whereIn('id', $cids)
            ->get();
        
        foreach ($categories as &$category){
            $category->countPositions = $countPositions[$category->id] ?? 0;
        }

        $data = [];
        $data['categories'] = view('layouts.categories_search', compact(['categories']))->render();
        $data['offers'] = view('layouts.products', compact(['products', 'page']))->render();
        return response()->json($data);
    }
    
    /**
     * Подгрузка категорий
     * @param Request $request
     * @return type
     */
    public function get_categories(Request $request)
    {
        
        $ids = [];
        $query = trim(strip_tags(stripcslashes(htmlspecialchars($request->get('query')))));
        //ищем по 2 вариантам lat and rus
        $queryRus = self::toRus($query);
        $queryLat = self::toLat($query);
        
        $min_price      = $request->get('min_price');
        $max_price      = $request->get('max_price');

        $geo_id = 1;

        //собираем все категории в диапазоне цен
        $results = $db->select("SELECT ".implode(', ', $fields)." FROM idx_tollyru_products "
                    . "WHERE MATCH('$queryRus|$queryLat') AND price >= $min_price AND price <= $max_price "
                    . "AND stock > 0 AND enabled = 1 "
                    . "LIMIT 1000000 OPTION max_matches=1000000, field_weights=(pname=4, vname=3, seo=2, body=1), ranker=expr('sum(user_weight)')");

            
        $cids = $this->getCategoriesIds($results);
        $countPositions = $this->getCountPositionsOnCategory($results);
        
        $categories = \DB::table('categories')
            ->select('id','name')
            ->whereIn('id', $cids)
            ->get();
        
        foreach ($categories as &$category){
            $category->countPositions = $countPositions[$category->id] ?? 0;
        }

        $data = [];
        $data['categories'] = view('layouts.categories_search', compact(['categories']))->render();
        return response()->json($data);
    }

    public function imgSize($width=320, $height=200, $img){
        if(empty($img))
            return false;

        $resizePath = storage_path('app/public').DIRECTORY_SEPARATOR;
        $parts = explode('.', $img);
        $filename = $parts[0].$width.'x'.$height.'.'.$parts[1];
        if (file_exists($resizePath.$filename))
            return url ('storage', $filename);

        if(file_exists($resizePath.$img)) {

            $image = \Image::make( $resizePath . $img )->fit( $width, $height );
            $image->save( $resizePath . $filename );

        } else {
            return false;
        }
        return url ('storage', $filename);

    }
   
    private function getCategoriesIds($results){
        
        $ids = [];
        
        if(isset($results)){
            
            foreach($results as $item){
                $ids[] = $item->category_id;
            }
            
        }
        
        return array_unique($ids);
    }
    
    private function getCountPositionsOnCategory($results){
        
        $result = [];
        
        if(isset($results)){
            
            foreach($results as $item){
                
                if(!isset($result[$item->category_id]))
                    $result[$item->category_id] = 1;
                else 
                    $result[$item->category_id] += 1;
            }
            
        }
        
        return $result;
    }
    
    private static function toRus($text){
        
        $lat = [
            'A','B','E','K','M','O','P','C','T','X',
            'a','e','o','p','c','x'
        ];
        $rus = [
            'А','В','Е','К','М','О','Р','С','Т','Х',
            'а','е','о','р','с','х'
        ];
        
        return str_replace($lat, $rus, $text);
        
    }
    
    private static function toLat($text){
        
        $text = preg_replace('|\s+|', '|', $text);
        
        $lat = [
            'A','B','E','K','M','O','P','C','T','X',
            'a','e','o','p','c','x'
        ];
        $rus = [
            'А','В','Е','К','М','О','Р','С','Т','Х',
            'а','е','о','р','с','х'
        ];
        
        return str_replace($rus, $lat, $text);
        
    }
    
}

