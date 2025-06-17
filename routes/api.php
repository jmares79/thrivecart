<?php

use App\Http\Controllers\BasketController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class)
    ->only(['index', 'show', 'store', 'update']);

Route::resource('orders', OrderController::class)
    ->only(['store', 'show']);

Route::post('orders/{order}/total', BasketController::class)
    ->name('orders.total');
