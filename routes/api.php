<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GaunController;
use App\Http\Controllers\PemesananController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/gaun', [GaunController::class, 'getAllGaun']);
    Route::post('/create-gaun', [GaunController::class, 'createGaun']);
    Route::post('/update-gaun/{kodeGaun}', [GaunController::class, 'updateGaun']);
    Route::get('/get-nota-detail/{kodeNota}', [PemesananController::class, 'getDetailNota']);
    Route::get('/delete-gaun/{kodeGaun}', [GaunController::class, 'deleteGaun']);
    Route::post('/logout', [AuthController::class, 'loginApi']);
});

Route::post('/login', [AuthController::class, 'loginApi']);
Route::post('/register', [AuthController::class, 'registerApi']);