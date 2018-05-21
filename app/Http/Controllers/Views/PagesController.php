<?php

namespace App\Http\Controllers\Views;

use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function show($url){
        $page = Page::where('slug', '=', $url)->first();

        return view('pages.page', compact(['page']));
    }
}
