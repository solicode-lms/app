<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class FallbackViewProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Hook dans le moteur de rendu des vues
        View::composer('*', function ($view) {
            $originalViewPath = $view->getName(); // Ex: "ville.index"

            // Vérifiez si une vue personnalisée dans "/ui/" existe
            $customViewPath = $this->generateCustomViewPath($originalViewPath);

            if (view()->exists($customViewPath)) {
                // Si la vue personnalisée existe, utilisez-la
                $view->setPath(view($customViewPath)->getPath());
            } elseif (!view()->exists($originalViewPath)) {
                // Si la vue originale n'existe pas, appliquez la logique de fallback
                $fallbackViewPath = $this->generateFallbackViewPath($originalViewPath);

                if (view()->exists($fallbackViewPath)) {
                    // Si une vue de fallback existe, utilisez-la
                    $view->setPath(view($fallbackViewPath)->getPath());
                }
            }
        });
    }

    /**
     * Génère un chemin pour une vue personnalisée basée dans "/ui/".
     * Exemple : "ville.index" => "ui.ville.index"
     */
    protected function generateCustomViewPath($originalViewPath)
    {
        return 'ui.' . $originalViewPath;
    }

    /**
     * Génère un chemin de fallback basé sur une convention.
     * Exemple : "ville.index" => "ville.base.index"
     */
    protected function generateFallbackViewPath($originalViewPath)
    {
        // Logique de fallback : ajoute "base" entre les segments du chemin
        $segments = explode('.', $originalViewPath);

        if (count($segments) > 1) {
            array_splice($segments, -1, 0, 'base'); // Ajoute "base" avant le dernier segment
        } else {
            // Si le chemin est simple, ajoute simplement "base"
            $segments[] = 'base';
        }

        return implode('.', $segments); // Ex: "ville.index" => "ville.base.index"
    }
}
