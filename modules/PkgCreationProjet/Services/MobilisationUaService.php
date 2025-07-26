<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseMobilisationUaService;

/**
 * Classe MobilisationUaService pour gérer la persistance de l'entité MobilisationUa.
 */
class MobilisationUaService extends BaseMobilisationUaService
{
    public function dataCalcul($mobilisationUa)
    {
        // En Cas d'édit
        if(isset($mobilisationUa->id)){
          
        }
      
        return $mobilisationUa;
    }
   
}
