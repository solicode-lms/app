<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistrement des services nécessaires à l'application.
     */
    public function register(): void
    {
        // Charger dynamiquement tous les ServiceProviders des modules.
        $this->loadModuleServiceProviders();
        
        // Active ou désactive la prévention du "lazy loading" Eloquent 
        // en fonction de la variable d’environnement MODEL_PREVENT_LAZY_LOADING.
        // Cela permet de forcer le développeur à utiliser "with()" pour éviter les requêtes N+1.

        // dd(app()->isProduction());
        // Model::preventLazyLoading(! app()->isProduction());
        Model::preventLazyLoading(filter_var(env('MODEL_PREVENT_LAZY_LOADING', false), FILTER_VALIDATE_BOOLEAN));
    }

    /**
     * Démarrage de divers services de l'application.
     */
    public function boot(): void
    {
        // dd("AppServiceProvider");
        // Configuration de la pagination pour utiliser le style Bootstrap.
        Paginator::useBootstrap();

        //  Directive Blade Personnalisée
        Blade::directive('limit', function ($expression) {
            // Crée une directive : @limit($string, $length)
            return "<?php echo \Illuminate\Support\Str::limit($expression); ?>";
        });

        Blade::directive('accessiblePermissions', function ($expression) {
            return "<?php \$accessiblePermissions = collect($expression)->filter(fn(\$permission) => auth()->user()->can(\$permission)); ?>";
        });

    }

    /**
     * Charger dynamiquement les ServiceProviders depuis les modules.
     *
     * @return void
     */
    protected function loadModuleServiceProviders()
    {
        // Chemin vers le dossier contenant les modules.
        $moduleProvidersPath = base_path('modules');

        // Chemin vers le fichier de configuration JSON.
        $configFilePath = $moduleProvidersPath . '/modules-config.json';
        $config = json_decode(file_get_contents($configFilePath), true);

        // Récupérer tous les fichiers correspondant à un ServiceProvider dans les modules.
        $providerFiles = glob($moduleProvidersPath . '/*/App/Providers/*ServiceProvider.php');

        foreach ($providerFiles as $providerFile) {

             // Récupérer le nom du dossier du module à partir du chemin du fichier.
            $moduleName = basename(dirname(dirname(dirname($providerFile))));

            // Si on ne charge pas un module : il ne vas pas charger les route 
            // ce qui générer une exception lors de l'affichage de la page admin
            // Vérifier si le module est désactivé dans la configuration.
            // if (isset($config[$moduleName]['active']) && !$config[$moduleName]['active']) {
            //     Log::info("Module désactivé : {$moduleName}");
            //     echo "[Module désactivé : {$moduleName}\n]"; // Texte en jaune
            //     continue;
            // }

            // Récupérer le nom complet de la classe du ServiceProvider.
            $providerClass = $this->getProviderClass($providerFile);

            // Vérifier si la classe existe avant de l'enregistrer.
            // Vérifier si la classe existe avant de l'enregistrer.
            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
                // Log::info("ServiceProvider chargé : {$providerClass}");
            } else {
                Log::error("ServiceProvider non trouvé : {$providerClass}");
            }
        }
    }

    /**
     * Récupérer la classe du ServiceProvider à partir du fichier PHP.
     *
     * @param string $file
     * @return string
     */
    protected function getProviderClass(string $file): string
    {
        // Obtenir le chemin relatif à partir de base_path()
        $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
    
        // Normaliser les séparateurs de chemins en '\\' pour les namespaces
        $relativePath = str_replace(['/', '\\'], '\\', $relativePath);
    
        // Supprimer l'extension .php
        $relativePath = str_replace('.php', '', $relativePath);
    
        // Remplacer "modules" par "Modules" uniquement au début du chemin
        if (str_starts_with($relativePath, 'modules\\')) {
            $relativePath = 'Modules' . substr($relativePath, 7);
        }
    
        return $relativePath;
    }
    
}
