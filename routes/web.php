<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\GarmentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('src.cashier.home');
});

Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'loginIndex'])->name('form.login'); // login form page
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'loginUser'])->name('login');

Route::middleware(['auth', 'web'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logoutUser'])->name('logout');

    Route::name('cashier.')->prefix('cashier')->group( function () {
        Route::get('/home', function () {
            return view('src.cashier.home');
        })->name('home');

        Route::get('/catalog', function () {
            return view('src.cashier.product');
        })->name('catalog');

        Route::get('/transaction', function () {
            return view('src.cashier.transaction');
        })->name('transaction');
    });
});

// Route::name('garment.')->prefix('garment')->group( function () {
//     Route::post('/create', [\App\Http\Controllers\Api\GarmentController::class, 'store'])->name('store');
//     Route::put('/{garment}', [\App\Http\Controllers\Api\GarmentController::class, 'update'])->name('update');
//     Route::delete('/{garment}/r', [\App\Http\Controllers\Api\GarmentController::class, 'destroy'])->name('delete');

//     Route::resource('garments', GarmentController::class)->except(['create', 'edit', 'destroy', 'update']);
// });

// Route::post('/catalog/{$garment}', [App\Http\Controllers\Api\CatalogController::class, 'store'])->name('catalog.store');

Route::name('dashboard.')->prefix('dashboard')->group( function () {

    Route::get('/', function () {
        return view('src.admin.dashboard');
    })->name('home');

    Route::name('product.')->prefix('product')->group( function () {
        Route::get('/', [\App\Http\Controllers\Api\ProductController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Api\ProductController::class, 'create'])->name('form');

        Route::post('/create', [\App\Http\Controllers\Api\ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [\App\Http\Controllers\Api\ProductController::class, 'show'])->name('show');

        Route::get('/{product}/edit', [\App\Http\Controllers\Api\ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}/edit', [\App\Http\Controllers\Api\ProductController::class, 'update'])->name('update');

        Route::delete('/{product}/r', [\App\Http\Controllers\Api\ProductController::class, 'destroy'])->name('delete');
        // Route::resource('products', ProductController::class)->except(['create', 'edit', 'destroy', 'update']);
    });

});

// Route::get('/dashboard/garments', function () {
//     return view('src.admin.garment');
// });
// Route::get('/dashboard/rented', function () {
//     return view('src.admin.prodrented');
// });
// Route::get('/dashboard/transactions', function () {
//     return view('src.admin.transactions');
// });
// Route::get('/dashboard/users', function () {
//     return view('src.admin.users');
// });
// Route::get('/dashboard/product/edit', function () {
//     return view('src.admin.adproducts.editprod');
// });