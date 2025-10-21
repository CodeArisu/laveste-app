<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\ProductController::class, 'index'])->name('index');

Route::get('/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('form');

Route::post('/create', [\App\Http\Controllers\ProductController::class, 'store'])->name('store');

Route::get('/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('show');

Route::get('/{product}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('edit');

Route::put('/{product}/edit', [\App\Http\Controllers\ProductController::class, 'update'])->name('update');

Route::delete('/{product}/r', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('delete');
