<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\GarmentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('src.landing');
});

Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'registerUser'])->name('register');

Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'loginIndex'])->name('form.login');
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'loginUser'])->name('login');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logoutUser'])->name('logout');
    Route::get('/landing', function () {
        return view('src.landing');
    })->name('landing');
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