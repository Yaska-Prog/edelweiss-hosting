<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GaunController;
use App\Http\Controllers\PemesananController;
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

Route::get('/', function () {
    return redirect('/gaun');
});
Route::middleware('auth')->group(function () {
    Route::resource('gaun', GaunController::class)->names('gaun');
    Route::resource('pemesanan', PemesananController::class)->names('pemesanan');
    Route::post('check-kode-gaun', [GaunController::class, 'checkKode'])->name('check-kode-gaun');
    Route::get('availability', [PemesananController::class, 'availabilityScreen'])->name('availability-screen');
    Route::post('availability', [PemesananController::class, 'checkAvailability'])->name('check-availability');
    Route::get('grant-access', [AuthController::class, 'grantAccessScreen'])->name('grant-access');
    Route::post('grant-access', [AuthController::class, 'grantAccess'])->name('grant-access');
});
Route::get('login', [AuthController::class, 'loginView'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
