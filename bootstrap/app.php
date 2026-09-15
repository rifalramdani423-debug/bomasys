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
    ->withMiddleware(function (Middleware $middleware): void {
        
        // Middleware bawaan milikmu
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Pengecualian CSRF Token untuk Webhook Midtrans
        $middleware->validateCsrfTokens(except: [
            '/api/midtrans/webhook',
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();