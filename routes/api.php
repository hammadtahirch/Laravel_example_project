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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@register');

Route::group(['middleware' => 'auth:api'], function () {

    Route::resource('user', 'Api\UserController');
    Route::resource('shop', 'Api\ShopController');
    Route::resource('collection', 'Api\CollectionController');
    Route::resource('template', 'Api\EmailTemplateController');
    Route::resource('shop/{shop_id}/time_slot', 'Api\ShopTimeSlotController');
    Route::resource('shop/{shop_id}/products', 'Api\ShopProductsController');
    Route::resource('product/{product_id}/variances', 'Api\ProductVarianceController');
    Route::resource('variance/{variance_id}/option', 'Api\VarianceOptionController');
    Route::resource('role', 'Api\RoleController');
    Route::resource('permission', 'Api\PermissionController');
    Route::get('details', 'Api\UserController@details');
    Route::post('sign_out', 'Api\UserController@signOut');

    Route::post('test_permission_roles', 'Api\TestApiController@AddPermissionAndRoles');

});
