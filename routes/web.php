<?php

use App\Http\Controllers\api\ProductController;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('src.landing');
});

Route::get('/test', [ProductController::class, 'index'])->name('view.product');