<?php

use App\Http\Controllers\api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/product', [ProductController::class, 'store'])->name('store.product');
Route::put('/product/{product}', [ProductController::class, 'update'])->name('update.product');

Route::put('/test', [ProductController::class, 'test'])->name('test.product');