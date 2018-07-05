<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        \Schema::defaultStringLength(191);
        $cart_total = 0;
        if( isset($_COOKIE['shopping_cart']) ) {
            $cart = (array)json_decode($_COOKIE['shopping_cart']);
            foreach ($cart as $k=>&$v) {
                $cart_total += $v;
            }
        }
        \View::share('cart_total', $cart_total);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
