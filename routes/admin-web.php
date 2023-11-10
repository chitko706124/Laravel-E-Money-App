<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WalletController;
use App\Http\Controllers\Backend\AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




// Route::get('/admin', function () {
//     // dd(session('admin_user'));
//     return "hello admin";
// })->middleware(CheckAdmin::class);

Route::prefix('admin')->middleware('auth:admin_users')->group(function () {

    Route::get('/', [PageController::class, 'home'])->name('admin.dashboard');
    
    Route::resource('admin-user', AdminUserController::class);
    Route::resource('user', UserController::class);

    Route::get('wallet', [WalletController::class, 'index'])->name('wallet.index');


    // Route::get('/admin-user/datatable/ssr', [AdminUserController::class, 'index'])->name('admin.datatable');
});
