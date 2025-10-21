<?php

use Illuminate\Support\Facades\Route;

Route::get('/details', [\App\Http\Controllers\AppointmentController::class, 'preindex'])->name('preindex');
Route::post('/details', [\App\Http\Controllers\AppointmentController::class, 'storeAppointmentSession'])->name('session');

Route::get('/details/process', [\App\Http\Controllers\AppointmentController::class, 'showProcess'])->name('process');
Route::post('/details/process', [\App\Http\Controllers\AppointmentController::class, 'storeSession'])->name('store');

Route::get('/details/process/checkout', [\App\Http\Controllers\AppointmentController::class, 'index'])->name('check');
Route::post('/details/process/checkout', [\App\Http\Controllers\AppointmentController::class, 'store'])->name('checkout');

Route::get('/details/process/{appointment}', [\App\Http\Controllers\AppointmentController::class, 'show'])->name('show');

Route::post('/{appointment}/completed', [\App\Http\Controllers\CashierController::class, 'appointmentCompleted'])->name('completed');
Route::post('/{appointment}/cancel', [App\Http\Controllers\CashierController::class, 'appointmentCancelled'])->name('cancelled');
