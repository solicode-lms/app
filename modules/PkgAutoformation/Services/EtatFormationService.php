<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Services;
use Modules\PkgAutoformation\Services\Base\BaseEtatFormationService;

/**
 * Classe EtatFormationService pour gérer la persistance de l'entité EtatFormation.
 */
class EtatFormationService extends BaseEtatFormationService
{
    public function dataCalcul($etatFormation)
    {
        // En Cas d'édit
        if(isset($etatFormation->id)){
          
        }
      
        return $etatFormation;
    }
   
}
