<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('register', 'API\RegisterController@register');
//Route::resource('products', 'API\ProductController');

Route::middleware('auth:api')->group( function () {
	Route::get('list', 'API\ProductController@index');
	Route::put('discount/{id}','API\ProductController@discount');
	Route::post('create-bundle','API\ProductController@bundle');
	Route::post('order','API\OrderController@store');
	Route::resource('products', 'API\ProductController');
	
});


