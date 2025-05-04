<?php

use App\Http\Controllers\Api\CatalogController;
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
// Route::post('/catalog/{$garment}', [App\Http\Controllers\Api\CatalogController::class, 'store'])->name('catalog.store');

// pre checkout customers details API
// Route::get('/cashier/catalog/details', [\App\Http\Controllers\Api\Transactions\ProductRentController::class, 'index'])->name('cashier.details');
// Route::post('/cashier/catalog/details', [\App\Http\Controllers\Api\Transactions\ProductRentController::class, 'store'])->name('cashier.details.store');
// // Route::get('/cashier/catalog/details/show', [\App\Http\Controllers\Api\Transactions\ProductRentController::class, 'show'])->name('cashier.details.show');

// // transaction checkout API
// Route::get('/cashier/catalog/checkout', [App\Http\Controllers\Api\Transactions\TransactionController::class, 'index'])->name('cashier.checkout');
// Route::post('/cashier/catalog/checkout', [App\Http\Controllers\Api\Transactions\TransactionController::class, 'store'])->name('cashier.checkout.store');
// Route::get('/cashier/catalog/checkout/show', [App\Http\Controllers\Api\Transactions\TransactionController::class, 'show'])->name('cashier.checkout.show');

Route::get('/cashier/appointment/checkout', function () {
    return view('src.cashier.checkout3');
})->name('appointment.checkout');

Route::get('/cashier/catalog/receipt', function () {
    return view('src.cashier.receipt');
})->name('cashier.receipt');

Route::get('/dashboard/rented', function () {
    return view('src.admin.prodrented');
})->name('rented');
Route::get('/dashboard/transactions', function () {
    return view('src.admin.transactions');
})->name('transactions');
Route::get('/dashboard/users', function () {
    return view('src.admin.users');
})->name('users');