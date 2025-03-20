<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Services;
use Modules\PkgAutoformation\Services\Base\BaseRealisationFormationService;

/**
 * Classe RealisationFormationService pour gérer la persistance de l'entité RealisationFormation.
 */
class RealisationFormationService extends BaseRealisationFormationService
{
    public function dataCalcul($realisationFormation)
    {
        // En Cas d'édit
        if(isset($realisationFormation->id)){
          
        }
      
        return $realisationFormation;
    }
   
}
