<?php
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
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias para roles
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'cors.custom' => \App\Http\Middleware\Cors::class,
        ]);
        
        // Opcional: registrar CORS como global
        $middleware->append(\App\Http\Middleware\Cors::class);
        
        // ✅ AGREGA ESTO - Deshabilita redirección para APIs
        $middleware->redirectGuestsTo(function ($request) {
            // Si la petición espera JSON (API), no redirigir
            if ($request->expectsJson() || $request->is('api/*')) {
                return null;
            }
            // Solo redirigir si hay una ruta 'login' definida
            return null; // O return route('login') si tienes esa ruta
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();