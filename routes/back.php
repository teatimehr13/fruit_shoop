<?php

use App\Http\Controllers\Back\ProductController as BackProductController;
use App\Http\Controllers\Back\CategoryController as BackCategoryController;
use App\Http\Controllers\Back\SubcategoryController as BackSubcategoryController;
use App\Http\Controllers\Back\ProductOptionController as BackProductOptionController;
use App\Http\Controllers\Back\OrderController as BackOrderController;
use App\Http\Controllers\Back\AboutController as BackAboutController;


use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('back')->name('back.')->group(function () {
    Route::resource('products', BackProductController::class);
    Route::resource('categories', BackCategoryController::class);
    Route::resource('orders', BackOrderController::class);
    Route::resource('about', BackAboutController::class);
});

Route::get('/back/categories.json', [BackCategoryController::class, 'indexJson'])
    ->name('back.categories.index.json');
