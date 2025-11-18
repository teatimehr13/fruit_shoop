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
    Route::patch('subcategories/reorder', [BackSubcategoryController::class, 'reorder'])->name('subcategories.reorder');
    
    Route::resource('products', BackProductController::class);
    Route::resource('categories', BackCategoryController::class);
    Route::resource('categories.subcategories', BackSubcategoryController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->shallow();

    Route::resource('orders', BackOrderController::class);
    Route::get('about', [BackAboutController::class, 'index'])->name('about.index');
    Route::post('about/save', [BackAboutController::class, 'save'])->name('about.save');
    // Route::resource('about', BackAboutController::class);
    // Route::resource('productOptions', BackProductOptionController::class)->except(['index']);
    Route::resource('product.options', BackProductOptionController::class)
        ->except(['create', 'edit', 'show'])
        ->shallow()
        ->parameters([
            'options' => 'productOption',   // 讓 {option} 變成 {productOption}
        ]);;

    Route::post('products/{product}/options/save', [BackProductOptionController::class, 'save'])->name('products.options.save');  

    Route::resource('product.images', BackProductImageController::class)
        ->except(['create', 'edit', 'show'])
        ->shallow()
        ->parameters([
            'images' => 'productImage',
        ]);;

    Route::post('product/images', [BackProductImageController::class, 'destroyMany'])->name('product.images.destroymany');
    Route::patch('product/images/{productImage}/primary', [BackProductImageController::class, 'setPrimary'])->name('product.images.primary');
    Route::patch('product/images/reorder', [BackProductImageController::class, 'reorder'])->name('product.images.reorder');

    Route::get('product/{product}/details', [BackProductController::class, 'details'])->name('product.details');
    Route::patch('product/{product}/changeStatus', [BackProductController::class, 'changeStatus'])->name('product.changeStatus');

    Route::get('/subcategories/{category}', [BackProductController::class, 'getSubcategories'])->name('product.getSubcategories');
});

Route::get('/back/categories.json', [BackCategoryController::class, 'indexJson'])
    ->name('back.categories.index.json');
