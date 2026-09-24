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
        $middleware->validateCsrfTokens(except: [
            'mandamientos',
            'mandamientos/*',
            'personas',
            'personas/*',
            'registro-criminal',
            'registro-criminal/*',
        ]);

        // Agregar middleware para verificar si el usuario está activo
        $middleware->web(\App\Http\Middleware\EnsureUserIsActive::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Por defecto Laravel no registra el 413 (archivo demasiado grande); se necesita para diagnosticar subidas.
        $exceptions->stopIgnoring(\Illuminate\Http\Exceptions\PostTooLargeException::class);

        // Toda excepción registrada incluye quién y desde dónde ocurrió.
        $exceptions->context(fn () => [
            'usuario_id' => auth()->id(),
            'url' => request()->fullUrl(),
            'metodo' => request()->method(),
            'ip' => request()->ip(),
        ]);
    })->create();
