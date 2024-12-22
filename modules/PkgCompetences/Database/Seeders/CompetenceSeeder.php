<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

use Modules\PkgCompetences\Models\Competence;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class CompetenceSeeder extends Seeder
{
    public static int $order = 18;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // La suppression des donnes déclenche le suppression en cascade
        // Schema::disableForeignKeyConstraints();
        // Competence::truncate();
        // Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgCompetences/Database/data/competences.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Competence::create([
                    "code" => $data[0] ,
                    "nom" => $data[1] ,
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
            'getCompetences'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-CompetenceController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-CompetenceController',
            'show-CompetenceController',
            'create-CompetenceController',
            'store-CompetenceController',
            'edit-CompetenceController',
            'update-CompetenceController',
            'destroy-CompetenceController',
            'export-CompetenceController',
            'import-CompetenceController',
            'getCompetences-CompetenceController',
        ]);

        $membre->givePermissionTo([
            'index-CompetenceController',
            'show-CompetenceController'
        ]);
    }
}
