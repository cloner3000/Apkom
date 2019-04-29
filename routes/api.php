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
Route::get('user/select/kaprodi', 'API\UserController@getKaprodiData');
Route::get('user/export', 'API\UserController@export');
Route::get('user/find', 'API\UserController@search');

//Jurusan Route Custom
Route::get('jurusan/select', 'API\JurusanController@getJurusanData');
Route::get('jurusan/export', 'API\JurusanController@export');
Route::get('jurusan/find', 'API\JurusanController@search');

//Bidang Kompetensi Route Custom
Route::get('bidang-kompetensi/select', 'API\BidangKompetensiController@getBidangKompetensiData');
Route::get('bidang-kompetensi/export', 'API\BidangKompetensiController@export');
Route::get('bidang-kompetensi/find', 'API\BidangKompetensiController@search');

//Kompetensi Wajib Route Custom
Route::get('kompetensi-wajib/export', 'API\KompetensiWajibController@export');
Route::get('kompetensi-wajib/find', 'API\KompetensiWajibController@search');

//Mahasiswa Route Custom
Route::get('mahasiswa/profile', 'API\MahasiswaController@profile');
Route::get('mahasiswa/export', 'API\MahasiswaController@export');
Route::get('mahasiswa/find', 'API\MahasiswaController@search');

//Kompetensi Route Custom
Route::get('kompetensi/export', 'API\KompetensiController@export');
Route::get('kompetensi/find', 'API\KompetensiController@search');

//Bukti Kompetensi Wajib Route Custom
Route::get('bukti-kompetensi-wajib/find', 'API\BuktiKompetensiWajibController@search');

//Profile Route
Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');

Route::apiResources([
    'user' => 'API\UserController',
    'jurusan' => 'API\JurusanController',
    'bidang-kompetensi' => 'API\BidangKompetensiController',
    'kompetensi-wajib' => 'API\KompetensiWajibController',
    'mahasiswa' => 'API\MahasiswaController',
    'kompetensi' => 'API\KompetensiController',
    'bukti-kompetensi-wajib' => 'API\BuktiKompetensiWajibController',
    ]);
