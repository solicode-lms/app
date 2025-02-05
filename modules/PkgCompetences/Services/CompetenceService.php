<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseCompetenceService;

/**
 * Classe CompetenceService pour gérer la persistance de l'entité Competence.
 */
class CompetenceService extends BaseCompetenceService
{
    public function dataCalcul($competence)
    {
        // En Cas d'édit
        if(isset($competence->id)){
          
        }
      
        return $competence;
    }
}
