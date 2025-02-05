<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseNiveauCompetenceService;

/**
 * Classe NiveauCompetenceService pour gérer la persistance de l'entité NiveauCompetence.
 */
class NiveauCompetenceService extends BaseNiveauCompetenceService
{
    public function dataCalcul($niveauCompetence)
    {
        // En Cas d'édit
        if(isset($niveauCompetence->id)){
          
        }
      
        return $niveauCompetence;
    }
}
