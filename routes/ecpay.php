<?php

// use App\Http\Controllers\Ecpay\PaymentController;

use App\Http\Controllers\Ecpay\PaymentController;

use Illuminate\Support\Facades\Route;

// Route::post('/ecpay/checkout', [PaymentController::class, 'checkout'])->name('ecpay.checkout');
Route::post('/ecpay/return', [PaymentController::class, 'returnUrl'])->name('ecpay.return');
Route::post('/ecpay/result',   [PaymentController::class, 'frontOrderResultURL'])->name('ecpay.result');
Route::post('/ecpay/notify', [PaymentController::class, 'notifyUrl'])->name('ecpay.notify');
Route::get('/payment/retry/{order}', [PaymentController::class, 'retry'])->name('payment.retry');

Route::get('/ecpay/checkout/{order}', [PaymentController::class, 'checkout'])
    ->name('ecpay.checkout');