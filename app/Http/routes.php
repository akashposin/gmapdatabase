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

Route::get('/', function () {
    return view('auth.login');
});

Route::auth();

Route::get('/home', 'HomeController@index');
Route::post('home/show', 'HomeController@show');
Route::get('home/googlemap', 'HomeController@googlemap');
Route::get('home/newlocation', 'HomeController@newlocation');
Route::post('home/upload', 'HomeController@upload');
Route::get('edit/{id}', 'HomeController@edit');
Route::post('home/update/{id}','HomeController@update');