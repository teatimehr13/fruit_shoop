<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\ProductController;

use Illuminate\Support\Facades\Route;

// Route::resource('product', ProductController::class);

Route::get('/', [ProductController::class, 'home'])->name('front.home.index');
// Route::get('/products/{categoryName?}', [ProductController::class, 'index'])
//     ->name('product.index');
// Route::get('/products/{category?}', [ProductController::class, 'index'])
//     ->name('product.index');

// 全部商品列表：/products
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

// 某分類底下的商品：/categories/水果
Route::get('/categories/{category}', [ProductController::class, 'index'])
    ->name('categories.products');


//DB cart crud (login後)    
Route::middleware('auth')->prefix('cart')->name('cart.')->group(function () {
    Route::post('/items', [CartController::class, 'store'])->name('store');
    Route::patch('/items/{cartItem}', [CartController::class, 'update'])->name('update');
    Route::delete('/items/{cartItem}', [CartController::class, 'destroy'])->name('destroy');
});
