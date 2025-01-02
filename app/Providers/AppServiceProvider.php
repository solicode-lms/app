<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
    }

    /**
     * Démarrage de divers services de l'application.
     */
    public function boot(): void
    {
        dd("AppServiceProvider");
        // Configuration de la pagination pour utiliser le style Bootstrap.
        Paginator::useBootstrap();
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

        // Récupérer tous les fichiers correspondant à un ServiceProvider dans les modules.
        $providerFiles = glob($moduleProvidersPath . '/*/App/Providers/*ServiceProvider.php');

        foreach ($providerFiles as $providerFile) {
            // Récupérer le nom complet de la classe du ServiceProvider.
            $providerClass = $this->getProviderClass($providerFile);

            // Vérifier si la classe existe avant de l'enregistrer.
            // Vérifier si la classe existe avant de l'enregistrer.
            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
                Log::info("ServiceProvider chargé : {$providerClass}");
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
