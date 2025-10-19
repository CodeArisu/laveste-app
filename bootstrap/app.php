<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            // your global web middleware
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            '/login',
            '/register'
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\UserPolicies::class,
            'active' => \App\Http\Middleware\CheckUserStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // $exceptions->render(function (App\Exceptions\InternalExceptions $e, $request) {
        // if ($request->expectsJson()) {
        //     return response()->json([
        //         'error' => $e->getMessage(),
        //         'description' => $e->getDescription(),
        //         'status' => $e->getStatusCode()->value,
        //     ], $e->getStatusCode()->getStatusCode());
        // }

        // return redirect()
        //     ->back()
        //     ->withInput()
        //     ->withErrors([
        //         'internal_error' => $e->getMessage(),
        //         'internal_error_description' => $e->getDescription(),
        //     ]);
        // });
    })->create();
