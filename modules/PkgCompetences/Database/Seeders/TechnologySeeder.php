<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgCompetences\Models\Technology;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class TechnologySeeder extends Seeder
{
    public static int $order = 10;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        Schema::disableForeignKeyConstraints();
        Technology::truncate();
        Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/PkgCompetences/Database/data/technologies.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                Technology::create([
                    "nom" => $data[0] ,
                    "description" => $data[1] ,
                    "categorie_technologie_id" => $data[2] 
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
            'getTechnologies'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-TechnologyController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-TechnologyController',
            'show-TechnologyController',
            'create-TechnologyController',
            'store-TechnologyController',
            'edit-TechnologyController',
            'update-TechnologyController',
            'destroy-TechnologyController',
            'export-TechnologyController',
            'import-TechnologyController',
            'getTechnologies-TechnologyController',
        ]);

        $membre->givePermissionTo([
            'index-TechnologyController',
            'show-TechnologyController'
        ]);
    }
}
