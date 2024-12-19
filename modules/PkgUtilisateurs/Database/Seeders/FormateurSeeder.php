<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgUtilisateurs\Models\Formateur;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class FormateurSeeder extends Seeder
{
    public static int $order = 5;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Formateur::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgUtilisateurs/Database/data/formateurs.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Formateur::create([
                    "nom" => $data[0] ,
                    "prenom" => $data[1] ,
                    "prenom_arab" => $data[2] ,
                    "nom_arab" => $data[3] ,
                    "tele_num" => $data[4] ,
                    "profile_image" => $data[5] 
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
            'getFormateurs'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-FormateurController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-FormateurController',
            'show-FormateurController',
            'create-FormateurController',
            'store-FormateurController',
            'edit-FormateurController',
            'update-FormateurController',
            'destroy-FormateurController',
            'export-FormateurController',
            'import-FormateurController',
            'getFormateurs-FormateurController',
        ]);

        $membre->givePermissionTo([
            'index-FormateurController',
            'show-FormateurController'
        ]);
    }
}
