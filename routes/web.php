<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('dashboard.home');
});

Route::get('/login', [\App\Http\Controllers\Users\AuthController::class, 'loginIndex'])->name('form.login'); // login form page
Route::post('/login', [\App\Http\Controllers\Users\AuthController::class, 'loginUser'])->name('login');

Route::get('/register', [\App\Http\Controllers\Users\AuthController::class, 'registerIndex'])->name('form.register'); // register form page
Route::post('/register', [\App\Http\Controllers\Users\AuthController::class, 'registerUser'])->name('register');


Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Users\AuthController::class, 'logoutUser'])->name('logout');

    Route::get('/test', function () {
    return 'Redirect successful!';
    })->name('test');

    Route::get('/redirect', function () {
        return redirect()->route('test');
    });


    // Route::get('/edit/{user}', [\App\Http\Controllers\Users\AuthController::class, 'edit'])->name('form.edit');
    // Route::put('/edit/{user}', [\App\Http\Controllers\Users\AuthController::class, 'update'])->name('update');

    // Route::patch('/disable/{user}', [\App\Http\Controllers\Users\AuthController::class, 'disable'])
    // ->name('disable');

    // cashier routes
    Route::middleware(['role:admin,manager,accountant'])->name('cashier.')->prefix('cashier')->group(function () {
        require __DIR__ . '/cashier.php';

        Route::middleware(['role:admin,manager,accountant'])->name('appointment.')->prefix('appointment')->group(function () {
            require __DIR__ . '/appointments.php';
        });
    });

    // dashboard routes
    Route::name('dashboard.')->prefix('dashboard')->group(function () {
        Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('home');

        Route::post('/code', [\App\Http\Controllers\DashboardController::class, 'generate'])->name('code');
        Route::post('/code/register', [\App\Http\Controllers\DashboardController::class, 'register'])->name('code.register');

        Route::get('/users', [App\Http\Controllers\Users\AuthController::class, 'index'])->name('users')->middleware(['role:admin']);

        // dashboard products routes
        Route::middleware(['role:admin'])->name('product.')->prefix('product')->group(function () {
            require __DIR__ . '/products.php';
        });

        // dashboard garment routes
        Route::middleware(['role:admin,manager'])->name('garment.')->prefix('garment')->group(function () {
            require __DIR__ . '/garments.php';
        });

        Route::get('/transactions', [App\Http\Controllers\DashboardController::class, 'transactions'])->name('transactions');
        Route::get('/rented', [App\Http\Controllers\DashboardController::class, 'rented'])->name('rented');
    });
});

Route::get('/admin/users/edituser', function () {
    return view('src.admin.users.edituser');
});
