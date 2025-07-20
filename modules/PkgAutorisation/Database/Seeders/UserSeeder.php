<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Database\Seeders\SysModuleSeeder;
use Modules\PkgAutorisation\Database\Seeders\Base\BaseUserSeeder;
use Modules\PkgAutorisation\Models\User;

class UserSeeder extends BaseUserSeeder
{

    // changement de fichier csv et insertion des rôles au utilisateurs
    public function seedFromCsv(): void {

        // Puisque c'est le premier seeder faut charger les donner nécessaire pour l'execution de UserSeeder
        // Insertion des rôles 
        $roleSeeder = new RoleSeeder();
        $roleSeeder->seedFromCsv();
        // Insertion des Moudues
        $sysModuleSeeder =  new SysModuleSeeder();
        $sysModuleSeeder->seedFromCsv();


        $csvFile = fopen(base_path("modules/PkgAutorisation/Database/data/users.csv"), "r");
        $firstline = true;

        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                User::create([
                    "name" => $data[0] ,
                    "email" => $data[1] ,
                    "password" => $data[2]
                ])->assignRole($data[3]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
