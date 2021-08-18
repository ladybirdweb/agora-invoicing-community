<?php

//use App\Http\Controllers\License\LicenseController;
use App\Http\Controllers\License\LicenseController;
use App\Http\Controllers\License\LocalizedLicenseController;
use App\Http\Controllers\License\EncryptDecryptController;
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

//Route::get('/license/{first_name}/{last_name}/{email}',[LicenseController::class,'addNewProduct');

Route::post('/downloadFile', [LocalizedLicenseController::class,'downloadFile'])->name('event.rsvp')->middleware('signed');
Route::post('/uploadFile',[LocalizedLicenseController::class,'storeFile']);
Route::post('/request',[LocalizedLicenseController::class,'tempOrderLink']);
Route::get('/show',[LocalizedLicenseController::class,'showAllFiles']);
Route::post('/edit',[LocalizedLicenseController::class,'fileEdit']);
Route::post('/delete',[LocalizedLicenseController::class,'deleteFile']);
Route::post('encrypt',[EncryptDecryptController::class,'encrypt']);
Route::post('decrypt',[EncryptDecryptController::class,'decrypt']);
Route::get('generate',[EncryptDecryptController::class,'generate']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
