<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseRealisationMicroCompetenceService;

/**
 * Classe RealisationMicroCompetenceService pour gérer la persistance de l'entité RealisationMicroCompetence.
 */
class RealisationMicroCompetenceService extends BaseRealisationMicroCompetenceService
{
    public function dataCalcul($realisationMicroCompetence)
    {
        // En Cas d'édit
        if(isset($realisationMicroCompetence->id)){
          
        }
      
        return $realisationMicroCompetence;
    }
   
}
