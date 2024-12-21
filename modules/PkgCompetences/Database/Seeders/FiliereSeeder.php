<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

use Modules\PkgCompetences\Models\Filiere;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class FiliereSeeder extends Seeder
{
    public static int $order = 15;

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

        $permissions = [];
        foreach ($actions as $action) {
             $permissions[] =Permission::create(['name' => $action . '-FiliereController', 'guard_name' => 'web']);
        }

        // Créer les permissions parents
        $manage = Permission::create([
            'name' => 'manage-FiliereController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $readOnly = Permission::create([
            'name' => 'readOnly-FiliereController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $importExport = Permission::create([
            'name' => 'importExport-FiliereController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);


        // Associer les permissions enfants aux parents
        $manage->children()->sync(array_column($permissions, 'id')); // Toutes les permissions
        $readOnly->children()->sync([
            Permission::where('name', 'index-FiliereController')->first()->id,
            Permission::where('name', 'show-FiliereController')->first()->id,
        ]);
        $importExport->children()->sync([
            Permission::where('name', 'export-FiliereController')->first()->id,
            Permission::where('name', 'import-FiliereController')->first()->id,
        ]);


        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'manage-FiliereController',
            'importExport-FiliereController',
            'readOnly-FiliereController',
        ]);

        $membre->givePermissionTo([
            'readOnly-FiliereController',
        ]);
    }
}
