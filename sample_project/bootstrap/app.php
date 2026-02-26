<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'integration' => \App\Http\Middleware\VerifyIntegrationKey::class,
            'session.auth' => \App\Http\Middleware\EnsureSessionAuthenticated::class,
            'role' => \App\Http\Middleware\EnsureRole::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/api/perf-metrics',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\CacheControl::class,
            \App\Http\Middleware\CompressResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
