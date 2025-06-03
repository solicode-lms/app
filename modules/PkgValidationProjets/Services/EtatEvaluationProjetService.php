<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgValidationProjets\Services;
use Modules\PkgValidationProjets\Services\Base\BaseEtatEvaluationProjetService;

/**
 * Classe EtatEvaluationProjetService pour gérer la persistance de l'entité EtatEvaluationProjet.
 */
class EtatEvaluationProjetService extends BaseEtatEvaluationProjetService
{
    public function dataCalcul($etatEvaluationProjet)
    {
        // En Cas d'édit
        if(isset($etatEvaluationProjet->id)){
          
        }
      
        return $etatEvaluationProjet;
    }
   
}
