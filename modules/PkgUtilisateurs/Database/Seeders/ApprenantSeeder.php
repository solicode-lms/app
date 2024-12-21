<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Database\Seeders;

use Modules\PkgUtilisateurs\Models\Apprenant;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class ApprenantSeeder extends Seeder
{
    public static int $order = 9;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Apprenant::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgUtilisateurs/Database/data/apprenants.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Apprenant::create([
                    "nom" => $data[0] ,
                    "prenom" => $data[1] ,
                    "prenom_arab" => $data[2] ,
                    "nom_arab" => $data[3] ,
                    "tele_num" => $data[4] ,
                    "profile_image" => $data[5] ,
                    "groupe_id" => $data[6] ,
                    "niveaux_scolaires_id" => $data[7] ,
                    "ville_id" => $data[8] 
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
            'getApprenants'
        ];

        $permissions = [];
        foreach ($actions as $action) {
             $permissions[] =Permission::create(['name' => $action . '-ApprenantController', 'guard_name' => 'web']);
        }

        // Créer les permissions parents
        $manage = Permission::create([
            'name' => 'manage-ApprenantController',
            'module' => 'PkgUtilisateurs',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $readOnly = Permission::create([
            'name' => 'readOnly-ApprenantController',
            'module' => 'PkgUtilisateurs',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $importExport = Permission::create([
            'name' => 'importExport-ApprenantController',
            'module' => 'PkgUtilisateurs',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);


        // Associer les permissions enfants aux parents
        $manage->children()->sync(array_column($permissions, 'id')); // Toutes les permissions
        $readOnly->children()->sync([
            Permission::where('name', 'index-ApprenantController')->first()->id,
            Permission::where('name', 'show-ApprenantController')->first()->id,
        ]);
        $importExport->children()->sync([
            Permission::where('name', 'export-ApprenantController')->first()->id,
            Permission::where('name', 'import-ApprenantController')->first()->id,
        ]);


        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'manage-ApprenantController',
            'importExport-ApprenantController',
            'readOnly-ApprenantController',
        ]);

        $membre->givePermissionTo([
            'readOnly-ApprenantController',
        ]);
    }
}
