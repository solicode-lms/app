<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Database\Seeders;

App\Models\Specialite
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class SpecialiteSeeder extends Seeder
{
    public static int $order = 11;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Specialite::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgUtilisateurs/Database/data/specialites.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Specialite::create([
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
            'getSpecialites'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-SpecialiteController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-SpecialiteController',
            'show-SpecialiteController',
            'create-SpecialiteController',
            'store-SpecialiteController',
            'edit-SpecialiteController',
            'update-SpecialiteController',
            'destroy-SpecialiteController',
            'export-SpecialiteController',
            'import-SpecialiteController',
            'getSpecialites-SpecialiteController',
        ]);

        $membre->givePermissionTo([
            'index-SpecialiteController',
            'show-SpecialiteController'
        ]);
    }
}
