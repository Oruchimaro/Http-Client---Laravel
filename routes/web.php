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

Route::get('/', 'WelcomeController@show')->name('welcome');

Route::get('authorization', 'Auth\LoginController@authorization')->name('authorization');

Route::get('products/{title}-{id}', 'ProductsController@show')->name('products.show');

Route::get('categories/{title}-{id}/products', 'CategoryProductController@show')->name('categories.products.show');
Auth::routes(['register' => false, 'reset' => false]); //disable register and reset routes

Route::get('/home', 'HomeController@index')->name('home');
