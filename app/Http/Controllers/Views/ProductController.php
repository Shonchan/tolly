<?php

namespace App\Http\Controllers\Views;

use App\Category;
use App\Feature;
use App\Page;
use App\Product;
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
        $category = Category::where('slug', '=', $url)->first();

//        \Debugbar::info($category->products);
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


        $features = Feature::where('in_filter', '=', '1')
            ->where('category_id', '=', $category->id)->get();



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
            ->select('p.*','b.name as brand','v.price as price')
            ->paginate(36);
//            ->when($category, function($query, $category) {

//            return $query->innerJoin('products_categories as pc', 'pc.product_id', '=', 'p.id')->on('pc.category_id', '=', $category->id);
//        });



        foreach ($products as &$p){
            $p->imgs = json_decode($p->images);
            $p->img = $this->imgSize(320, 200, $p->imgs[0]);

        }

        return view('category/category', compact(['category','products', 'features', 'max_min_price', 'page']));
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
                    $orderBy = \DB::raw("p.id desc");
                    break;
                case 'price':
                    $orderBy = \DB::raw("v.price asc");
                    break;
                case 'rating':
                    $orderBy = \DB::raw("p.name asc");
                    break;
                case "discount":
                    $orderBy = \DB::raw("v.price desc");
                    break;
                default:
                    $orderBy = \DB::raw("p.id desc");
                    break;
            }
        } else {
            $orderBy = \DB::raw("p.id desc");
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
                $features_filter .= \DB::raw( "AND p.id in (SELECT product_id FROM options WHERE feature_id = $feature AND value in ($val) ) " );
            }
//        \Debugbar::info($features_filter);
        $price_filter = " AND v.price >= $min_price AND v.price <= $max_price ";

        $cids = $category->childIds();
        $cids[] = $category->id;
        $geo_id = 213;

        $products = \DB::table('products as p')
            ->join('products_categories as pc', function($join) use ($cids)
            {
                $join->on('pc.product_id', '=', 'p.id')
                    ->whereIn('pc.category_id', $cids);

            })
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
//            ->leftJoin('popularity as pop', function($join) use ($geo_id){
//                $join->on('pop.product_id', '=', 'p.id')
//                    ->whereIn('p.geo_id', $geo_id);
//            })
            ->leftJoin('variants as v', function($join){
                $join->on('v.product_id', '=', 'p.id');
            })
            ->select('p.*','b.name as brand','v.price as price')
            ->whereRaw("1 $price_filter 
                                $features_filter")
            ->orderByRaw($orderBy)
            ->paginate(36);
//            ->when($category, function($query, $category) {

//            return $query->innerJoin('products_categories as pc', 'pc.product_id', '=', 'p.id')->on('pc.category_id', '=', $category->id);
//        });

        foreach ($products as &$p){
            $p->imgs = json_decode($p->images);
            $p->img = $this->imgSize(320, 200, $p->imgs[0]);
        }


        $data = [];
        $data['offers'] = view('layouts.products', compact(['products', 'page']))->render();
        return response()->json($data);
    }

    public function imgSize($width=320, $height=200, $img){
        if(empty($img))
            $img = $this->img();

        $resizePath = storage_path('app/public').DIRECTORY_SEPARATOR;
        $parts = explode('.', $img);
        $filename = $parts[0].$width.'x'.$height.'.'.$parts[1];
        if (file_exists($resizePath.$filename))
            return url ('storage', $filename);

        $image = \Image::make($resizePath.$img)->resize($width, $height);
        $image->save($resizePath.$filename);
        return url ('storage', $filename);

    }

    public function filter(Request $request){
        if($request->has('id')){
//            $category = Category::where('id', '=', $request->get('category'))->first();
            $cat_id = $request->get('id');
        } else  {
            return response()->json(false);
        }

        $features = Feature::where('in_filter', '=', '1')
            ->where('category_id', '=', $cat_id)->get();

        $data = view('layouts.ajax-filter', compact(['features']))->render();
        return response()->json($data);

    }

    public function show($url)
    {
        $product = Product::where('slug', '=', $url)->first();
        $product->images = json_decode($product->images);
        $product->image = $product->images[0];

        $options = \DB::table('options as o')
            ->leftJoin('features as f', 'f.id', '=', 'o.feature_id')
            ->selectRaw('f.id as feature_id, f.name, o.value, o.product_id')
            ->whereIn('o.product_id', (array)$product->id)
            ->orderBy('f.position', 'asc')->get();
//        \Debugbar::info($options);

        return view('category/product', compact(['product', 'options']));
    }
}
