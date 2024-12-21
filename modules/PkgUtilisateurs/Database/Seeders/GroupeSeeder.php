<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgUtilisateurs\Models\Groupe;
use Spatie\Permission\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;

class GroupeSeeder extends Seeder
{
    public static int $order = 1;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Groupe::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgUtilisateurs/Database/data/groupes.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Groupe::create([
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
            'getGroupes'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-GroupeController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-GroupeController',
            'show-GroupeController',
            'create-GroupeController',
            'store-GroupeController',
            'edit-GroupeController',
            'update-GroupeController',
            'destroy-GroupeController',
            'export-GroupeController',
            'import-GroupeController',
            'getGroupes-GroupeController',
        ]);

        $membre->givePermissionTo([
            'index-GroupeController',
            'show-GroupeController'
        ]);
    }
}
