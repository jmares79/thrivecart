<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class)
    ->only(['index', 'show', 'store', 'update']);

Route::post('orders', OrderController::class)->name('order.create');
