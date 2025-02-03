<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Modules\PkgAutorisation\Database\Seeders\Base\BaseRoleSeeder;
use Modules\PkgAutorisation\Models\Role;

class RoleSeeder extends BaseRoleSeeder
{

    // Il doit s'executer une seul fois
    public function run(): void
    {
        // Vérifiez si le rôle avec l'ID 1 existe déjà
        $role = Role::find(1);
    
        if ($role === null) {
            parent::run();
        }
    }

}
