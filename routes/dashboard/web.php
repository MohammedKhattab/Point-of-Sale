<?php

use Illuminate\Support\Facades\Route;

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

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){
        route::prefix('dasboard')->name('dashboard.')->middleware(['auth'])->group(function(){

            route::get('/index','dashboardController@index')->name('index');


            //user routes
            Route::resource('users', 'UserController')->except(['show']);

            //clients routes
            Route::resource('clients', 'ClientController')->except(['show']);
            Route::resource('clients.orders', 'Client\OrderController')->except(['show']);

            // categories routes
            Route::resource('categories', 'CategoryController')->except(['show']);


            // products routes
            Route::resource('products', 'ProductController')->except(['show']);

             // order routes
             Route::resource('orders', 'OrderController')->except(['show']);
             Route::get('/orders/{order}/products', 'OrderController@products')->name('orders.products');
         });


    });
