<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Services;
use Modules\PkgAutoformation\Services\Base\BaseWorkflowChapitreService;

/**
 * Classe WorkflowChapitreService pour gérer la persistance de l'entité WorkflowChapitre.
 */
class WorkflowChapitreService extends BaseWorkflowChapitreService
{
    public function dataCalcul($workflowChapitre)
    {
        // En Cas d'édit
        if(isset($workflowChapitre->id)){
          
        }
      
        return $workflowChapitre;
    }
   
}
