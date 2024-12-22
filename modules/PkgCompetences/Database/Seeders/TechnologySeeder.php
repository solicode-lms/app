<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

use Modules\PkgCompetences\Models\Technology;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class TechnologySeeder extends Seeder
{
    public static int $order = 25;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // La suppression des donnes déclenche le suppression en cascade
        // Schema::disableForeignKeyConstraints();
        // Technology::truncate();
        // Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgCompetences/Database/data/technologies.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Technology::create([
                    "nom" => $data[0] ,
                    "description" => $data[1] ,
                    "categorie_technologie_id" => $data[2] 
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
            'getTechnologies'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-TechnologyController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-TechnologyController',
            'show-TechnologyController',
            'create-TechnologyController',
            'store-TechnologyController',
            'edit-TechnologyController',
            'update-TechnologyController',
            'destroy-TechnologyController',
            'export-TechnologyController',
            'import-TechnologyController',
            'getTechnologies-TechnologyController',
        ]);

        $membre->givePermissionTo([
            'index-TechnologyController',
            'show-TechnologyController'
        ]);
    }
}
