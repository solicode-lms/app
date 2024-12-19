<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgUtilisateurs\Models\Niveaux_scolaire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class Niveaux_scolaireSeeder extends Seeder
{
    public static int $order = 4;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Niveaux_scolaire::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgUtilisateurs/Database/data/niveaux_scolaires.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Niveaux_scolaire::create([
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
            'getNiveaux_scolaires'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-Niveaux_scolaireController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-Niveaux_scolaireController',
            'show-Niveaux_scolaireController',
            'create-Niveaux_scolaireController',
            'store-Niveaux_scolaireController',
            'edit-Niveaux_scolaireController',
            'update-Niveaux_scolaireController',
            'destroy-Niveaux_scolaireController',
            'export-Niveaux_scolaireController',
            'import-Niveaux_scolaireController',
            'getNiveaux_scolaires-Niveaux_scolaireController',
        ]);

        $membre->givePermissionTo([
            'index-Niveaux_scolaireController',
            'show-Niveaux_scolaireController'
        ]);
    }
}
