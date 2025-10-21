<?php

use Illuminate\Support\Facades\Route;

// this routes to the product info through add to garment
Route::get('/', [\App\Http\Controllers\GarmentController::class, 'index'])->name('index');

Route::post('/{products}/create', [\App\Http\Controllers\GarmentController::class, 'store'])->name('store');

Route::get('/{garment}/edit', [\App\Http\Controllers\GarmentController::class, 'edit'])->name('edit');

Route::put('/{garment}', [\App\Http\Controllers\GarmentController::class, 'update'])->name('update');

Route::get('/{garment}/details', [\App\Http\Controllers\GarmentController::class, 'show'])->name('show');

Route::delete('/{garment}/r', [\App\Http\Controllers\GarmentController::class, 'destroy'])->name('delete');
