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
Route::group(['middleware' => ['cacheResponse:600']],function() {
    Route::get('/','Admin@index')->name('index');
    Route::group(['middleware' => "role:product|super-admin"],function(){
        Route::resource('products', 'Product')->except('show');
        Route::resource('categories', 'Category')->only('index','store','show','update','destroy');
        Route::resource('countries', 'Country')->only('index','store','show','update','destroy');
        Route::resource('suppliers', 'Supplier')->only('index','store','show','update','destroy');
    });
    Route::group(['middleware' => 'role:order|super-admin'],function(){
        Route::resource('status', 'OrderStatus')->only('index','store','show','update','destroy');
        Route::resource('orders', 'Order')->only('index','update','edit');
    });
    Route::resource('blogs', 'Blog')->except('show')->middleware('role:blog|super-admin');
    Route::resource('slides', 'Slide')->except('show')->middleware('role:slide|super-admin');
    Route::resource('users', 'User')->except('show')->middleware('role:super-admin');
});
