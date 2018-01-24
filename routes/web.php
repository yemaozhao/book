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

use App\Entity\Member;

Route::get('/', 'View\MemberController@toLogin');

Route::get('/login', 'View\MemberController@toLogin');

Route::get('/register', 'View\MemberController@toRegister');
Route::get('/category', 'View\BookController@toCategory');
Route::get('/product/category_id/{category_id}', 'View\BookController@toProduct');
Route::get('/product/{product_id}', 'View\BookController@toPdtContent');
Route::get('/cart', 'View\CartController@toCart');




Route::group(['prefix' => 'service'], function () {
	Route::any('validate/create', 'Service\ValidateController@create');
	Route::any('validate_phone/send', 'Service\ValidateController@sendSMS');
	Route::post('register', 'Service\MemberController@register');
	Route::post('login', 'Service\MemberController@login');
	Route::any('validate_email', 'Service\ValidateController@validateEmail');

	Route::get('category/parent_id/{parent_id}', 'Service\BookController@getCategoryByParentId');
	Route::get('cart/add/{product_id}', 'Service\CartController@addCart');
	Route::get('cart/delete', 'Service\CartController@deleteCart');

});

Route::group(['middleware' => 'check.login'], function () {
    
	Route::get('/order_commit', 'View\OrderController@toOrderCommit');
	Route::get('/order_list', 'View\OrderController@toOrderList');

    
});





