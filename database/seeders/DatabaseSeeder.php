<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->run_modules_seeders();
    }

    public function run_modules_seeders():void{

        // TODO: utilisation de l'odre depuis le fichier modules.json ou c'est mieu 
        // d'ajouter l'ordre dans le schema

        $this->command->info('Parcourir tous les modules et exécuter leurs seeders');

        
        
        // Récupérer tous les dossiers de modules
        $modulesPath = base_path('modules');
        $modules = File::directories($modulesPath);
         

        // Parcourir tous les modules et exécuter leurs seeders
        foreach ($modules as $module) {

            if(basename($module) == "Core") { continue;}
            
            // Trouver le seeder principal du module en recherchant le fichier de seeder
            $seederFile = $module . '/Database/Seeders/' . Str::studly(basename($module)) . 'Seeder.php';

            // Vérifier si le fichier de seeder existe
            if (File::exists($seederFile)) {

                // Appeler le seeder du module
                
                $seederClass = 'Modules\\' . Str::studly(basename($module)) . '\\Database\\Seeders\\' . Str::studly(basename($module)) . 'Seeder';
                $this->command->info('Appeler le seeder :' . $seederClass);
                $this->call($seederClass);
            }
        }


    }
}
