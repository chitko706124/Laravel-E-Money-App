<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::post('/logout', 'logout')->middleware('auth:api')->name('logout');
});

Route::controller(PageController::class)->middleware(['auth:api'])->group(function () {
    Route::get('/profile', 'profile');

    Route::get('/transactions', 'transactions');
    Route::get('/transaction/{trx_no}', 'transactionDetail');
});
