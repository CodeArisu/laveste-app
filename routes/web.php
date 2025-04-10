<?php

use App\Http\Resources\GarmentResource;
use App\Http\Resources\ProductResource;
use App\Models\Garment;
use App\Models\Product;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('src.landing');
});

Route::get('/test/{product}', [\App\Http\Controllers\Api\ProductController::class, 'show'])->name('test');
Route::get('/test/garment/{garment}', function ($garment) {
    $garment = Garment::with(['product', 'size', 'condition'])->find($garment);
    return new GarmentResource($garment);
});