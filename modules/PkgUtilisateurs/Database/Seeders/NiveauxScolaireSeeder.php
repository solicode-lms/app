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

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-NiveauxScolaireController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-NiveauxScolaireController',
            'show-NiveauxScolaireController',
            'create-NiveauxScolaireController',
            'store-NiveauxScolaireController',
            'edit-NiveauxScolaireController',
            'update-NiveauxScolaireController',
            'destroy-NiveauxScolaireController',
            'export-NiveauxScolaireController',
            'import-NiveauxScolaireController',
            'getNiveauxScolaires-NiveauxScolaireController',
        ]);

        $membre->givePermissionTo([
            'index-NiveauxScolaireController',
            'show-NiveauxScolaireController'
        ]);
    }
}
