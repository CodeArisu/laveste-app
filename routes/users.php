<?php

use Illuminate\Support\Facades\Route;

// User detail routes
// this api should add user details for a specific user
Route::post('/add-details/{user}', [\App\Http\Controllers\Users\UserController::class, 'store'])->name('add.details');

Route::get('/edit/{user}', [\App\Http\Controllers\Users\UserController::class, 'edit'])->name('form.edit');
Route::put('/edit/{user}', [\App\Http\Controllers\Users\UserController::class, 'update'])->name('update');

Route::patch('/disable/{user}', [\App\Http\Controllers\Users\UserController::class, 'disable'])->name('disable');
