<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Banner;

class BannerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //TODO для других страниц разруливаем по $view->getName() и соответствующей группе баннеров в БД

        View::composer(['index'], function($view){
            switch($view->getName()){
                case 'index': 
                    $banners = Banner::all()->where('status', '=', '1')->where('group', '=', 'main');
                    break;
            }

            $view->with('banners', $banners);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
