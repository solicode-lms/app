<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Permission;
use Spatie\Permission\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    public static int $order = 19;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgAutorisation/Database/data/permissions.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Permission::create([
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
            'getPermissions'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-PermissionController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-PermissionController',
            'show-PermissionController',
            'create-PermissionController',
            'store-PermissionController',
            'edit-PermissionController',
            'update-PermissionController',
            'destroy-PermissionController',
            'export-PermissionController',
            'import-PermissionController',
            'getPermissions-PermissionController',
        ]);

        $membre->givePermissionTo([
            'index-PermissionController',
            'show-PermissionController'
        ]);
    }
}