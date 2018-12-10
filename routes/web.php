<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
//Route::middleware('guest')->group(function () {
    Route::get( '/', 'IndexController@index' )->name('main');

    Route::post('/ajax/search', 'Views\SearchController@get');
    Route::post('/ajax/search/get_categories', 'Views\SearchController@get_categories');
    Route::post('/ajax/search/more', 'Views\SearchController@more');
    Route::get('/search/{query}', 'Views\SearchController@all');
    Route::get( '/product/{url}', 'Views\ProductController@show' )->name( 'product' );
    Route::get('/cart', 'Views\OrderController@cart')->name('cart');
//    Route::post('/cart', 'Views\OrderController@addToCart')->name('addToCart');
    Route::post('/ajax/addtocart', 'Views\OrderController@addToCartAjax')->name('addToCartAjax');
    Route::post('/ajax/select_variant', 'Views\OrderController@selectVariantAjax');
    Route::post('/ajax/apply_coupone', 'Views\OrderController@applyСouponeAjax');
    Route::post('/ajax/recalculate_discount', 'Views\OrderController@recalculateDiscountAjax');
    Route::post('/order', 'Views\OrderController@create')->name('createOrder');
    Route::get('/order/check', 'Views\OrderController@check');
    Route::post('/order/pay', 'Views\OrderController@pay');
    Route::post('/order/success', 'Views\OrderController@store');
    Route::get('/order/{hash}', 'Views\OrderController@show');
    Route::get('/kontakty', 'Views\PagesController@contacts');
    Route::get('/feed/yandex.xml', 'Views\PagesController@yandex');
    Route::get('/feed/google.xml', 'Views\PagesController@google');
    Route::post('/ajax/products', 'Views\ProductController@showMore');
    Route::post('/ajax/filter', 'Views\ProductController@filter');
    Route::post('/ajax/click', 'Views\ProductController@click');
    Route::post('/ajax/callback', 'Views\OrderController@callback' );
    Route::post('/ajax/by_one_click', 'Views\OrderController@by_one_click' );
    Route::post('/ajax/add_review', 'Views\ProductController@add_review' );
    Route::post('/ajax/variant_features', 'Admin\VoyagerProductsController@variant_features' );
    Route::post('/ajax/product_features', 'Views\ProductController@product_features' );

    Route::get('/sitemap.xml', 'Views\SitemapController@index');
    Route::get('/sitemap_p0{page}.xml', 'Views\SitemapController@page');


    Route::get( '/{url}', 'Views\ProductController@index' )->name( 'category' );
    Route::get( '/{url}/page-{page}', 'Views\ProductController@index' )->name( 'category' );



//    Route::get( '/{url}', 'Views\PagesController@show' )->name( 'page' );
//});


