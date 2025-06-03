<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgValidationProjets\Services;
use Modules\PkgValidationProjets\Services\Base\BaseEvaluationRealisationProjetService;

/**
 * Classe EvaluationRealisationProjetService pour gérer la persistance de l'entité EvaluationRealisationProjet.
 */
class EvaluationRealisationProjetService extends BaseEvaluationRealisationProjetService
{
    public function dataCalcul($evaluationRealisationProjet)
    {
        // En Cas d'édit
        if(isset($evaluationRealisationProjet->id)){
          
        }
      
        return $evaluationRealisationProjet;
    }
   
}
