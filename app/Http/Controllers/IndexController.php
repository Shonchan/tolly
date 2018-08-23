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
            ->selectRaw('p.*, v.price')
            ->orderBy('created_at', 'desc')->limit(8)->get();

        foreach ($new_products as &$p){
            $p->imgs = json_decode($p->images);
            $p->img = $this->imgSize(320, 200, $p->imgs[0]);
        }
//        \Debugbar::info($new_products);
        $popular_products = \DB::table('products as p')->where('enabled', '=', 1)
            ->leftJoin('popularity as pp', 'pp.product_id', '=', 'p.id')
            ->leftJoin('variants as v', 'v.product_id', '=', 'p.id')
            ->selectRaw('p.*, v.price')
            ->orderBy('pp.weight', 'desc')->limit(8)->get();

        foreach ($popular_products as &$p){
            $p->imgs = json_decode($p->images);
            $p->img = $this->imgSize(320, 200, $p->imgs[0]);
        }
//        \Debugbar::info($popular_products);

        return view('index', compact(['new_products', 'popular_products']));
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
}
