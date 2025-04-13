<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Services;
use Modules\PkgRealisationProjets\Services\Base\BaseWorkflowProjetService;

/**
 * Classe WorkflowProjetService pour gérer la persistance de l'entité WorkflowProjet.
 */
class WorkflowProjetService extends BaseWorkflowProjetService
{
    public function dataCalcul($workflowProjet)
    {
        // En Cas d'édit
        if(isset($workflowProjet->id)){
          
        }
      
        return $workflowProjet;
    }
   
}
