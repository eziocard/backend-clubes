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
    ->withMiddleware(function ($middleware): void {
    // Alias para roles
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'cors.custom' => \App\Http\Middleware\Cors::class,
    ]);

    // Opcional: registrar CORS como global
    $middleware->append(\App\Http\Middleware\Cors::class);
})



    ->withExceptions(function ($exceptions): void {
        //
    })
    ->create();
