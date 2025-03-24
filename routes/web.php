<?php

use App\Http\Controllers\api\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('index');
});

Route::get('/test', [ProductController::class, 'index'])->name('view.product');