<?php

use App\Http\Controllers\Back\ProductController as BackProductController;
use App\Http\Controllers\Back\CategoryController as BackCategoryController;
use App\Http\Controllers\Back\SubcategoryController as BackSubcategoryController;
use App\Http\Controllers\Back\ProductOptionController as BackProductOptionController;
use App\Http\Controllers\Back\OrderController as BackOrderController;
use App\Http\Controllers\Back\AboutController as BackAboutController;
use App\Http\Controllers\Back\ProductImageController as BackProductImageController;



use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('back')->name('back.')->group(function () {
    Route::resource('products', BackProductController::class);
    Route::resource('categories', BackCategoryController::class);
    Route::resource('orders', BackOrderController::class);
    Route::resource('about', BackAboutController::class);
    // Route::resource('productOptions', BackProductOptionController::class)->except(['index']);
    Route::resource('product.options', BackProductOptionController::class)
        ->except(['create', 'edit', 'show'])
        ->shallow()
        ->parameters([
            'options' => 'productOption',   // 讓 {option} 變成 {productOption}
        ]);;

    Route::resource('product.images', BackProductImageController::class)
        ->except(['create', 'edit', 'show'])
        ->shallow()
        ->parameters([
            'images' => 'productImage',
        ]);;
});

Route::get('/back/categories.json', [BackCategoryController::class, 'indexJson'])
    ->name('back.categories.index.json');
