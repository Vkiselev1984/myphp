<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Products API
Route::apiResource('products', \App\Http\Controllers\ProductController::class)->names('products');
