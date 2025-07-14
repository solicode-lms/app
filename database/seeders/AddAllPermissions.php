<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use ReflectionMethod;


// php artisan db:seed --class=Database\Seeders\AddAllPermissions
class AddAllPermissions extends Seeder
{
    /**
     * Nom de la méthode spécifique à exécuter dans les seeders.
     */
    private string $methodToExecute = 'addDefaultControllerDomainFeatures'; // Modifier ici si nécessaire

    /**
     * Exécuter les seeders des modules en appelant uniquement la méthode spécifique.
     */
    public function run()
    {
        $baseModulePath = base_path('modules'); // Adapter au chemin des modules
        $modules = File::directories($baseModulePath);

        $allSeeders = collect();

        // Chemin vers le fichier de configuration JSON.
        $configFilePath = $baseModulePath . '/modules-config.json';
        $config = json_decode(file_get_contents($configFilePath), true);

        foreach ($modules as $modulePath) {
            $moduleNamespace = $this->getModuleNamespace($modulePath);
            $moduleName = basename($modulePath);

            if (isset($config[$moduleName]['active']) && !$config[$moduleName]['active']) {
                echo "\033[33mModule désactivé : {$moduleName}\033[0m\n";
                continue;
            }

            // Déterminer le dossier des seeders du module
            $moduleSeederDir = $modulePath . '/Database/Seeders';
            if (!File::exists($moduleSeederDir)) {
                continue;
            }

            // Charger et collecter les seeders avec leur ordre
            $moduleSeeders = collect(File::files($moduleSeederDir))
                ->map(fn($file) => $moduleNamespace . '\\Database\\Seeders\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME))
                ->filter(fn($class) => class_exists($class))
                ->map(fn($class) => [
                    'class' => $class,
                    'order' => $class::$order ?? PHP_INT_MAX
                ]);

            $allSeeders = $allSeeders->concat($moduleSeeders);
        }

        // Trier les seeders par ordre d'exécution
        $sortedSeeders = $allSeeders
            ->sortBy('order')
            ->pluck('class')
            ->toArray();

        // Exécuter uniquement la méthode spécifiée pour chaque seeder
        foreach ($sortedSeeders as $seederClass) {
            $this->executeSpecificMethod($seederClass);
        }
    }

    /**
     * Exécuter une méthode spécifique d'un seeder donné.
     *
     * @param string $seederClass
     */
    private function executeSpecificMethod(string $seederClass)
    {
        $seederInstance = new $seederClass();

        if (method_exists($seederInstance, $this->methodToExecute)) {
            echo "\033[32m[INFO] Exécution de {$this->methodToExecute} dans {$seederClass}\033[0m\n";
            $seederInstance->{$this->methodToExecute}();
        } else {
            echo "\033[31m[WARNING] Méthode {$this->methodToExecute} introuvable dans {$seederClass}\033[0m\n";
        }
    }

    /**
     * Obtenir le namespace d'un module à partir de son chemin.
     *
     * @param string $modulePath
     * @return string
     */
    protected function getModuleNamespace(string $modulePath): string
    {
        $moduleName = basename($modulePath);
        return "Modules\\$moduleName";
    }
}
