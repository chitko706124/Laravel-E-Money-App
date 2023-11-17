<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Frontend\NotificationController;
use App\Http\Controllers\Frontend\PageController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

//admin-login
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm']);
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// user login
Auth::routes();
// Route::controller(AuthController::class)->group(function () {
//     Route::get('/send-email', 'sendEmail')->name('send.email');
//     Route::post('/OTP-code', 'otpCode')->name('otp.page');
//     Route::post('/OTP-code-store', 'otpCodeStore')->name('otp.store');
// });

Route::middleware('auth')
    ->controller(PageController::class)
    ->group(function () {
        Route::get('/', 'home')->name('home');

        Route::get('/profile', 'profile')->name('profile');
        Route::get('/update-password', 'updatePassword')->name('update-password');
        Route::post('/update-password', 'updatePasswordStore')->name('update-password.store');

        Route::get('/friend', 'friend')->name('friend');
        Route::get('/friend/add', 'friendAdd')->name('friend.add');
        Route::get('/friend/add/store', 'friendAddStore')->name('friend.addStore');


        Route::get('/wallet', 'wallet')->name('wallet');

        Route::get('/transfer', 'transfer')->name('transfer');
        Route::get('/transfer/confirm', 'transferConfirm')->name('transfer.confirm');
        Route::post('/transfer/complete', 'transferComplete')->name('transfer.complete');

        Route::get('/receive-qr', 'receiveQR')->name('receive.qr');
        Route::get('/scan-pay', 'scanAndPay')->name('scan.pay');
        Route::get('/scan-pay-transfer', 'scanAndPayTransfer')->name('scan.pay.transfer');

        Route::get('/transaction', 'transaction')->name('transaction');
        Route::get('/transaction/{trx_id}', 'transactionDetail')->name('transaction.detail');

        Route::get('/to-account-verify', 'toAccountVerify');
        Route::get('password-check', 'passwordCheck')->name('password.check');
        Route::get('/transfer-hash', 'transferHash')->name('transfer.hash');
    });



Route::middleware('auth')
    ->controller(NotificationController::class)
    ->group(function () {
        Route::get('/notification', 'index')->name('notification.index');
        Route::get('/notification/{id}', 'show')->name('notification.show');
    });
