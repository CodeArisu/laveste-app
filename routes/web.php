<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\GarmentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// 'src.admin.adproduct_blades.productadd'
// 'src.admin.dashboard'
// src.admin.adproduct_blades.infoprod

Route::get('/', function () {
    return view('src.admin.transactions');
});


Route::get('/register', [\App\Http\Controllers\Auth\AuthController::class, 'registerIndex'])->name('form.register'); // register form page
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'registerUser'])->name('register');

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



Route::name('product.')->prefix('product')->group( function () {
    Route::post('/create', [\App\Http\Controllers\Api\ProductController::class, 'store'])->name('store');
    Route::put('/{product}', [\App\Http\Controllers\Api\ProductController::class, 'update'])->name('update');
    Route::delete('/{product}/r', [\App\Http\Controllers\Api\ProductController::class, 'destroy'])->name('delete');

    Route::resource('products', ProductController::class)->except(['create', 'edit', 'destroy', 'update']);
});

Route::name('garment.')->prefix('garment')->group( function () {
    Route::post('/create', [\App\Http\Controllers\Api\GarmentController::class, 'store'])->name('store');
    Route::put('/{garment}', [\App\Http\Controllers\Api\GarmentController::class, 'update'])->name('update');
    Route::delete('/{garment}/r', [\App\Http\Controllers\Api\GarmentController::class, 'destroy'])->name('delete');

    Route::resource('garments', GarmentController::class)->except(['create', 'edit', 'destroy', 'update']);
});

Route::post('/catalog/{$garment}', [App\Http\Controllers\Api\CatalogController::class, 'store'])->name('catalog.store');




// PAGE ROUTING





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


Route::get('/admin/user_blades/register', function () {
    return view('src.admin.user_blades.register');
});

Route::get('/admin/users', function () {
    return view('src.admin.users');
});

Route::get('/admin/user_blades/edituser', function () {
    return view('src.admin.user_blades.edituser');
});

Route::get('/admin/prodrented', function () {
    return view('src.admin.prodrented');
});

















