<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('products', ProductController::class)
    ->only(['index', 'show', 'store', 'update']);

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
