<?php


namespace Modules\PkgBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

// TODO essayer de mettre database\seeders\UserSeeder.php dans ce fichier
class UserSeeder extends Seeder
{
    public static int $order = 2;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // Schema::disableForeignKeyConstraints();
        // User::truncate();
        // Schema::enableForeignKeyConstraints();

        // $csvFile = fopen(base_path("modules/PkgBlog/Database/data/users.csv"), "r");
        // $firstline = true;
        // while (($data = fgetcsv($csvFile)) !== false) {
        //     if (!$firstline) {
        //         User::create([
        //             "name" => $data[0] ,
        //             "email" => $data[1] ,
        //             "password" => $data[2] 
        //         ]);
        //     }
        //     $firstline = false;
        // }
        // fclose($csvFile);

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
        $membre = Role::where('name', $MembreRole)->first();

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
