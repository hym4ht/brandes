<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            // Middleware autentikasi umum (cek login + optional role)
            'auth.brandes' => AuthMiddleware::class,

            // Middleware role khusus admin
            'role.admin'   => AdminMiddleware::class,

            // Middleware role khusus user biasa
            'role.user'    => UserMiddleware::class,
        ]);

        // Kecualikan route API dari CSRF protection ditiadakan (IoT Detached)
        $middleware->validateCsrfTokens(except: [
            //
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
