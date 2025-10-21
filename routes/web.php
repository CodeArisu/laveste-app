<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\AuthController;


Route::get('/', function () {
    return redirect()->route('dashboard.home');
});

Route::get('/login', [AuthController::class, 'loginIndex'])->name('form.login'); // login form page
Route::post('/login', [AuthController::class, 'loginUser'])->name('login');
Route::middleware(['auth'])->group(function () {
    Route::get('/register', [AuthController::class, 'registerIndex'])->name('form.register'); // register form page
    Route::post('/register', [AuthController::class, 'registerUser'])->name('register');
    Route::post('/logout', [AuthController::class, 'logoutUser'])->name('logout');

    // users routes
    Route::middleware(['role:admin,manager'])->name('user.')->prefix('user')->group(function () {
        require __DIR__.'/users.php';
    });

    // cashier routes
    Route::middleware(['role:admin,manager,accountant'])->name('cashier.')->prefix('cashier')->group(function () {
        require __DIR__ . '/cashier.php';
        // appointment routes
        Route::middleware(['role:admin,manager,accountant'])->name('appointment.')->prefix('appointment')->group(function () {
            require __DIR__ . '/appointments.php';
        });
    });

    // dashboard routes
    Route::name('dashboard.')->prefix('dashboard')->group(function () {
        require __DIR__.'/dashboard.php';
    });

});

// Route::get('/admin/users/edituser', function () {
//     return view('src.admin.users.edituser');
// });
