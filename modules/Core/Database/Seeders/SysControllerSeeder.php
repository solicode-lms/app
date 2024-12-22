<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\SysController;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class SysControllerSeeder extends Seeder
{
    public static int $order = 2;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // La suppression des donnes déclenche le suppression en cascade
        // Schema::disableForeignKeyConstraints();
        // SysController::truncate();
        // Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/Core/Database/data/sysControllers.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                SysController::create([
                    "module_id" => $data[0] ,
                    "name" => $data[1] ,
                    "slug" => $data[2] ,
                    "description" => $data[3] ,
                    "is_active" => $data[4] 
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
            'getSysControllers'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-SysControllerController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-SysControllerController',
            'show-SysControllerController',
            'create-SysControllerController',
            'store-SysControllerController',
            'edit-SysControllerController',
            'update-SysControllerController',
            'destroy-SysControllerController',
            'export-SysControllerController',
            'import-SysControllerController',
            'getSysControllers-SysControllerController',
        ]);

        $membre->givePermissionTo([
            'index-SysControllerController',
            'show-SysControllerController'
        ]);
    }
}
