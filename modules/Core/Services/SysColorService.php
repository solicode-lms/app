<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseSysColorService;

/**
 * Classe SysColorService pour gérer la persistance de l'entité SysColor.
 */
class SysColorService extends BaseSysColorService
{
    public function dataCalcul($sysColor)
    {
        // En Cas d'édit
        if(isset($sysColor->id)){
          
        }
      
        return $sysColor;
    }
   
}
