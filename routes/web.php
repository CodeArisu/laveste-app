<?php

use Illuminate\Support\Facades\Route;

// 'src.admin.adproduct_blades.productadd'
// 'src.admin.dashboard'
// src.admin.adproduct_blades.infoprod

Route::get('/', function () {
    return view('src.admin.users');
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

Route::get('/index', function () {
    return view('src.index');
});


Route::prefix('admin')->group(function () {
    Route::view('/dashboard', 'src.admin.dashboard');
    Route::view('/adproduct', 'src.admin.adproduct');  // <-- is this your products page?
    Route::view('/garment', 'src.admin.garment');
    Route::view('/transactions', 'src.admin.transactions');
    Route::view('/users', 'src.admin.users');
    Route::view('/prodrented', 'src.admin.prodrented');

    // Add this route if your products page is here:
    Route::view('/products', 'src.admin.products');
});

Route::get('/admin/adproduct_blades/productadd', function () {
    return view('src.admin.adproduct_blades.productadd');
});

Route::get('/admin/adproduct_blades/infoprod', function () {
    return view('src.admin.adproduct_blades.infoprod');
});

Route::get('/admin/adproduct_blades/editprod', function () {
    return view('src.admin.adproduct_blades.editprod');
});

Route::get('/admin/garment', function () {
    return view('src.admin.garment');
});

Route::get('/register', function () {
    return view('src.register');
});






