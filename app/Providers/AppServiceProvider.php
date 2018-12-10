<?php

namespace App\Providers;

use App\Category;
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
        $categories = Category::where('parent_id', '=', 0)
            ->where('type', '=', 'c')
            ->where('enabled', '=', 1)
            ->orderBy('position', 'asc')
            ->get();
        \View::share('cats', $categories);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Way\Generators\GeneratorsServiceProvider::class);
            $this->app->register(\Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
        }

        require_once __DIR__ . '/../Http/helpers.php';
    }
}
