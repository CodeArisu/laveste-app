<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('src.landing');
});

Route::get('/test', function () {
    throw App\Exceptions\AuthException::userAlreadyRegistered();
}); 