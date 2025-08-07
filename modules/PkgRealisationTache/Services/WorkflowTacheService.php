<?php


namespace Modules\PkgRealisationTache\Services;

use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\Base\BaseWorkflowTacheService;

/**
 * Classe WorkflowTacheService pour gérer la persistance de l'entité WorkflowTache.
 */
class WorkflowTacheService extends BaseWorkflowTacheService
{
    protected array $index_with_relations = ['sysColor'];
    

   

    
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
