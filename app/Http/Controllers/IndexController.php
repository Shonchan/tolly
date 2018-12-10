<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){

//        $cats = Category::all();
        $new_products = \DB::table('products as p')->where('enabled', '=', 1)
            ->leftJoin('variants as v', 'v.product_id', '=', 'p.id')
            ->selectRaw('p.*, v.id as vid, v.price, v.name as vname' )
            ->where('v.stock', '>', 0)
            ->orderBy('created_at', 'desc')->limit(8)->get();

        foreach ($new_products as &$p){

            $p->imgs = json_decode($p->images);
          //  dd($p->images);
            $p->img = $this->imgSize(320, 200, $p->imgs[0]);
        }
//        \Debugbar::info($new_products);
        $geo_id = 1;
        $popular_products = \DB::table('products as p')->where('enabled', '=', 1)
            ->leftJoin('popularity as pop', function($join) use ($geo_id)
            {
                $join->on('pop.product_id', '=', 'p.id')
                    ->where('pop.geo_id', '=', $geo_id);

            })
            ->leftJoin('variants as v', 'v.product_id', '=', 'p.id')
            ->selectRaw('p.*, v.id as vid, v.price, v.name as vname')
            ->where('v.stock', '>', 0)
            ->orderBy('pop.weight', 'desc')->limit(8)->get();

        foreach ($popular_products as &$p){
            $p->imgs = json_decode($p->images);
            $p->img = $this->imgSize(320, 200, $p->imgs[0]);
        }
//        \Debugbar::info($popular_products);

        return view('index', compact(['new_products', 'popular_products']));
    }

    private function imgSize($width=320, $height=200, $img){
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
}
