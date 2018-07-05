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
Route::middleware('guest')->group(function () {
    Route::get( '/', 'IndexController@index' );

    Route::get( '/products/{url}', 'Views\ProductController@show' )->name( 'product' );
    Route::get('/cart', 'Views\OrderController@cart');
    Route::post('/cart', 'Views\OrderController@addToCart')->name('addToCart');
    Route::post('/order', 'Views\OrderController@create')->name('createOrder');
    Route::post('/order/success', 'Views\OrderController@store');
    Route::get('/order/{hash}', 'Views\OrderController@show');
    Route::get( '/{url}', 'Views\ProductController@index' )->name( 'category' );
//    Route::get( '/{url}', 'Views\PagesController@show' )->name( 'page' );
});


