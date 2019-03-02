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

//Route::get('/', function () {
//    dd(env("DB_CONNECTION"));
//});
use Illuminate\Support\Facades\Cache;

Route::get('admin/{path?}', 'React\ReactController@adminPortal')->where('path', '.*');
Route::get('/', function () {
echo phpinfo();
});
