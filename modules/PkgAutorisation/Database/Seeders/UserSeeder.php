<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Database\Seeders\Base\BaseUserSeeder;
use Modules\PkgAutorisation\Models\User;

class UserSeeder extends BaseUserSeeder
{

    // changement de fichier csv et insertion des rôles au utilisateurs
    public function seedFromCsv(): void {

        // Insertion des rôles 
        $roleSeeder = new RoleSeeder();
        $roleSeeder->run();


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
