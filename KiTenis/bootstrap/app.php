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
        // Para usar Filament, você NÃO precisa do alias "admin".
        // Se no futuro quiser rotas admin próprias, crie um middleware real e registre aqui.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
