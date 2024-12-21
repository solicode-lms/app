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

        Schema::disableForeignKeyConstraints();
        Competence::truncate();
        Schema::enableForeignKeyConstraints();

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

        $permissions = [];
        foreach ($actions as $action) {
             $permissions[] =Permission::create(['name' => $action . '-CompetenceController', 'guard_name' => 'web']);
        }

        // Créer les permissions parents
        $manage = Permission::create([
            'name' => 'manage-CompetenceController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $readOnly = Permission::create([
            'name' => 'readOnly-CompetenceController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $importExport = Permission::create([
            'name' => 'importExport-CompetenceController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);


        // Associer les permissions enfants aux parents
        $manage->children()->sync(array_column($permissions, 'id')); // Toutes les permissions
        $readOnly->children()->sync([
            Permission::where('name', 'index-CompetenceController')->first()->id,
            Permission::where('name', 'show-CompetenceController')->first()->id,
        ]);
        $importExport->children()->sync([
            Permission::where('name', 'export-CompetenceController')->first()->id,
            Permission::where('name', 'import-CompetenceController')->first()->id,
        ]);


        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'manage-CompetenceController',
            'importExport-CompetenceController',
            'readOnly-CompetenceController',
        ]);

        $membre->givePermissionTo([
            'readOnly-CompetenceController',
        ]);
    }
}
