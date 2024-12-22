<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\SysModule;
use Illuminate\Database\Seeder;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\Permission;

class SysModuleSeeder extends Seeder
{
    public static int $order = 1;

    public function run(): void
    {
        $AdminRole = User::ADMIN;
        $MembreRole = User::MEMBRE;

        // La suppression des donnes déclenche le suppression en cascade
        // Schema::disableForeignKeyConstraints();
        // SysModule::truncate();
        // Schema::enableForeignKeyConstraints();

        $csvFile = fopen(base_path("modules/Core/Database/data/sysModules.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile)) !== false) {
            if (!$firstline) {
                SysModule::create([
                    "name" => $data[0] ,
                    "slug" => $data[1] ,
                    "description" => $data[2] ,
                    "is_active" => $data[3] ,
                    "order" => $data[4] ,
                    "version" => $data[5] 
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
            'getSysModules'
        ];

        foreach ($actions as $action) {
            Permission::create(['name' => $action . '-SysModuleController', 'guard_name' => 'web']);
        }

        $admin = Role::where('name', $AdminRole)->first();
        $membre = Role::where('name', $MembreRole)->first();

        $admin->givePermissionTo([
            'index-SysModuleController',
            'show-SysModuleController',
            'create-SysModuleController',
            'store-SysModuleController',
            'edit-SysModuleController',
            'update-SysModuleController',
            'destroy-SysModuleController',
            'export-SysModuleController',
            'import-SysModuleController',
            'getSysModules-SysModuleController',
        ]);

        $membre->givePermissionTo([
            'index-SysModuleController',
            'show-SysModuleController'
        ]);
    }
}
