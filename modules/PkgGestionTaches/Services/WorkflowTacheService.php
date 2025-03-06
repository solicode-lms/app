<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Services;
use Modules\PkgGestionTaches\Services\Base\BaseWorkflowTacheService;

/**
 * Classe WorkflowTacheService pour gérer la persistance de l'entité WorkflowTache.
 */
class WorkflowTacheService extends BaseWorkflowTacheService
{
    public function dataCalcul($workflowTache)
    {
        // En Cas d'édit
        if(isset($workflowTache->id)){
          
        }
      
        return $workflowTache;
    }
   
}
