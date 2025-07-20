<?php

namespace Modules\PkgAutorisation\Database\Seeders;

use Modules\PkgAutorisation\Database\Seeders\Base\BaseRoleSeeder;
use Modules\PkgAutorisation\Models\Role;

class RoleSeeder extends BaseRoleSeeder
{

     public function run(): void
    {
        // il est exécuter par UserSeeder : le premier seeder 
        // $this->seedFromCsv();

        // Ajouter le contrôleur, le domaine, les fonctionnalités et leurs permissions
        $this->addDefaultControllerDomainFeatures();

    }

}
