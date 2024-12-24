<?php

namespace Modules\Core\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Enregistrer les services dans l'application.
     *
     * @return void
     */
    public function register()
    {
        // Vous pouvez enregistrer les services spécifiques au module ici.
    }

    /**
     * Effectuer les opérations de démarrage pour le module.
     *
     * @return void
     */
    public function boot()
    {
        // Charger les migrations
        $migrationsPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }

        // Charger les fichiers de routes du module
        $routesPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Routes';
        if (is_dir($routesPath)) {
            $routeFiles = File::allFiles($routesPath);
            foreach ($routeFiles as $routeFile) {
                $this->loadRouteFile($routeFile);
            }
        }

        // Charger les vues du module
        $viewsPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'Core');
        }

        // Charger les fichiers de traduction
        $langPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'lang';
        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'Core');
        }
    }

    /**
     * Charger un fichier de routes.
     *
     * @param \SplFileInfo $file
     * @return void
     */
    protected function loadRouteFile($file)
    {
        $filePath = $file->getPathname();
        $middleware = $this->getMiddleware($filePath);

        try {
            Route::middleware($middleware)->group(function () use ($filePath) {
                require $filePath;
            });
        } catch (\Throwable $e) {
            Log::error("Erreur lors du chargement du fichier de routes : {$filePath}. Message : {$e->getMessage()}");
        }
    }

    /**
     * Obtenir les middlewares en fonction du fichier de routes.
     *
     * @param string $filePath
     * @return array
     */
    protected function getMiddleware($filePath)
    {
        // Ajouter une logique pour déterminer les middlewares si nécessaire.
        return ['web'];
    }
}
