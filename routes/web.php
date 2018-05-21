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

Route::get('/', 'IndexController@index');
Route::get( '/category/{url}', 'Views\ProductController@index' )->name('category');
Route::get( '/products/{url}', 'Views\ProductController@show' )->name('product');

Route::get( '/{url}', 'Views\PagesController@show' )->name('page');


