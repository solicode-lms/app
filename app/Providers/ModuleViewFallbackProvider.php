<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ModuleViewFallbackProvider extends ServiceProvider
{
    /**
     * Liste des vues déjà traitées.
     */
    private $processedViews = [];

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Intercepter les vues pour les namespaces correspondant à "Pkg*::*" et "Core::*"
        View::composer(['Pkg*::*', 'Core::*'], function ($view) {
            $originalView = $view->getName(); // Exemple : "PkgUtilisateur::ville.index"

            // Éviter de retraiter la même vue
            if (in_array($originalView, $this->processedViews)) {
                return;
            }

            // Marquer la vue comme traitée
            $this->processedViews[] = $originalView;

            // Construire le chemin du fallback
            $segments = explode('.', $originalView); // Découpe "PkgUtilisateur::ville.index" en segments
            $namespaceAndFirstSegment = explode('::', $segments[0]); // Découpe "PkgUtilisateur::ville"

            if (count($namespaceAndFirstSegment) === 2) {
                $namespace = $namespaceAndFirstSegment[0]; // "PkgUtilisateur"
                $firstSegment = $namespaceAndFirstSegment[1]; // "ville"

                // Construire le chemin avec "custom" ajouté après le premier segment
                $fallbackView = $namespace . '::' . $firstSegment . '.custom.' . implode('.', array_slice($segments, 1));
                
                // Exemple : "PkgUtilisateur::ville.custom.index"
            } else {
                $fallbackView = $originalView; // Pas de modification si le format est incorrect
            }

            // Vérifier si une vue personnalisée existe
            if (view()->exists($fallbackView)) {
                // Utiliser la vue personnalisée
                $view->setPath(view($fallbackView)->getPath());
            }
        });
    }
}
