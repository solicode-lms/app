<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Providers\Base;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BasePkgApprentissageServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__ . '/../../../resources/views', 'PkgApprentissage');

        // Charger les fichiers de traduction
        $this->loadTranslationsFrom(
            __DIR__ . '/../../../resources/lang',
            'PkgApprentissage'
        );

        $this->registerObservers();
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

    protected function registerObservers()
    {
        $observerPath = __DIR__ . '/../../../Observers';
        $namespace = 'Modules\\PkgApprentissage\\Observers\\';
        $modelNamespace = 'Modules\\PkgApprentissage\\Models\\';

        if (!is_dir($observerPath)) {
            return;
        }

        foreach (glob($observerPath . '/*Observer.php') as $file) {
            $fileName = basename($file, '.php'); // ex: RealisationTacheObserver
            $modelName = Str::replaceLast('Observer', '', $fileName); // RealisationTache

            $observerClass = $namespace . $fileName;
            $modelClass = $modelNamespace . $modelName;

            if (class_exists($modelClass) && class_exists($observerClass)) {
                $modelClass::observe($observerClass);
            }
        }
    }
}
