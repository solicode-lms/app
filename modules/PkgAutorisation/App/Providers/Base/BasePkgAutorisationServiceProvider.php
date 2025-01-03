<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\App\Providers\Base;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class BasePkgAutorisationServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(__DIR__ . '/../../../Database/Migrations');

        // Charger les fichiers de routes du module
        $routeFiles = File::allFiles(__DIR__ . '/../../../Routes');
        foreach ($routeFiles as $routeFile) {
            $this->loadRouteFile($routeFile);
        }

        // Charger les vues du module
        $this->loadViewsFrom(__DIR__ . '/../../../resources/views', 'PkgAutorisation');

        // Charger les fichiers de traduction
        $this->loadTranslationsFrom(
            __DIR__ . '/../../../resources/lang',
            'PkgAutorisation'
        );
    }

    /**
     * Charger un fichier de routes.
     *
     * @param \SplFileInfo $file
     */
    protected function loadRouteFile($file)
    {
        $filePath = $file->getPathname();
        $middleware = $this->getMiddleware($filePath);

        Route::middleware($middleware)->group(function () use ($filePath) {
            require $filePath;
        });
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
