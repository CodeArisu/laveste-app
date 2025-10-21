<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('home');

Route::post('/code', [\App\Http\Controllers\DashboardController::class, 'generate'])->name('code');
Route::post('/code/register', [\App\Http\Controllers\DashboardController::class, 'register'])->name('code.register');

Route::get('/users', [App\Http\Controllers\Users\UserController::class, 'index'])->name('users')->middleware(['role:admin']);
// dashboard products routes
Route::middleware(['role:admin'])->name('product.')->prefix('product')->group(function () {
    require __DIR__ . '/products.php';
});

// dashboard garment routes
Route::middleware(['role:admin,manager'])->name('garment.')->prefix('garment')->group(function () {
    require __DIR__ . '/garments.php';
});

Route::get('/transactions', [App\Http\Controllers\DashboardController::class, 'transactions'])->name('transactions');
Route::get('/rented', [App\Http\Controllers\DashboardController::class, 'rented'])->name('rented');
