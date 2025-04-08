<?php

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('src.landing');
});

// Route::get('/test/{product}', [\App\Http\Controllers\Api\ProductController::class, 'test'])->name('test');