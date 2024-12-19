<?php


namespace Modules\PkgAuthentification\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;


// app/database\seeders\RoleSeeder.php
class RoleSeeder extends Seeder
{
    public static int $order = 1;

    public function run(): void
    {
      
        $AdminRole = "admin";
        $FormateurRole = "formateur";

        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgAuthentification/Database/data/roles.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Role::create([
                    "name" => $data[0]
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
        $membre = Role::where('name', $FormateurRole)->first();

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
