<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\GarmentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Products\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard.home');
});

Route::get('/register', [\App\Http\Controllers\Auth\AuthController::class, 'registerIndex'])->name('form.register'); // register form page
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'registerUser'])->name('register');

Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'loginIndex'])->name('form.login'); // login form page
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'loginUser'])->name('login');

Route::middleware(['auth', 'web'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logoutUser'])->name('logout');

    Route::middleware(['role:admin,manager,accountant'])->name('cashier.')->prefix('cashier')->group( function () {
        Route::get('/home', function () {
            return view('src.cashier.home');
        })->name('home');

        Route::get('/catalog', [CatalogController::class, 'index'])->name('index'); 

        Route::get('/transaction', function () {
            return view('src.cashier.transaction');
        })->name('transaction');
        
    });

    Route::name('dashboard.')->prefix('dashboard')->group( function () {
        Route::get('/', function () {
            $product = Product::all();
            return view('src.admin.dashboard', ['productCount' => count($product)]);
        })->name('home');
    
        Route::middleware(['role:admin'])->name('product.')->prefix('product')->group( function () {
            Route::get('/', [\App\Http\Controllers\Api\ProductController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Api\ProductController::class, 'create'])->name('form');
    
            Route::post('/create', [\App\Http\Controllers\Api\ProductController::class, 'store'])->name('store');
            Route::get('/{product}', [\App\Http\Controllers\Api\ProductController::class, 'show'])->name('show');
    
            Route::get('/{product}/edit', [\App\Http\Controllers\Api\ProductController::class, 'edit'])->name('edit');
            Route::put('/{product}/edit', [\App\Http\Controllers\Api\ProductController::class, 'update'])->name('update');
    
            Route::delete('/{product}/r', [\App\Http\Controllers\Api\ProductController::class, 'destroy'])->name('delete');
            // Route::resource('products', ProductController::class)->except(['create', 'edit', 'destroy', 'update']);
        });

        Route::middleware(['role:admin'])->name('garment.')->prefix('garment')->group( function () {
            // this routes to the product info through add to garment
            Route::get('/', [\App\Http\Controllers\Api\GarmentController::class, 'index'])->name('index');
            
            Route::post('/{products}/create', [\App\Http\Controllers\Api\GarmentController::class, 'store'])->name('store');
    
            Route::put('/{garment}', [\App\Http\Controllers\Api\GarmentController::class, 'update'])->name('update');
            Route::delete('/{garment}/r', [\App\Http\Controllers\Api\GarmentController::class, 'destroy'])->name('delete');
        
            // Route::resource('garments', GarmentController::class)->except(['create', 'edit', 'destroy', 'update']);
        });
    });

});

Route::get('/cashier/catalog/checkout', function () {
    return view('src.cashier.checkout');
})->name('cashier.checkout');

Route::get('/cashier/appointment/checkout', function () {
    return view('src.cashier.checkout2');
})->name('appointment.checkout');





// Route::post('/catalog/{$garment}', [App\Http\Controllers\Api\CatalogController::class, 'store'])->name('catalog.store');


Route::get('/dashboard/rented', function () {
    return view('src.admin.prodrented');
})->name('rented');
Route::get('/dashboard/transactions', function () {
    return view('src.admin.transactions');
})->name('transactions');
Route::get('/dashboard/users', function () {
    return view('src.admin.users');
})->name('users');