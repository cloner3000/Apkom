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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/validation', function () {
    return view('validation');
})->name('validation');
Route::post('/check', 'CheckSkpiController@checkSkpi');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/chart/save', 'HomeController@saveChart');
Route::get('/chart/{id}', 'HomeController@chart');
Route::get('{path}',"HomeController@index")->where( 'path', '([A-z\/_.\d-]+)?' );
