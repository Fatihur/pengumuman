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
        // Global middleware
        $middleware->append([
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\InputSanitization::class,
        ]);

        // Route-specific middleware aliases
        $middleware->alias([
            'admin.access' => \App\Http\Middleware\AdminAccessControl::class,
            'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
            'login.logger' => \App\Http\Middleware\LoginAttemptLogger::class,
        ]);

        // Web middleware group additions
        $middleware->web(append: [
            \App\Http\Middleware\LoginAttemptLogger::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
