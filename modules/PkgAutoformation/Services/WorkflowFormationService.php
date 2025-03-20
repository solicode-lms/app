<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Services;
use Modules\PkgAutoformation\Services\Base\BaseWorkflowFormationService;

/**
 * Classe WorkflowFormationService pour gérer la persistance de l'entité WorkflowFormation.
 */
class WorkflowFormationService extends BaseWorkflowFormationService
{
    public function dataCalcul($workflowFormation)
    {
        // En Cas d'édit
        if(isset($workflowFormation->id)){
          
        }
      
        return $workflowFormation;
    }
   
}
