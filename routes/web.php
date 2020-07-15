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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get("/", "TopController@index");
Route::get("/admin/import", "Admin\ImportController@index");
Route::post("/admin/import/upload", "Admin\ImportController@upload");

// ユニークな会員一覧画面
Route::get("/admin/user/index", "Admin\UserController@index");
Route::get("/admin/user", "Admin\UserController@index");
Route::get("/admin/user/detail/{unique_user_id}", "Admin\UserController@detail");
Route::get("/admin/user/all", "Admin\UserController@all");
Route::get("/admin/user/contact/{unique_user_id}", "Admin\UserController@contact");

// ユニークなイベント一覧画面
Route::get("/admin/event/index", "Admin\EventController@index");
Route::get("/admin/event", "Admin\EventController@index");
Route::get("/admin/event/{event_id}", "Admin\EventController@detail");
