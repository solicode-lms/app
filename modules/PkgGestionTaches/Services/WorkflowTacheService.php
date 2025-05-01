<?php


namespace Modules\PkgGestionTaches\Services;

use Modules\PkgGestionTaches\Models\WorkflowTache;
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

    
    /**
     * get ou créer le WorkflowTache : REVISION_NECESSAIRE
     * @return TModel
     */
    public function getOrCreateWorkflowRevision()
    {
        return WorkflowTache::firstOrCreate([
            'code' => 'REVISION_NECESSAIRE'
        ], [
            'titre' => 'Révision nécessaire',
            'description' => 'La tâche a été révisée par le formateur.',
            'sys_color_id' => 4, // Couleur neutre
            'reference' => 'REVISION_NECESSAIRE',
        ]);
    }
   
}
