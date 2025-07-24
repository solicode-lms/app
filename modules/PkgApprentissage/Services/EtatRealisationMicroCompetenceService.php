<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseEtatRealisationMicroCompetenceService;

/**
 * Classe EtatRealisationMicroCompetenceService pour gérer la persistance de l'entité EtatRealisationMicroCompetence.
 */
class EtatRealisationMicroCompetenceService extends BaseEtatRealisationMicroCompetenceService
{
    public function dataCalcul($etatRealisationMicroCompetence)
    {
        // En Cas d'édit
        if(isset($etatRealisationMicroCompetence->id)){
          
        }
      
        return $etatRealisationMicroCompetence;
    }
   
}
