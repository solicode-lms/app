<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

use Modules\PkgCompetences\Models\CategorieTechnology;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class CategorieTechnologySeeder extends Seeder
{
    public static int $order = 17;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        CategorieTechnology::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgCompetences/Database/data/categorieTechnologies.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                CategorieTechnology::create([
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
            'getCategorieTechnologies'
        ];

        $permissions = [];
        foreach ($actions as $action) {
             $permissions[] =Permission::create(['name' => $action . '-CategorieTechnologyController', 'guard_name' => 'web']);
        }

        // Créer les permissions parents
        $manage = Permission::create([
            'name' => 'manage-CategorieTechnologyController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $readOnly = Permission::create([
            'name' => 'readOnly-CategorieTechnologyController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $importExport = Permission::create([
            'name' => 'importExport-CategorieTechnologyController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);


        // Associer les permissions enfants aux parents
        $manage->children()->sync(array_column($permissions, 'id')); // Toutes les permissions
        $readOnly->children()->sync([
            Permission::where('name', 'index-CategorieTechnologyController')->first()->id,
            Permission::where('name', 'show-CategorieTechnologyController')->first()->id,
        ]);
        $importExport->children()->sync([
            Permission::where('name', 'export-CategorieTechnologyController')->first()->id,
            Permission::where('name', 'import-CategorieTechnologyController')->first()->id,
        ]);


        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'manage-CategorieTechnologyController',
            'importExport-CategorieTechnologyController',
            'readOnly-CategorieTechnologyController',
        ]);

        $membre->givePermissionTo([
            'readOnly-CategorieTechnologyController',
        ]);
    }
}
