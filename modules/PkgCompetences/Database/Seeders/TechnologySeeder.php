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
    public static int $order = 20;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Technology::truncate();
        Schema::enableForeignKeyConstraints();

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

        $permissions = [];
        foreach ($actions as $action) {
             $permissions[] =Permission::create(['name' => $action . '-TechnologyController', 'guard_name' => 'web']);
        }

        // Créer les permissions parents
        $manage = Permission::create([
            'name' => 'manage-TechnologyController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $readOnly = Permission::create([
            'name' => 'readOnly-TechnologyController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $importExport = Permission::create([
            'name' => 'importExport-TechnologyController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);


        // Associer les permissions enfants aux parents
        $manage->children()->sync(array_column($permissions, 'id')); // Toutes les permissions
        $readOnly->children()->sync([
            Permission::where('name', 'index-TechnologyController')->first()->id,
            Permission::where('name', 'show-TechnologyController')->first()->id,
        ]);
        $importExport->children()->sync([
            Permission::where('name', 'export-TechnologyController')->first()->id,
            Permission::where('name', 'import-TechnologyController')->first()->id,
        ]);


        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'manage-TechnologyController',
            'importExport-TechnologyController',
            'readOnly-TechnologyController',
        ]);

        $membre->givePermissionTo([
            'readOnly-TechnologyController',
        ]);
    }
}
