<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

use Modules\PkgCompetences\Models\Module;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class ModuleSeeder extends Seeder
{
    public static int $order = 16;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Module::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgCompetences/Database/data/modules.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Module::create([
                    "nom" => $data[0] ,
                    "description" => $data[1] ,
                    "masse_horaire" => $data[2] ,
                    "filiere_id" => $data[3] 
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
            'getModules'
        ];

        $permissions = [];
        foreach ($actions as $action) {
             $permissions[] =Permission::create(['name' => $action . '-ModuleController', 'guard_name' => 'web']);
        }

        // Créer les permissions parents
        $manage = Permission::create([
            'name' => 'manage-ModuleController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $readOnly = Permission::create([
            'name' => 'readOnly-ModuleController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);

        $importExport = Permission::create([
            'name' => 'importExport-ModuleController',
            'module' => 'PkgCompetences',
            'type' => 'feature',
            'guard_name' => 'web'
        ]);


        // Associer les permissions enfants aux parents
        $manage->children()->sync(array_column($permissions, 'id')); // Toutes les permissions
        $readOnly->children()->sync([
            Permission::where('name', 'index-ModuleController')->first()->id,
            Permission::where('name', 'show-ModuleController')->first()->id,
        ]);
        $importExport->children()->sync([
            Permission::where('name', 'export-ModuleController')->first()->id,
            Permission::where('name', 'import-ModuleController')->first()->id,
        ]);


        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'manage-ModuleController',
            'importExport-ModuleController',
            'readOnly-ModuleController',
        ]);

        $membre->givePermissionTo([
            'readOnly-ModuleController',
        ]);
    }
}
