<?php

use App\Http\Controllers\Front\ProductController;
use Illuminate\Support\Facades\Route;

// Route::resource('product', ProductController::class);

Route::get('/', [ProductController::class, 'home'])->name('front.home.index');
Route::get('/products', [ProductController::class, 'index'])
    ->name('product.index');
// Route::get('/products', [ProductController::class, 'index'])->name('front.products.index');
// Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('front.products.show');