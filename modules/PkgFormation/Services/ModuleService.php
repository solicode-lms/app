<?php


namespace Modules\PkgFormation\Services;
use Modules\PkgFormation\Services\Base\BaseModuleService;

/**
 * Classe ModuleService pour gérer la persistance de l'entité Module.
 */
class ModuleService extends BaseModuleService
{
    protected array $index_with_relations = ['filiere','competences'];

    public function dataCalcul($module)
    {
        // En Cas d'édit
        if(isset($module->id)){
          
        }
      
        return $module;
    }
   
}
