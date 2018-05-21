<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){

        $cats = Category::all();

        return view('index', compact(['cats']));
    }
}
