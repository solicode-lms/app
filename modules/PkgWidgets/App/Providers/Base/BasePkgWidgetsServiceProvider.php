<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Providers\Base;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BasePkgWidgetsServiceProvider extends ServiceProvider
{
    /**
     * Enregistrer les services dans l'application.
     *
     * @return void
     */
    public function register()
    {
        // 📌 Parcours automatique du dossier Services du package
        $servicesPath = __DIR__ . '/../../../Services';
        $namespace = 'Modules\\PkgWidgets\\Services\\';

        if (is_dir($servicesPath)) {
            $files = collect(File::files($servicesPath));
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $fullClass = $namespace . $className;

                    if (class_exists($fullClass)) {
                        // Enregistrement en singleton
                        $this->app->singleton($fullClass, function ($app) use ($fullClass) {
                            return new $fullClass();
                        });
                    }
                }
            }
        }
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
        $routeFiles = collect(File::allFiles(__DIR__ .  '/../../../Routes'))
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
        $this->loadViewsFrom(__DIR__ . '/../../../resources/views', 'PkgWidgets');

        // Charger les fichiers de traduction
        $this->loadTranslationsFrom(
            __DIR__ . '/../../../resources/lang',
            'PkgWidgets'
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
        $namespace = 'Modules\\PkgWidgets\\Observers\\';
        $modelNamespace = 'Modules\\PkgWidgets\\Models\\';

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
