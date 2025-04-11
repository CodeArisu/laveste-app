<?php

use App\Http\Resources\GarmentResource;
use App\Http\Resources\ProductResource;
use App\Models\Garment;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('src.landing');
});