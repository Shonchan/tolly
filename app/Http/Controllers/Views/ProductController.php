<?php

namespace App\Http\Controllers\Views;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index($url){
        $category = Category::where('slug', '=', $url)->first();

        return view('category/category', compact(['category']));
    }

    public function show($url){
        $product = Product::where('slug', '=', $url)->first();
        $product->images = json_decode($product->images);
        $product->image = $product->images[0];
        return view('category/product', compact(['product']));
    }
}
