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

//Dashboard Route Custom
Route::get('dashboard', 'API\DashboardController@index');

//User Route Custom
Route::get('user/select/kaprodi', 'API\UserController@getKaprodiData');
Route::get('user/export', 'API\UserController@export');
Route::get('user/find', 'API\UserController@search');
Route::post('user/add', 'API\UserController@storeUserMahasiswa');

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
Route::get('mahasiswa/point/{id}', 'API\MahasiswaController@setPoint');
Route::get('mahasiswa/achievement', 'API\MahasiswaController@achievement');

//Kompetensi Route Custom
Route::get('kompetensi/export', 'API\KompetensiController@export');
Route::get('kompetensi/find', 'API\KompetensiController@search');
Route::put('kompetensi/skpi/validation/{id}', 'API\KompetensiController@changeValidation');
Route::get('kompetensi/skpi/{id}', 'API\KompetensiController@skpiKompetensi');
Route::get('kompetensi/skpi/{id}/find', 'API\KompetensiController@skpiSearchKompetensi');

//Bukti Kompetensi Wajib Route Custom
Route::get('bukti-kompetensi-wajib/find', 'API\BuktiKompetensiWajibController@search');

//Skpi Route Custom
Route::get('skpi/export', 'API\SkpiController@export');
Route::get('skpi/find', 'API\SkpiController@search');
Route::get('skpi/cek', 'API\SkpiController@checkStatus');
Route::get('skpi/preview', 'API\SkpiController@preview');
Route::get('skpi/publish/{id}', 'API\SkpiController@publish');

//Profile Route
Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');

//Backup Route
Route::get('backup/download/{name}', 'API\BackupController@download');
Route::get('backup/restore/{name}', 'API\BackupController@restore');

Route::apiResources([
    'user' => 'API\UserController',
    'jurusan' => 'API\JurusanController',
    'bidang-kompetensi' => 'API\BidangKompetensiController',
    'kompetensi-wajib' => 'API\KompetensiWajibController',
    'mahasiswa' => 'API\MahasiswaController',
    'kompetensi' => 'API\KompetensiController',
    'bukti-kompetensi-wajib' => 'API\BuktiKompetensiWajibController',
    'skpi' => 'API\SkpiController',
    'backup' => 'API\BackupController'
    ]);
