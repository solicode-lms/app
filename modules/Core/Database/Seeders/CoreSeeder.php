<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CoreSeeder extends Seeder
{

    public function loadAndRun(string $moduleSeederDir, string $moduleNamespace): void
    {
        // Extraire le nom du module à partir de l'espace de noms
        $parts = explode('\\', $moduleNamespace);
        $moduleName = $parts[1]; // Exemple : "PkgBlog" extrait de "Modules\PkgBlog\Database\Seeders"
    
        // Déterminer le fichier principal du seeder du module
        $moduleSeederFile = $moduleName . 'Seeder.php'; // Exemple : "PkgBlogSeeder.php"
    
        // Charger et filtrer les fichiers seeder
        $seeders = collect(File::files($moduleSeederDir))
            ->filter(fn($file) => $file->getFilename() !== $moduleSeederFile) // Exclure le fichier seeder principal
            ->map(fn($file) => $moduleNamespace . '\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME)) // Construire le chemin complet des classes
            ->filter(fn($class) => class_exists($class)) // Vérifier que la classe existe
            ->map(fn($class) => [
                'class' => $class, 
                'order' => $class::$order ?? PHP_INT_MAX // Extraire l'ordre si défini, sinon valeur par défaut
            ])
            ->sortBy('order') // Trier les seeders par ordre
            ->pluck('class') // Récupérer uniquement les noms de classes triés
            ->toArray(); // Convertir en tableau
    
        // Exécuter les seeders dans l'ordre spécifié
        $this->call($seeders);
    }
    


    // public function load_and_run($seeders_dir,$namesapce_dir): void
    // {

    //     $module_name = str_split($namesapce_dir,"/")[1];
    //     dd($module_name);
    //     $module_seeder_file_name =  $module_name . "Seeder.php" ;

    //     // Charger dynamiquement tous les seeders dans le répertoire
    //     // sauf bien sur le fichier de module
    //     $seeders = collect(File::files($seeders_dir))->map(function ($file)  use ($namesapce_dir,$module_seeder_file_name) {
    //         $fileName = $file->getFilename();
    //         $class = $namesapce_dir . '\\' . pathinfo($fileName, PATHINFO_FILENAME);
            
    //         if($fileName ==  $module_seeder_file_name ) {return null;}

    //         return class_exists($class) ? $class : null;
    //     })->filter();

    //     // Exécuter tous les seeders trouvés
    //     $this->call($seeders->toArray());
    // }
}