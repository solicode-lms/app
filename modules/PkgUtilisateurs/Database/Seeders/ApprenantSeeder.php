<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgUtilisateurs\Models\Apprenant;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class ApprenantSeeder extends Seeder
{
    public static int $order = 4;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Apprenant::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgUtilisateurs/Database/data/apprenants.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Apprenant::create([
                    "nom" => $data[0] ,
                    "prenom" => $data[1] ,
                    "prenom_arab" => $data[2] ,
                    "nom_arab" => $data[3] ,
                    "tele_num" => $data[4] ,
                    "profile_image" => $data[5] ,
                    "date_inscription" => $data[6] ,
                    "ville_id" => $data[7] ,
                    "niveaux_scolaires_id" => $data[8] ,
                    "groupe_id" => $data[9] 
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
            'getApprenants'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-ApprenantController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-ApprenantController',
            'show-ApprenantController',
            'create-ApprenantController',
            'store-ApprenantController',
            'edit-ApprenantController',
            'update-ApprenantController',
            'destroy-ApprenantController',
            'export-ApprenantController',
            'import-ApprenantController',
            'getApprenants-ApprenantController',
        ]);

        $membre->givePermissionTo([
            'index-ApprenantController',
            'show-ApprenantController'
        ]);
    }
}
