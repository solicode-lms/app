<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgBlog\Models\Category;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    public static int $order = 1;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgBlog/Database/data/categories.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Category::create([
                    "name" => $data[0] ,
                    "slug" => $data[1] 
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
            'getCategories'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-CategoryController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-CategoryController',
            'show-CategoryController',
            'create-CategoryController',
            'store-CategoryController',
            'edit-CategoryController',
            'update-CategoryController',
            'destroy-CategoryController',
            'export-CategoryController',
            'import-CategoryController',
            'getCategories-CategoryController',
        ]);

        $membre->givePermissionTo([
            'index-CategoryController',
            'show-CategoryController'
        ]);
    }
}
