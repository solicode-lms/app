<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgCompetences\Models\Filiere;
use Spatie\Permission\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;

class FiliereSeeder extends Seeder
{
    public static int $order = 7;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Filiere::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgCompetences/Database/data/filieres.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Filiere::create([
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
            'getFilieres'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-FiliereController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-FiliereController',
            'show-FiliereController',
            'create-FiliereController',
            'store-FiliereController',
            'edit-FiliereController',
            'update-FiliereController',
            'destroy-FiliereController',
            'export-FiliereController',
            'import-FiliereController',
            'getFilieres-FiliereController',
        ]);

        $membre->givePermissionTo([
            'index-FiliereController',
            'show-FiliereController'
        ]);
    }
}