<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgValidationProjets\Services;
use Modules\PkgValidationProjets\Services\Base\BaseEvaluationRealisationTacheService;

/**
 * Classe EvaluationRealisationTacheService pour gérer la persistance de l'entité EvaluationRealisationTache.
 */
class EvaluationRealisationTacheService extends BaseEvaluationRealisationTacheService
{
    public function dataCalcul($evaluationRealisationTache)
    {
        // En Cas d'édit
        if(isset($evaluationRealisationTache->id)){
          
        }
      
        return $evaluationRealisationTache;
    }
   
}
