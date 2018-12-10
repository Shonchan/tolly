<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SitemapController extends Controller
{
    public function index(Request $request)
    {

        $max_id = \DB::table('variants')->selectRaw('max(id) as max_id')->pluck('max_id')->first();
        $pages = ceil($max_id/2000);
        $bot = $this->is_bot($request->userAgent());

        return response()->view('feeds.sitemap', compact(['pages', 'bot']), 200)->header('Content-Type', 'application/xml');
    }

    public function page(Request $request, $page)
    {

        $view = 'feeds.sitemap_part';

        if($page == 0) {
            $items = \DB::table( 'categories' )->where( 'enabled', '=', 1 )->get();
            $view = 'feeds.sitemap_categories';
        }
        else {
            $items = \DB::table( 'variants as v' )->join( 'products as p', 'p.id', '=', 'v.product_id' )
                ->select( 'p.name', 'v.name as vname', 'v.id', 'v.stock', 'v.updated_at' )
                ->where( 'p.enabled', '=', 1 )
                ->where( 'v.id', '>', 2000 * ( $page - 1 ) )
                ->where( 'v.id', '<=', 2000 * $page )->get();
        }

        $bot = $this->is_bot($request->userAgent());
        return response()->view($view, compact(['items', 'bot']), 200)->header('Content-Type', 'application/xml');
    }

    private function is_bot($engine){
        $bot = false;
        $google = ['Google'];
        $yandex = ['YandexWebmaster/2.0', 'Yandex'];
        foreach ($google as $b) {
            if (strstr($engine, $b)) $bot = 'google';
        }
        foreach ($yandex as $b) {
            if (strstr($engine, $b)) $bot = 'yandex';
        }
        return $bot;
    }
}

