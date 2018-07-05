<?php

namespace App\Http\Controllers\Views;

use App\Category;
use App\Page;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{

    protected $pagesController;
    public function __construct(PagesController $pagesController)
    {
        $this->pagesController = $pagesController;
    }

    public function index($url){
        $category = Category::where('slug', '=', $url)->first();

//        \Debugbar::info($category->products);
        if(!$category){
//            return redirect()->action('Views\PagesController@show', $url);

            return $this->pagesController->show($url);
        }

        return view('category/category', compact(['category']));
    }


    public function show($url){
        $product = Product::where('slug', '=', $url)->first();
        $product->images = json_decode($product->images);
        $product->image = $product->images[0];
        return view('category/product', compact(['product']));
    }
}
