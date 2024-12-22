<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

use Modules\PkgCompetences\Models\NiveauCompetence;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class NiveauCompetenceSeeder extends Seeder
{
    public static int $order = 24;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // La suppression des donnes déclenche le suppression en cascade
        // Schema::disableForeignKeyConstraints();
        // NiveauCompetence::truncate();
        // Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgCompetences/Database/data/niveauCompetences.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                NiveauCompetence::create([
                    "nom" => $data[0] ,
                    "description" => $data[1] ,
                    "competence_id" => $data[2] 
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
            'getNiveauCompetences'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-NiveauCompetenceController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-NiveauCompetenceController',
            'show-NiveauCompetenceController',
            'create-NiveauCompetenceController',
            'store-NiveauCompetenceController',
            'edit-NiveauCompetenceController',
            'update-NiveauCompetenceController',
            'destroy-NiveauCompetenceController',
            'export-NiveauCompetenceController',
            'import-NiveauCompetenceController',
            'getNiveauCompetences-NiveauCompetenceController',
        ]);

        $membre->givePermissionTo([
            'index-NiveauCompetenceController',
            'show-NiveauCompetenceController'
        ]);
    }
}
