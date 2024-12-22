<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Database\Seeders;


use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class RoleSeeder extends Seeder
{
    public static int $order = 2;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // La suppression des donnes déclenche le suppression en cascade
        // Schema::disableForeignKeyConstraints();
        // Role::truncate();
        // Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgAutorisation/Database/data/roles.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Role::create([
                    "name" => $data[0] ,
                    "guard_name" => $data[1] 
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);

        $actions = [
            'index',
            'show',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
            'export',
            'import',
            'getRoles'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-RoleController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-RoleController',
            'show-RoleController',
            'create-RoleController',
            'store-RoleController',
            'edit-RoleController',
            'update-RoleController',
            'destroy-RoleController',
            'export-RoleController',
            'import-RoleController',
            'getRoles-RoleController',
        ]);

        $membre->givePermissionTo([
            'index-RoleController',
            'show-RoleController'
        ]);
    }
}
