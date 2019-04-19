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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//User Route Custom
Route::get('user/kaprodi', 'API\UserController@getKaprodi');
Route::get('user/export', 'API\UserController@export');
Route::get('user/find', 'API\UserController@search');

//Jurusan Route Custom
Route::get('jurusan/export', 'API\JurusanController@export');
Route::get('jurusan/find', 'API\JurusanController@search');

//Bidang Kompetensi Route Custom
Route::get('bidang-kompetensi/export', 'API\BidangKompetensiController@export');
Route::get('bidang-kompetensi/find', 'API\BidangKompetensiController@search');

//Profile Route
Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');

Route::apiResources([
    'user' => 'API\UserController',
    'jurusan' => 'API\JurusanController',
    'bidang-kompetensi' => 'API\BidangKompetensiController'
    ]);
