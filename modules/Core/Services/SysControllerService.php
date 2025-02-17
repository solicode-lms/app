<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseSysControllerService;

/**
 * Classe SysControllerService pour gérer la persistance de l'entité SysController.
 */
class SysControllerService extends BaseSysControllerService
{
    public function dataCalcul($sysController)
    {
        // En Cas d'édit
        if(isset($sysController->id)){
          
        }
      
        return $sysController;
    }
   
}
