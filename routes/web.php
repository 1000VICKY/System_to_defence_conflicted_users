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

Route::post("/admin/user", "Admin\UserController@upload");
