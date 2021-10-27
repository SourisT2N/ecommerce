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
Route::get('san-pham/{name}','HomeController@showProduct')->name('product.show');


Route::group(['middleware' => ['cacheResponse:600']],function() {
    Route::get('/', 'HomeController@index')->name('index');
    Route::get('san-pham','HomeController@product')->name('product');
    Route::get('danh-muc/{name}','HomeController@product')->name('category');
    Route::get('tin-tuc', 'HomeController@blog')->name('blog');
    Route::get('tin-tuc/{name}', 'HomeController@showBlog')->name('blog.show');
});

Route::group(['middleware' => ['auth']],function (){
    Route::get('user/logout','Auth\AuthController@logout')->name('user.logout');
    Route::get('thanh-toan','HomeController@checkout')->name('checkout');
    Route::group(['prefix' => 'user','as' => 'user.'],function(){
        Route::get('/','UserController@index')->name('index');
        Route::get('changePassword','UserController@changePassword')->name('changePassword');
        Route::get('don-hang','UserController@orders')->name('order');
        Route::get('don-hang/{id}','UserController@showOrder')->name('order.show');
    });
    Route::group(['prefix' => 'ajax'],function() {
        Route::get('total/{id}','OrderController@orderTotal')->name('total');
        Route::resource('cart','CartController')->except(['create','show']);
        Route::post('order','OrderController@orderDefault')->name('order.default');
        Route::patch('order/{id}','OrderController@updateOrder')->name('order.update');
        Route::post('comment/{id}','CommentController@store')->name('comment.store');
        Route::patch('user/update','Auth\AuthController@updateInfo')->name('user.update');
    });
});

Route::group(['middleware' => 'guest'],function() {
    Route::get('login',"HomeController@login")->name('login');
    Route::get('register',"HomeController@register")->name('register');
    Route::get('forgot',"HomeController@forgot")->name('forgot');
    Route::get('user/reset/{code}','HomeController@reset')->name('user.reset');
    Route::get('user/auth/{code}','HomeController@active')->name('user.code');
    Route::group(['namespace' => 'Auth'],function() {
        Route::post('login',"AuthController@login")->name('login.post');
        Route::post('register',"AuthController@register")->name('register.post');
        Route::post('forgot','AuthController@forgot')->name('forgot');
        Route::post('user/reset/{code}','AuthController@resetPassword')->name('user.reset');
        Route::get('auth/{provider}','AuthController@callback')->name('callback');
        Route::get('redirect/{provider}','AuthController@redirect')->name('redirect');
    });
});

Route::group(['middleware' => ['auth','role:admin|super-admin']],function() {
    Route::group(['prefix' => 'laravel-filemanager', 'middleware'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
});
