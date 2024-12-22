<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\FeatureDomain;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class FeatureDomainSeeder extends Seeder
{
    public static int $order = 3;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // La suppression des donnes déclenche le suppression en cascade
        // Schema::disableForeignKeyConstraints();
        // FeatureDomain::truncate();
        // Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/Core/Database/data/featureDomains.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                FeatureDomain::create([
                    "name" => $data[0] ,
                    "slug" => $data[1] ,
                    "description" => $data[2] ,
                    "module_id" => $data[3] 
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
            'getFeatureDomains'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-FeatureDomainController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-FeatureDomainController',
            'show-FeatureDomainController',
            'create-FeatureDomainController',
            'store-FeatureDomainController',
            'edit-FeatureDomainController',
            'update-FeatureDomainController',
            'destroy-FeatureDomainController',
            'export-FeatureDomainController',
            'import-FeatureDomainController',
            'getFeatureDomains-FeatureDomainController',
        ]);

        $membre->givePermissionTo([
            'index-FeatureDomainController',
            'show-FeatureDomainController'
        ]);
    }
}
