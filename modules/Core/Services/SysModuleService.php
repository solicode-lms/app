<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseSysModuleService;

/**
 * Classe SysModuleService pour gérer la persistance de l'entité SysModule.
 */
class SysModuleService extends BaseSysModuleService
{
    public function dataCalcul($sysModule)
    {
        // En Cas d'édit
        if(isset($sysModule->id)){
          
        }
      
        return $sysModule;
    }
   
}
