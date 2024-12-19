<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = new Application(
    dirname(__DIR__)
);


// Charger dynamiquement un fichier `.env.<ENV>` seulement si APP_ENV est défini
if (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] !== 'local') {
    echo "APP_ENV = Production";
    $app->loadEnvironmentFrom('.env.' . $_SERVER['APP_ENV']);

}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Ajouter vos middlewares ici
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Configurer vos gestionnaires d'exceptions ici
    })
    ->create();
