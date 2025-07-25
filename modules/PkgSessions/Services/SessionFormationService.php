<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgSessions\Services;
use Modules\PkgSessions\Services\Base\BaseSessionFormationService;

/**
 * Classe SessionFormationService pour gérer la persistance de l'entité SessionFormation.
 */
class SessionFormationService extends BaseSessionFormationService
{
    public function dataCalcul($sessionFormation)
    {
        // En Cas d'édit
        if(isset($sessionFormation->id)){
          
        }
      
        return $sessionFormation;
    }
   
}
