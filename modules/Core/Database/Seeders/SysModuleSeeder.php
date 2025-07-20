<?php
// TODO : Il doit être appeler une seul foix  $this->seedFromCsv(); est 
// Il est appele maintenant par RoleSeeder


namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Database\Seeders\Base\BaseSysModuleSeeder;
use Modules\Core\Models\Feature;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Models\SysController;
use Modules\Core\Models\SysModule;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Models\User;


class SysModuleSeeder extends BaseSysModuleSeeder
{
    public static int $order = 6;

    public function run(): void
    {
        // il est exécuter par RootSeeder : le premier seeder 
        // Ajouter les données à partir d'un fichier CSV
        // $this->seedFromCsv();

        // Ajouter le contrôleur, le domaine, les fonctionnalités et leurs permissions
        $this->addDefaultControllerDomainFeatures();

    }
}
