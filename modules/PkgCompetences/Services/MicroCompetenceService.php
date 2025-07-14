<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseMicroCompetenceService;

/**
 * Classe MicroCompetenceService pour gérer la persistance de l'entité MicroCompetence.
 */
class MicroCompetenceService extends BaseMicroCompetenceService
{
    public function dataCalcul($microCompetence)
    {
        // En Cas d'édit
        if(isset($microCompetence->id)){
          
        }
      
        return $microCompetence;
    }
   
}
