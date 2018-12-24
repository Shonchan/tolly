<?php

namespace App\Http\Controllers\Views;

use App\Category;
use App\Feature;
use App\Mark;
use App\Page;
use App\Product;
use App\Variant;
use Doctrine\Common\Util\Debug;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;


class ProductController extends Controller
{

    protected $pagesController;
    public function __construct(PagesController $pagesController)
    {
        $this->pagesController = $pagesController;
    }

    public function index($url, $page=1)
    {
//        $mark = Mark::where('slug', '=', $url)->first();
//        if($mark){
//            $category = Category::where( 'id', '=', $mark->category_id )->first();
//        } else {

            $category = Category::where( 'slug', '=', $url )->first();
//        }

//        \Debugbar::info($mark);
        if(!$category){
//            return redirect()->action('Views\PagesController@show', $url);

            return $this->pagesController->show($url);
        }

        if($category->type == 'mc') {
            $mark = $category;

            $category = $mark->parent;
            while ($category->type != 'c') {
                $category = $category->parent;
            }
            $mark->features = json_decode( $mark->filter, true );
        }


        $features_filter = " ";
        if(!empty($mark->features) && count($mark->features) > 0)
            foreach($mark->features as $feature=>$value) {
                $val = "";
                foreach ($value as $v){
                    if(is_null($v))
                        $r = "NULL";
                    else
                        $r = "'".@addslashes($v)."'";

                    $val .= ($val===''? "" : ",").$r;
                }

                $features_filter .= \DB::raw( "AND ( p.id in (SELECT product_id FROM options WHERE feature_id = $feature AND value in ($val) ) OR v.id in (SELECT variant_id FROM options WHERE feature_id = $feature AND value in ($val) )) " );
            }

        $cids = $category->childIds();
        $cids[] = $category->id;
        if(isset($page)) {
            Paginator::currentPageResolver( function () use ( $page ) {
                return $page;
            } );
        }

        if(isset($mark))
            $parent_id = $mark->id;
        else
            $parent_id = $category->id;
        $marks = Category::where('parent_id', '=', $parent_id)
                ->where('type', '=','mc')
                ->orderBy('position', 'asc')
                ->get();
        if(count($marks) == 0) {
            if(isset($mark))
                $parent_id = $mark->parent_id;
            else
                $parent_id = $category->id;
            $marks = Category::where('parent_id', '=', $parent_id)
                    ->where('type', '=','mc')
                    ->orderBy('position', 'asc')
                    ->get();
        }

        $features = Feature::where('in_filter', '=', '1')
            ->where('category_id', '=', $category->id)
            ->orderBy('position', 'asc')
            ->get();



        $max_min_price = \DB::table('products as p')
            ->join('products_categories as pc', function($join) use ($cids)
            {
                $join->on('pc.product_id', '=', 'p.id')
                    ->whereIn('pc.category_id', $cids);

            })
            ->leftJoin('variants as v', function($join){
                $join->on('v.product_id', '=', 'p.id');
            })
            ->select(\DB::raw('MIN( v.price ) as min_price, MAX( v.price ) as max_price'))
            ->where('v.stock', '>', 0)
            ->where('p.enabled', '=', 1)
            ->first();



        $geo_id = 1;
        $products = \DB::table('products as p')
            ->join('products_categories as pc', function($join) use ($cids)
            {
                $join->on('pc.product_id', '=', 'p.id')
                    ->whereIn('pc.category_id', $cids);

            })
            ->leftJoin('popularity as pop', function($join) use ($geo_id)
            {
                $join->on('pop.product_id', '=', 'p.id')
                    ->where('pop.geo_id', '=', $geo_id);

            })
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('variants as v', function($join){
                $join->on('v.product_id', '=', 'p.id');
            })
            ->select('p.*','b.name as brand','v.id as vid','v.price as price', 'v.name as vname')
            ->whereRaw("p.enabled = 1 
                            $features_filter")
            ->where('v.stock', '>', 0)
            ->groupBy('p.id')
            ->orderBy('pop.weight', 'desc')
            ->paginate(96);
//        dd($products->total());
//            ->when($category, function($query, $category) {

//            return $query->innerJoin('products_categories as pc', 'pc.product_id', '=', 'p.id')->on('pc.category_id', '=', $category->id);
//        });



        foreach ($products as &$p){
            
            $opts = \DB::table('options as o')
            ->leftJoin('features as f', 'f.id', '=', 'o.feature_id')
            ->selectRaw('f.id as feature_id, f.name, o.value, o.product_id')
            ->where('f.in_catalog', '=', 1)
            ->where('o.product_id', '=', $p->id)
            ->orderBy('f.position', 'asc')->get();

            $options = [];
            foreach ( $opts as $opt ) {
                if(isset($options[$opt->feature_id])) {
                    $options[ $opt->feature_id ]->values[] = $opt->value;
                } else {
                    $options[ $opt->feature_id ] = $opt;
                    $options[ $opt->feature_id ]->values = (array)$opt->value;
                }
            }
            $p->options = $options;
            
            $p->imgs = json_decode($p->images);
            if($p->imgs)
                $p->img = $this->imgSize(320, 200, $p->imgs[0]);
            else
                $p->img= false;

        }

        //если это метка, то ее id будет для фильтра
        if(isset($mark))
            $category_id = $mark->id;
        else
            $category_id = $category->id;

        return view('category/category', compact(['category', 'category_id','products', 'features', 'max_min_price', 'page', 'mark', 'marks']));
    }

    public function showMore(Request $request)
    {
        if($request->has('category')){
            $category = Category::where('id', '=', $request->get('category'))->first();
        } else  {
            return response()->json($request->get('category'));
        }

        if($request->has('category')){
            $page = $request->get('page');
        } else {
            $page = 1;
        }


        if($request->has('sort')){
            switch ($request->get('sort')){
                case 'popular':
                    $orderBy = \DB::raw("pop.weight desc");
                    break;
                case 'price_asc':
                    $orderBy = \DB::raw("v.price asc");
                    break;
                case 'price_desc':
                    $orderBy = \DB::raw("v.price desc");
                    break;
                case 'rating':
                    $orderBy = \DB::raw("p.name asc");
                    break;
                case "discount":
                    $orderBy = \DB::raw("v.price desc");
                    break;
                default:
                    $orderBy = \DB::raw("pop.weight desc");
                    break;
            }
        } else {
            $orderBy = \DB::raw("pop.weight desc");
        }

        $min_price = $request->get('min_price');
        $max_price = $request->get('max_price');
        $features = $request->get('features');
        $features_filter = " ";
        if(!empty($features) && count($features) > 0)
            foreach($features as $feature=>$value) {
                $val = "";
                foreach ($value as $v){
                    if(is_null($v))
                        $r = "NULL";
                    else
                        $r = "'".@addslashes($v)."'";

                    $val .= ($val===''? "" : ",").$r;
                }
                $features_filter .= \DB::raw( "AND ( p.id in (SELECT product_id FROM options WHERE feature_id = $feature AND value in ($val) ) OR v.id in (SELECT variant_id FROM options WHERE feature_id = $feature AND value in ($val) )) " );
            }
//        \Debugbar::info($features_filter);
        $price_filter = " AND v.price >= $min_price AND v.price <= $max_price ";

        $cids = $category->childIds();
        $cids[] = $category->id;
        $geo_id = 1;

        $products = \DB::table('products as p')
            ->join('products_categories as pc', function($join) use ($cids)
            {
                $join->on('pc.product_id', '=', 'p.id')
                    ->whereIn('pc.category_id', $cids);

            })
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('popularity as pop', function($join) use ($geo_id)
            {
                $join->on('pop.product_id', '=', 'p.id')
                    ->where('pop.geo_id', '=', $geo_id);

            })
            ->leftJoin('variants as v', function($join){
                $join->on('v.product_id', '=', 'p.id');
            })
            ->select('p.*','b.name as brand','v.id as vid','v.price as price', 'v.name as vname')
            ->whereRaw("p.enabled = 1  $price_filter 
                                $features_filter")
            ->where('v.stock', '>', 0)
            ->groupBy('v.id')
            ->orderByRaw($orderBy)
            ->paginate(96);
//            ->when($category, function($query, $category) {

//            return $query->innerJoin('products_categories as pc', 'pc.product_id', '=', 'p.id')->on('pc.category_id', '=', $category->id);
//        });

        foreach ($products as &$p){
            $opts = \DB::table('options as o')
            ->leftJoin('features as f', 'f.id', '=', 'o.feature_id')
            ->selectRaw('f.id as feature_id, f.name, o.value, o.product_id')
            ->where('f.in_catalog', '=', 1)
            ->where('o.product_id', '=', $p->id)
            ->orderBy('f.position', 'asc')->get();

            $options = [];
            foreach ( $opts as $opt ) {
                if(isset($options[$opt->feature_id])) {
                    $options[ $opt->feature_id ]->values[] = $opt->value;
                } else {
                    $options[ $opt->feature_id ] = $opt;
                    $options[ $opt->feature_id ]->values = (array)$opt->value;
                }
            }
            $p->options = $options;
            $p->imgs = json_decode($p->images);
            if($p->imgs)
                $p->img = $this->imgSize(320, 200, $p->imgs[0]);
            else
                $p->img= false;
        }


        $data = [];
        $data['offers'] = view('layouts.products', compact(['products', 'page']))->render();
        return response()->json($data);
    }

    public function imgSize($width=320, $height=200, $img){
        if(empty($img))
            return false;

//        $img = str_replace('.JPEG', '.jpg', $img);


        $resizePath = storage_path('app/public').DIRECTORY_SEPARATOR;
        $parts = explode('.', $img);
        $filename = $parts[0].$width.'x'.$height.'.'.$parts[1];
        if (file_exists($resizePath.$filename)) {

            return url( 'storage', $filename );
        }

        if(file_exists($resizePath.$img)) {

            $image = \Image::make( $resizePath . $img )->fit( $width, $height );
            $image->save( $resizePath . $filename );
//            \Debugbar::info(url( 'storage', $filename ));

        } else {
            return false;
        }
        return url ('storage', $filename);

    }

    public function filter(Request $request){
        if($request->has('id')){//
            $cat_id = $request->get('id');
        } else  {
            return response()->json(false);
        }

        $geo_id = 1;

        $features = Feature::where('in_filter', '=', '1')
            ->where('category_id', '=', $cat_id)
            ->orderBy('position', 'asc')
            ->get();

        while(count($features) < 1) {
            $cat = \DB::table('categories')->where('id', '=', $cat_id)->first();

            $features = Feature::where('in_filter', '=', '1')
                ->where('category_id', '=', $cat->parent_id)->orderBy('position', 'asc')->get();
            $cat_id = $cat->parent_id;
        }

        $data = view('layouts.ajax-filter', compact(['features']))->render();
        return response()->json($data);

    }

    public function click(Request $request){
        if($request->has('id')){
//            $category = Category::where('id', '=', $request->get('category'))->first();
            $id = $request->get('id');
            $weight = (float)$request->get('weight');
        } else  {
            return response()->json(false);
        }

        $geo_id = 1;

        if(session('clicked'))
            $clicked = session('clicked');
        else
            $clicked = [];

        if(!in_array($id, $clicked)) {
            \DB::table( 'bonuses' )->insert( [
                'geo_id' => $geo_id,
                'product_id' => $id,
                'bonus' => $weight,
                'created_at' => date( 'Y-m-d H:i:s' ),
                'updated_at' => date( 'Y-m-d H:i:s' ),
            ] );
            $clicked[] = $id;
            session(['clicked'=>$clicked]);
        }
        return response()->json(true);


    }

    public function show($url)
    {
        $variant = Variant::where('id', '=', $url)->first();
//        $variant->feats = \DB::table('options')->where('variant_id', '=', $variant->id)->pluck('value', 'feature_id')->toArray();
//        \Debugbar::info($variant->feats);

        if(!$variant) {
            return response()->view('errors.404', compact(['url']), '404');
        }
        $product = Product::where('id', '=', $variant->product_id)->first();
        if(!$product) {
            return response()->view('errors.404', compact(['url']), '404');
        }
        $product->images = json_decode($product->images);
        if(isset($product->images[0])) {
            $product->image = $product->images[ 0 ];
        } else {
            $product->image = '';
        }

        $opts = \DB::table('options as o')
            ->leftJoin('features as f', 'f.id', '=', 'o.feature_id')
            ->selectRaw('f.id as feature_id, f.name, o.value, o.product_id')
            ->whereIn('o.product_id', (array)$product->id)
            ->orWhereIn('o.variant_id', (array)$variant->id)
            ->orderBy('f.position', 'asc')->get();

        $options = [];
        foreach ( $opts as $opt ) {
            if(isset($options[$opt->feature_id])) {
                $options[ $opt->feature_id ]->values[] = $opt->value;
            } else {
                $options[ $opt->feature_id ] = $opt;
                $options[ $opt->feature_id ]->values = (array)$opt->value;
            }
//            if(isset($variant->feats[$opt->feature_id])) {
//                $options[ $opt->feature_id ]->values = (array)$variant->feats[$opt->feature_id];
//            }
        }
//        \Debugbar::info($options);
        if(session('browsed'))
            $browsed = session('browsed');
        else
            $browsed = [];

        if(!in_array($product->id, $browsed)) {

            \DB::table( 'bonuses' )->insert( [
                'geo_id' => 1,
                'product_id' => $product->id,
                'bonus' => 0.1,
                'created_at' => date( 'Y-m-d H:i:s' ),
                'updated_at' => date( 'Y-m-d H:i:s' ),
            ] );

            $browsed[] = $product->id;
            session(['browsed'=>$browsed]);
        }

        $cart = json_decode( \Cookie::get('shopping_cart') );
//        $variant = $product->variant();

        //похожие продукты------------------------------------------------------
        //Выбираем все id свойств у текущего продукта, учавствующих в выборке
        $featureIds = \DB::table('options as o')
            ->leftJoin('features as f', 'f.id', '=', 'o.feature_id')
            ->selectRaw('f.id as feature_id')
            ->whereIn('o.product_id', (array)$product->id)
            ->where('f.in_selection', '=', 1)
            ->orderBy('f.position', 'asc')->pluck('feature_id')->toArray();
//        \Debugbar::info($featureIds);

        if (count($featureIds) > 0) {

            //Выбираем все названия свойств текущего продукта, учавствующих в выборке
            $oValues = \DB::table( 'options as o' )
                ->leftJoin( 'features as f', 'f.id', '=', 'o.feature_id' )
                ->selectRaw( 'o.value' )
                ->whereIn( 'o.product_id', (array)$product->id )
                ->where( 'f.in_selection', '=', 1 )
                ->orderBy( 'f.position', 'asc' )->pluck( 'o.value' )->toArray();
//        \Debugbar::info($oValues);


            $countSimilar = 0;
            $countFIds = count( $featureIds );
            //берем все id похожих продуктов по id и названиям свойств, в кол-ве 4 от текущего id в меньшую сторону
            while ( $countSimilar < 4 ) {

                if ( $countFIds == 0 )
                    break;

                $similarProductsPre = \DB::table( 'options as o' )
                    ->leftJoin( 'products as p', 'p.id', '=', 'o.product_id' )
                    ->join('variants as v', 'v.product_id', '=', 'p.id')
//            ->leftJoin('features as f', 'f.id', '=', 'o.feature_id')
                    ->selectRaw( 'count(o.product_id) count, o.product_id' )
                    ->whereIn( 'o.feature_id', $featureIds )
                    ->whereIn( 'o.value', $oValues )
                    ->where('v.stock', '>', 0)
                    ->where('p.enabled', '=', 1)
                    ->where( 'o.product_id', '<', $product->id )
                    ->orderBy( 'o.product_id', 'desc' )
                    ->groupBy( 'o.product_id' )
                    ->having( 'count', '=', $countFIds )
                    ->limit( 4 )
                    ->pluck( 'o.product_id' )->toArray();
                $countSimilar += count( $similarProductsPre );
                $countFIds -= 1;
            }

            $countSimilar = 0;
            $countFIds = count( $featureIds );

            //берем все id похожих продуктов по id и названиям свойств, в кол-ве 4 от текущего id в большую сторону
            while ( $countSimilar < 4 ) {

                if ( $countFIds == 0 )
                    break;

                $similarProductsAfter = \DB::table( 'options as o' )
                    ->leftJoin( 'products as p', 'p.id', '=', 'o.product_id' )
                    ->join('variants as v', 'v.product_id', '=', 'p.id')
                    ->selectRaw( 'count(o.product_id) count, o.product_id' )
                    ->whereIn( 'o.feature_id', $featureIds )
                    ->whereIn( 'o.value', $oValues )
                    ->where('v.stock', '>', 0)
                    ->where('p.enabled', '=', 1)
                    ->where( 'o.product_id', '>', $product->id )
                    ->orderBy( 'o.product_id', 'asc' )
                    ->groupBy( 'o.product_id' )
                    ->having( 'count', '=', count( $featureIds ) )
                    ->limit( 4 )
                    ->pluck( 'o.product_id' )->toArray();
                $countSimilar += count( $similarProductsAfter );
                $countFIds -= 1;
            }

            //сливаем вместе
            $similarProductsIds = array_merge( $similarProductsAfter, $similarProductsPre );
//        \Debugbar::info($similarProductsIds);

            //берем продукты
            $similarProducts = Product::whereIn( 'id', $similarProductsIds )->orderBy( 'id', 'asc' )->get();

            //декодируем имаги
            foreach ( $similarProducts as &$sProduct ) {
                $sProduct->images = json_decode( $sProduct->images );
            }

        }

        $amount = isset($cart->{$variant->id}) ? $cart->{$variant->id} : 0;
        $vid = $variant->id;
        $vprice = $variant->price;

        $rating = \App\Review::getRating($product->id);
        $averageStar = \App\Review::getAverageRatingStar($product->id);
//        \Debugbar::info($variant);

        //отзывы
        $reviews = \App\Review::where('product_id', '=', $product->id)->where('moderated', '=', 1)->orderBy('id', 'DESC')->get();

        //варианты
        $vars = $product->get_variants();
        $variants = [];
        foreach ($vars as $v){
            $var = new \stdClass();
            $var->id = $v->id;
            $var->name = $v->name;
            $var->stock = $v->stock;
            $var->price = $v->price." руб.";
            $var->compare_price = $v->compare_price;
            $var->imageUrl =  $this->imgSize(320, 200, $product->image);
            $variants[] = $var;
        }



        return view('category/product', compact([
            'product',
            'options',
            'amount',
            'vid',
            'variant',
            'vprice',
            'similarProducts',
            'reviews',
            'rating',
            'averageStar',
            'variants',
            ]));
    }

    public function showTag($url, $tag, $page=1)
    {
        $category = Category::where('slug', '=', $url)->first();


        if(!$category){
//            return redirect()->action('Views\PagesController@show', $url);

            return $this->pagesController->show($url);
        }

        $cids = $category->childIds();
        $cids[] = $category->id;
        if(isset($page)) {
            Paginator::currentPageResolver( function () use ( $page ) {
                return $page;
            } );
        }

        $mark = Mark::where('slug', '=', $tag)->first();

        $mark->features = json_decode($mark->filter, true);
//        \Debugbar::info($mark->features);

        $features = Feature::where('in_filter', '=', '1')
            ->where('category_id', '=', $category->id)->get();

        $marks = Mark::where('category_id', '=', $category->id)->get();

        $features_filter = " ";
        if(!empty($mark->features) && count($mark->features) > 0)
            foreach($mark->features as $feature=>$value) {
                $val = "";
                foreach ($value as $v){
                    if(is_null($v))
                        $r = "NULL";
                    else
                        $r = "'".@addslashes($v)."'";

                    $val .= ($val===''? "" : ",").$r;
                }
                $features_filter .= \DB::raw( "AND p.id in (SELECT product_id FROM options WHERE feature_id = $feature AND value in ($val) ) " );
            }


        $max_min_price = \DB::table('products as p')
            ->join('products_categories as pc', function($join) use ($cids)
            {
                $join->on('pc.product_id', '=', 'p.id')
                    ->whereIn('pc.category_id', $cids);

            })
            ->leftJoin('variants as v', function($join){
                $join->on('v.product_id', '=', 'p.id');
            })
            ->select(\DB::raw('MIN( v.price ) as min_price, MAX( v.price ) as max_price'))
            ->where('v.stock', '>', 0)
            ->first();



        $products = \DB::table('products as p')
            ->join('products_categories as pc', function($join) use ($cids)
            {
                $join->on('pc.product_id', '=', 'p.id')
                    ->whereIn('pc.category_id', $cids);

            })
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->leftJoin('variants as v', function($join){
                $join->on('v.product_id', '=', 'p.id');
            })
            ->select('p.*','b.name as brand','v.price as price', 'v.name as vname')
            ->whereRaw("1  
                                $features_filter")
            ->paginate(96);


        foreach ($products as &$p){
            $p->imgs = json_decode($p->images);
            $p->img = $this->imgSize(320, 200, $p->imgs[0]);

        }

        return view('category/category', compact(['category','products', 'features', 'max_min_price', 'page', 'mark', 'marks']));
    }

    public function add_review(Request $request){
        $data = $request->all();
        $user_id = \Auth::id();

        $required = [
            'name'  => 'required',
            'email' => 'required|email',
            'comment' => 'required',
        ];

        //если авторизированный юзер
        if($user_id){
            $required = [
                'comment' => 'required',
            ];
        }

        $validator = \Validator::make($data, $required,
        [
            'name.required'     => 'Введите имя',
            'email.required'    => 'Введите email',
            'email.email'       => 'Не валидный email',
            'comment.required'  => 'Введите  сообщение',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'validate_error' => true,
                'validate_messages' => response()->json($errors->all()),
            ]);
        }

        $review = new \App\Review();
        $review->product_id = $data['product_id'];

        //если авторизированный юзер
        if($user_id){
            $review->user_id = $user_id;
            $review->moderated = 1;
        } else {
            $review->name = $data['name'];
            $review->email = $data['email'];
        }

        $review->comment = $data['comment'];
        $review->rating = $data['rating'];

        if($review->save()){

            $message = "Ваш отзыв отправлен";

            return response()->json([
                'validate_error' => false,
                'message' => $message,
            ]);
        }
    }

    public function product_features(Request $request){
        if($request->has('id')) {
            $variant = Variant::where('id', '=', $request->get('id'))->first();
//            $variant->feats = \DB::table('options')->where('variant_id', '=', $variant->id)->pluck('value', 'feature_id')->toArray();
            $opts = \DB::table('options as o')
                ->leftJoin('features as f', 'f.id', '=', 'o.feature_id')
                ->selectRaw('f.id as feature_id, f.name, o.value, o.product_id')
                ->whereIn('o.product_id', (array)$variant->product_id)
                ->orWhereIn('o.variant_id', (array)$variant->id)
                ->orderBy('f.position', 'asc')->get();

            $options = [];
            foreach ( $opts as $opt ) {
                if(isset($options[$opt->feature_id])) {
                    $options[ $opt->feature_id ]->values[] = $opt->value;
                } else {
                    $options[ $opt->feature_id ] = $opt;
                    $options[ $opt->feature_id ]->values = (array)$opt->value;
                }

            }

            $brand = \DB::table('brands as b')
                ->join('products as p','b.id', '=', 'p.brand_id')
                ->join('variants as v', 'p.id', '=' ,'v.product_id')
                ->select('b.*')
                ->where('v.id', '=', $variant->id)->first();

            $view = \View::make('ajax.product_features', compact(['$variant', 'options', 'brand']))->render();

            return response()->json($view);

        }
        return false;
    }

}
