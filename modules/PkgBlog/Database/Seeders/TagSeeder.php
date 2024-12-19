<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgBlog\Models\Tag;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class TagSeeder extends Seeder
{
    public static int $order = 4;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Tag::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgBlog/Database/data/tags.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Tag::create([
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
            'getTags'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-TagController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-TagController',
            'show-TagController',
            'create-TagController',
            'store-TagController',
            'edit-TagController',
            'update-TagController',
            'destroy-TagController',
            'export-TagController',
            'import-TagController',
            'getTags-TagController',
        ]);

        $membre->givePermissionTo([
            'index-TagController',
            'show-TagController'
        ]);
    }
}
