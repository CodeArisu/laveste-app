<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\{GarmentController, ProductController};
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group( function () {
    Route::post('/register', 'registerUser')->name('register');

    Route::get('/login', 'loginIndex')->name('form.login');
    Route::post('/login', 'loginUser')->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', 'logoutUser')->name('logout');
        Route::get('/landing', function () {
            return view('src.landing');
        })->name('landing');
    });
});

Route::name('product.')->prefix('product')->group( function () {
    Route::post('/create', [\App\Http\Controllers\Api\ProductController::class, 'store'])->name('store');
    Route::put('/{product}', [\App\Http\Controllers\Api\ProductController::class, 'update'])->name('update');
    Route::delete('/{product}/r', [\App\Http\Controllers\Api\ProductController::class, 'destroy'])->name('delete');

    Route::resource('products', ProductController::class)->except(['create', 'edit', 'destroy', 'update']);
});

Route::name('garment.')->prefix('garment')->group( function () {
    Route::post('/create', [\App\Http\Controllers\Api\GarmentController::class, 'store'])->name('store');
    Route::put('/{garment}', [\App\Http\Controllers\Api\GarmentController::class, 'update'])->name('update');
    Route::delete('/{garment}/r', [\App\Http\Controllers\Api\GarmentController::class, 'destroy'])->name('delete');

    Route::resource('garments', GarmentController::class)->except(['create', 'edit', 'destroy', 'update']);
});

Route::post('/catalog/{$garment}', [App\Http\Controllers\Api\CatalogController::class, 'store'])->name('catalog.store');