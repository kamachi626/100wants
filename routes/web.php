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
Route::get('/', "IndexController@index");
Route::get('/list/{id}', "ListController@show")->where("id", "^[0-9]+$");

Route::group(['middleware' => ['auth']], function () {
	Route::get('/list/create', "ListController@create");
	Route::post('/list/create', "ListController@update");
	Route::get('/list/{id}/edit', "ListController@edit")->where("id", "^[0-9]+$");
	Route::post('/list/{id}/edit', "ListController@update")->where("id", "^[0-9]+$");
	Route::get('/list/{id}/delete', "ListController@delete")->where("id", "^[0-9]+$");
});

Route::get('/login', "LoginController@login")->name("login");
Route::get('/login/callback', "LoginController@callback");
Route::get('/logout', "LoginController@logout");

Route::get('/{id}', "ProfileController@show")->where("id", "^[a-zA-Z0-9_]+$");
