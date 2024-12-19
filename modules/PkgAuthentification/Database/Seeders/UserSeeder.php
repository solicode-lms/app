<?php
// USer dans : use App\Models\User;


namespace Modules\PkgAuthentification\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public static int $order = 2;

    public function run(): void
    {
        $AdminRole = "admin";
        $FormateurRole = "formateur";
        
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgAuthentification/Database/data/users.csv"), "r");
        $firstline = true;

        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                User::create([
                    "name" => $data[0] ,
                    "email" => $data[1] ,
                    "password" => $data[2]
                ])->assignRole($data[3]);;
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
            'getUsers'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-UserController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $FormateurRole)->first();

        $admin->givePermissionTo([
            'index-UserController',
            'show-UserController',
            'create-UserController',
            'store-UserController',
            'edit-UserController',
            'update-UserController',
            'destroy-UserController',
            'export-UserController',
            'import-UserController',
            'getUsers-UserController',
        ]);

        $membre->givePermissionTo([
            'index-UserController',
            'show-UserController'
        ]);
    }
}
