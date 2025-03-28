<?php

use App\Http\Controllers\Api\{GarmentController, ProductController};
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group( function () {
    Route::post('/register', 'registerUser')->name('register');
    Route::post('/login', 'loginUser')->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', 'logout')->name('logout');
    });
});

Route::name('product.')->prefix('product')->group( function () {
    Route::controller(ProductController::class)->group( function () {
        Route::post('/new', 'store')->name('store');
        Route::put('/{product}', 'update')->name('update');
    });
});

Route::post('/garment/new', [GarmentController::class, 'store'])->name('store.garment');

Route::put('/test', [ProductController::class, 'test'])->name('test.product');
