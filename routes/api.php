<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\{GarmentController, ProductController};
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group( function () {
    Route::post('/register', 'registerUser')->name('register');
    Route::post('/login', 'loginUser')->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', 'logoutUser')->name('logout');
    });
});


Route::controller(ProductController::class)->group( function () {
    Route::name('product.')->prefix('product')->group( function () {
        Route::post('/create', 'store')->name('store');
        Route::put('/{product}', 'update')->name('update');
        Route::delete('/{product}/r', 'destroy')->name('delete');
    });

    Route::resource('products', ProductController::class)->except(['create', 'edit', 'destroy', 'update']);
});


Route::controller(GarmentController::class)->group( function() {
    Route::name('garment.')->prefix('garment')->group( function () {
        Route::post('/create', [GarmentController::class, 'store'])->name('store');
        Route::put('/{garment}', [GarmentController::class, 'update'])->name('update');
        Route::delete('/{garment}/r', [GarmentController::class, 'destroy'])->name('delete');
    });

    Route::resource('garments', GarmentController::class)->except(['create', 'edit', 'destroy', 'update']);
});