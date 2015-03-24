<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', 'HomeController@index');

Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', ['as' => 'login-post', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);


Route::get('authCheck', function(){
	return Response::json(Auth::check());
});

Route::controller('settings','SettingsController');
Route::controller('users','UsersController');
Route::controller('roles','RolesController');

