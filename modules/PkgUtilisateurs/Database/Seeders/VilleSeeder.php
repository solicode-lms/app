<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Database\Seeders;

App\Models\Ville
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class VilleSeeder extends Seeder
{
    public static int $order = 7;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Ville::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgUtilisateurs/Database/data/villes.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Ville::create([
                    "nom" => $data[0] 
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
            'getVilles'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-VilleController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-VilleController',
            'show-VilleController',
            'create-VilleController',
            'store-VilleController',
            'edit-VilleController',
            'update-VilleController',
            'destroy-VilleController',
            'export-VilleController',
            'import-VilleController',
            'getVilles-VilleController',
        ]);

        $membre->givePermissionTo([
            'index-VilleController',
            'show-VilleController'
        ]);
    }
}
