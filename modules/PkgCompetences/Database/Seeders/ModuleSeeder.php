<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgCompetences\Models\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class ModuleSeeder extends Seeder
{
    public static int $order = 8;

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

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-ModuleController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-ModuleController',
            'show-ModuleController',
            'create-ModuleController',
            'store-ModuleController',
            'edit-ModuleController',
            'update-ModuleController',
            'destroy-ModuleController',
            'export-ModuleController',
            'import-ModuleController',
            'getModules-ModuleController',
        ]);

        $membre->givePermissionTo([
            'index-ModuleController',
            'show-ModuleController'
        ]);
    }
}
