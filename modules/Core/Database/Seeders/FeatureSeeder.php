<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\Feature;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class FeatureSeeder extends Seeder
{
    public static int $order = 25;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // La suppression des donnes déclenche le suppression en cascade
        // Schema::disableForeignKeyConstraints();
        // Feature::truncate();
        // Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/Core/Database/data/features.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Feature::create([
                    "name" => $data[0] ,
                    "description" => $data[1] ,
                    "domain_id" => $data[2] 
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
            'getFeatures'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-FeatureController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-FeatureController',
            'show-FeatureController',
            'create-FeatureController',
            'store-FeatureController',
            'edit-FeatureController',
            'update-FeatureController',
            'destroy-FeatureController',
            'export-FeatureController',
            'import-FeatureController',
            'getFeatures-FeatureController',
        ]);

        $membre->givePermissionTo([
            'index-FeatureController',
            'show-FeatureController'
        ]);
    }
}
