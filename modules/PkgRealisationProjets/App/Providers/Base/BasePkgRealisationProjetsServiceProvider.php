<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Providers\Base;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class BasePkgRealisationProjetsServiceProvider extends ServiceProvider
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
        
        $routeFiles = collect(File::allFiles(__DIR__ . '/../../../Routes'))
        ->sortBy(function ($file) {
            $name = $file->getFilename();
            return match (true) {
                str_contains($name, '.custom.') => 0,
                str_contains($name, '.api.')    => 1,
                default                       => 10,
            };
        });
        
        foreach ($routeFiles as $routeFile) {
            $this->loadRouteFile($routeFile);
        }

        

        // Charger les vues du module
        $this->loadViewsFrom(__DIR__ . '/../../../resources/views', 'PkgRealisationProjets');

        // Charger les fichiers de traduction
        $this->loadTranslationsFrom(
            __DIR__ . '/../../../resources/lang',
            'PkgRealisationProjets'
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
