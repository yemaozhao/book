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

//////////////////////////----------调试---------////////////////////
use App\Entity\Member;


// Route::get('/test', function () {
 
//     if (\Cache::has('testOne')) {
//         echo '存在chche,读取' . '<br />';
//         echo \Cache::get('testOne');
//     } else {
//         echo '不存在cache,现在创建' . '<br />';
//         $time = \Carbon\Carbon::now()->addMinutes(10);
//         $redis = \Cache::add('testOne', '我是缓存资源,显示当前时间：'.\Carbon\Carbon::now(), $time);
//         echo \Cache::get('testOne');
//     }
// });

/////////////////////////////////////////////////////////
Route::group(['middleware' => 'active.record'], function () {

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

      Route::any('upload/{type}', 'Service\UploadController@uploadFile');
      

    });

    Route::group(['middleware' => 'check.login'], function () {
        
    	Route::get('/order_commit', 'View\OrderController@toOrderCommit');
    	Route::get('/order_list', 'View\OrderController@toOrderList');

    	Route::post('service/basic_pay', 'Service\PayController@basicPay');


        
    });

});


/***********************************后台相关***********************************/



Route::group(['prefix' => 'admin'], function () {

  Route::get('login', 'Admin\IndexController@toLogin');
  Route::get('exit', 'Admin\IndexController@toExit');
  Route::post('service/login', 'Admin\IndexController@login');

  Route::group(['middleware' => 'check.admin.login'], function () {

    Route::group(['prefix' => 'service'], function () {
      Route::post('category/add', 'Admin\CategoryController@categoryAdd');
      Route::post('category/del', 'Admin\CategoryController@categoryDel');
      Route::post('category/edit', 'Admin\CategoryController@categoryEdit');

      Route::post('product/add', 'Admin\ProductController@productAdd');
      Route::post('product/del', 'Admin\ProductController@productDel');
      Route::post('product/edit', 'Admin\ProductController@productEdit');

      Route::post('member/edit', 'Admin\MemberController@memberEdit');

      Route::post('order/edit', 'Admin\OrderController@orderEdit');
    });

    Route::get('index', 'Admin\IndexController@toIndex');

    Route::get('category', 'Admin\CategoryController@toCategory');
    Route::get('category_add', 'Admin\CategoryController@toCategoryAdd');
    Route::get('category_edit', 'Admin\CategoryController@toCategoryEdit');

    Route::get('product', 'Admin\ProductController@toProduct');
    Route::get('product_info', 'Admin\ProductController@toProductInfo');
    Route::get('product_add', 'Admin\ProductController@toProductAdd');
    Route::get('product_edit', 'Admin\ProductController@toProductEdit');

    Route::get('member', 'Admin\MemberController@toMember');
    Route::get('member_edit', 'Admin\MemberController@toMemberEdit');

    Route::get('order', 'Admin\OrderController@toOrder');
    Route::get('order_edit', 'Admin\OrderController@toOrderEdit');
  });
});





