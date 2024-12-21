<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

App\Models\CategorieTechnology
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

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-CategorieTechnologyController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-CategorieTechnologyController',
            'show-CategorieTechnologyController',
            'create-CategorieTechnologyController',
            'store-CategorieTechnologyController',
            'edit-CategorieTechnologyController',
            'update-CategorieTechnologyController',
            'destroy-CategorieTechnologyController',
            'export-CategorieTechnologyController',
            'import-CategorieTechnologyController',
            'getCategorieTechnologies-CategorieTechnologyController',
        ]);

        $membre->givePermissionTo([
            'index-CategorieTechnologyController',
            'show-CategorieTechnologyController'
        ]);
    }
}
