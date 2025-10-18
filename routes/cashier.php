<?php

use Illuminate\Support\Facades\Route;

Route::get('/home', [\App\Http\Controllers\CashierController::class, 'rentalsIndex'])->name('home');

// updates product rented status
Route::put('/home/{ProductRent}', [\App\Http\Controllers\CashierController::class, 'productRentUpdate'])->name('rent-update');

Route::get('/catalog', [\App\Http\Controllers\CatalogController::class, 'index'])->name('index');

// pre checkout customers details API
Route::get('/catalog/{catalogs}/details', [\App\Http\Controllers\ProductRentController::class, 'index'])->name('details');
Route::post('/catalog/{catalogs}/details', [\App\Http\Controllers\ProductRentController::class, 'store'])->name('details.store');

// transaction checkout API
Route::get('/catalog/{catalogs}/checkout', [\App\Http\Controllers\TransactionController::class, 'index'])->name('checkout');
Route::post('/catalog/{catalogs}/checkout', [\App\Http\Controllers\TransactionController::class, 'store'])->name('checkout.store');

Route::post('/catalog/{catalogs}/checkout/verify_code', [\App\Http\Controllers\TransactionController::class, 'verifyCode'])->name('verify.code');

Route::get('/checkout/receipt/{transaction}', [\App\Http\Controllers\TransactionController::class, 'show'])->name('checkout.receipt');

// sets catalog to received
Route::post('/receive/{productRent}', [\App\Http\Controllers\CashierController::class, 'productRentUpdate'])->name('receive');
