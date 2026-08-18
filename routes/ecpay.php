<?php

use App\Http\Controllers\Ecpay\PaymentController;
use Illuminate\Support\Facades\Route;

// 綠界伺服器直接呼叫，不能要求登入
Route::post('/ecpay/return', [PaymentController::class, 'returnUrl'])->name('ecpay.return');
Route::post('/ecpay/result', [PaymentController::class, 'frontOrderResultURL'])->name('ecpay.result');
Route::post('/ecpay/notify', [PaymentController::class, 'notifyUrl'])->name('ecpay.notify');

// 使用者本人操作的付款頁面，需要登入
Route::middleware('auth')->group(function () {
    Route::get('/ecpay/checkout/{order}', [PaymentController::class, 'checkout'])->name('ecpay.checkout');
    Route::get('/payment/retry/{order}', [PaymentController::class, 'retry'])->name('payment.retry');
});
