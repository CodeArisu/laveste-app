<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('src.cashier.home');
});

Route::get('/cashier/home', function () {
    return view('src.cashier.home');
});

Route::get('/cashier/home', function () {
    return view('src.cashier.home');
})->name('cashier.home');

Route::get('/cashier/products', function () {
    return view('src.cashier.product');
});


Route::get('/cashier/transactions', function () {
    return view('src.cashier.transaction');
});

Route::get('/cashier/checkout2', function () {
    return view('src.cashier.checkout2');
});

Route::get('/cashier/checkout3', function () {
    return view('src.cashier.checkout3');
})->name('cashier.checkout3');


Route::get('/cashier/checkout', function () {
    return view('src.cashier.checkout');
});

Route::get('/cashier/receipt', function () {
    return view('src.cashier.receipt');
});

Route::get('/cashier/receipt2', function () {
    return view('src.cashier.receipt2');
});

Route::get('/cashier/scheduleAppointment', function () {
    return view('src.cashier.appointment');
});

