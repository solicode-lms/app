<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseCritereEvaluationService;

/**
 * Classe CritereEvaluationService pour gérer la persistance de l'entité CritereEvaluation.
 */
class CritereEvaluationService extends BaseCritereEvaluationService
{
    public function dataCalcul($critereEvaluation)
    {
        // En Cas d'édit
        if(isset($critereEvaluation->id)){
          
        }
      
        return $critereEvaluation;
    }
   
}
