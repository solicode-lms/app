<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Database\Seeders;

use Modules\PkgUtilisateurs\Models\NiveauxScolaire;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class NiveauxScolaireSeeder extends Seeder
{
    public static int $order = 6;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        NiveauxScolaire::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgUtilisateurs/Database/data/niveauxScolaires.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                NiveauxScolaire::create([
                    "nom" => $data[0] ,
                    "description" => $data[1] 
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
            'getNiveauxScolaires'
        ];

        $permissions = [];
        foreach ($actions as $action) {
             $permissions[] =Permission::create(['name' => $action . '-NiveauxScolaireController', 'guard_name' => 'web']);
        }

        // Créer les permissions parents
        $manage = Permission::create([
            'name' => 'manage-NiveauxScolaireController',
            'module' => 'PkgUtilisateurs',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $readOnly = Permission::create([
            'name' => 'readOnly-NiveauxScolaireController',
            'module' => 'PkgUtilisateurs',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $importExport = Permission::create([
            'name' => 'importExport-NiveauxScolaireController',
            'module' => 'PkgUtilisateurs',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);


        // Associer les permissions enfants aux parents
        $manage->children()->sync(array_column($permissions, 'id')); // Toutes les permissions
        $readOnly->children()->sync([
            Permission::where('name', 'index-NiveauxScolaireController')->first()->id,
            Permission::where('name', 'show-NiveauxScolaireController')->first()->id,
        ]);
        $importExport->children()->sync([
            Permission::where('name', 'export-NiveauxScolaireController')->first()->id,
            Permission::where('name', 'import-NiveauxScolaireController')->first()->id,
        ]);


        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'manage-NiveauxScolaireController',
            'importExport-NiveauxScolaireController',
            'readOnly-NiveauxScolaireController',
        ]);

        $membre->givePermissionTo([
            'readOnly-NiveauxScolaireController',
        ]);
    }
}
