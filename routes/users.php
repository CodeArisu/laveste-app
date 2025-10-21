<?php

use Illuminate\Support\Facades\Route;

// User detail routes
// this api should add user details for a specific user
Route::post('/add-details/{user}', [\App\Http\Controllers\Users\AuthController::class, 'store'])->name('add.details');

Route::get('/edit/{user}', [\App\Http\Controllers\Users\AuthController::class, 'edit'])->name('form.edit');
Route::put('/edit/{user}', [\App\Http\Controllers\Users\AuthController::class, 'update'])->name('update');

Route::patch('/disable/{user}', [\App\Http\Controllers\Users\AuthController::class, 'disable'])->name('disable');
