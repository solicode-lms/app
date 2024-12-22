<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Exécuter les seeders de tous les modules ensemble, triés par ordre.
     *
     * @return void
     */
    public function run()
    {
        // Chemin de base où se trouvent les modules
        $baseModulePath = base_path('Modules'); // Adapter au chemin des modules
        $modules = File::directories($baseModulePath);

        $allSeeders = collect();

        foreach ($modules as $modulePath) {
            // Récupérer le namespace du module
            $moduleNamespace = $this->getModuleNamespace($modulePath);

            // Déterminer le dossier des seeders pour ce module
            $moduleSeederDir = $modulePath . '/Database/Seeders';
            if (!File::exists($moduleSeederDir)) {
                continue; // Passer si aucun dossier de seeders
            }

            // Charger et collecter les seeders avec leur ordre
            $moduleSeeders = collect(File::files($moduleSeederDir))
                ->map(fn($file) => $moduleNamespace . '\\Database\\Seeders\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME)) // Construire le chemin complet des classes
                ->filter(fn($class) => class_exists($class)) // Vérifier que la classe existe
                ->map(fn($class) => [
                    'class' => $class,
                    'order' => $class::$order ?? PHP_INT_MAX // Extraire l'ordre ou valeur par défaut
                ]);

            $allSeeders = $allSeeders->concat($moduleSeeders); // Ajouter les seeders du module
        }

        // Trier tous les seeders ensemble par ordre
        $sortedSeeders = $allSeeders
            ->sortBy('order') // Trier par ordre
            ->pluck('class') // Récupérer uniquement les noms de classes triés
            ->toArray(); // Convertir en tableau

        // dd( $sortedSeeders);
        // Exécuter les seeders triés
        $this->call($sortedSeeders);
    }

    /**
     * Obtenir le namespace d'un module à partir de son chemin.
     *
     * @param string $modulePath
     * @return string
     */
    protected function getModuleNamespace(string $modulePath): string
    {
        // Adapter cette méthode à votre structure de module
        $moduleName = basename($modulePath);
        return "Modules\\$moduleName";
    }
}
