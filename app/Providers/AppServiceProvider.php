<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
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
        
        // Transformer le chemin de fichier en nom de classe PHP avec namespace
        $relativePath = str_replace(base_path(), '', $file); // Obtenir le chemin relatif
       
        $relativePath = str_replace('/', '\\', $relativePath); // Convertir les / en \
        $relativePath = trim($relativePath, '\\'); // Supprimer les \ en trop
        $relativePath = str_replace('.php', '', $relativePath); 
        // Remplacer uniquement "module" par "Module" au début du chemin
       
        if (substr($relativePath, 0, 7) === 'modules') {
             $relativePath = 'Modules' . substr($relativePath, 7);
        }
    
        // Exemple : Modules\PkgArticles\App\Providers\PkgArticlesServiceProvider
        return  $relativePath;
    }
}
