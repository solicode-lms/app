<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Services;
use Modules\PkgFormation\Services\Base\BaseModuleService;

/**
 * Classe ModuleService pour gérer la persistance de l'entité Module.
 */
class ModuleService extends BaseModuleService
{
    public function dataCalcul($module)
    {
        // En Cas d'édit
        if(isset($module->id)){
          
        }
      
        return $module;
    }
   
}
