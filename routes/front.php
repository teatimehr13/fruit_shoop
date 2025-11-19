<?php

use App\Http\Controllers\Front\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('product', ProductController::class);