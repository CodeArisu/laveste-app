<?php

use App\Enum\StatusCode;
use App\Exceptions\InvalidUserException;
use App\Http\Requests\AuthRequest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render( function (InvalidUserException $e) {
            return response()->json([
                'status' => 'error',
                'code' => $e->getStatusCode(),
                'message' => $e->getMessage()
            ], $e->getCode());
        });
    })->create();
