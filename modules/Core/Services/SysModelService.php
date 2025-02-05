<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseSysModelService;

/**
 * Classe SysModelService pour gérer la persistance de l'entité SysModel.
 */
class SysModelService extends BaseSysModelService
{
    public function dataCalcul($sysModel)
    {
        // En Cas d'édit
        if(isset($sysModel->id)){
          
        }
      
        return $sysModel;
    }
}
