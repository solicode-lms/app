<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;

class AddRoleToGetDataPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Récupérer toutes les permissions "index-*"
        $indexPermissions = Permission::where('name', 'LIKE', 'index-%')->get();

        foreach ($indexPermissions as $indexPermission) {
            // Trouver la permission correspondante "getData-*" en remplaçant "index-" par "getData-"
            $getDataPermissionName = str_replace('index-', 'getData-', $indexPermission->name);
            $getDataPermission = Permission::where('name', $getDataPermissionName)->first();

            if ($getDataPermission) {
                // Récupérer les rôles associés à "index-*"
                $roles = $indexPermission->roles;

                if ($roles->isNotEmpty()) {
                    // Associer les mêmes rôles à "getData-*"
                    $getDataPermission->roles()->syncWithoutDetaching($roles->pluck('id'));
                    
                    echo "\033[32m[INFO] Permission mise à jour : {$getDataPermission->name}\033[0m\n";
                }
            } else {
                echo "\033[33m[WARNING] Permission introuvable : {$getDataPermissionName}\033[0m\n";
            }
        }
    }
}
