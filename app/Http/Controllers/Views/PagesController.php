<?php

namespace App\Http\Controllers\Views;

use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    public function show($url){
        $page = Page::where('slug', '=', $url)->first();

        if(!$page){
            return response()->view('errors.404', compact(['url']), '404');
        }

        return view('pages.page', compact(['page']));
    }

    public function contacts(){
        return view('pages.contacts');
    }

    public function yandex(){
        $file = \Storage::disk('public')->path('feed/yandex.xml');
        $xml = file_get_contents($file);
        return response($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    public function google(){
        $file = \Storage::disk('public')->path('feed/google.xml');
        $xml = file_get_contents($file);
        return response($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
