<?php


namespace Modules\PkgRealisationTache\Services;

use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
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

    /**
     * Synchronise les EtatsRealisationTache pour tous les formateurs à partir des WorkflowTaches.
     *
     * @return int Nombre total d'états synchronisés (créés ou mis à jour)
     */
    public function resyncEtatsFormateurs(): int
    {
        $formateurs = Formateur::all();
        $workflowTaches = WorkflowTache::all();
        $totalSynced = 0;

        foreach ($workflowTaches as $workflow) {
            foreach ($formateurs as $formateur) {
                $etat = EtatRealisationTache::where('formateur_id', $formateur->id)
                    ->where('nom', $workflow->titre)
                    ->first();

                if ($etat) {
                    if (!$etat->workflow_tache_id) {
                        $etat->workflow_tache_id = $workflow->id;
                        $etat->description = $workflow->description;
                        $etat->sys_color_id = $workflow->sys_color_id;
                        $etat->is_editable_only_by_formateur = $workflow->is_editable_only_by_formateur ?? false;
                        $etat->save();
                        $totalSynced++;
                    }
                } else {
                    EtatRealisationTache::create([
                        'nom' => $workflow->titre,
                        'description' => $workflow->description,
                        'reference' => uniqid('etat_'),
                        'formateur_id' => $formateur->id,
                        'sys_color_id' => $workflow->sys_color_id,
                        'workflow_tache_id' => $workflow->id,
                        'ordre' => $workflow->ordre,
                        'is_editable_only_by_formateur' => $workflow->is_editable_only_by_formateur ?? false,
                    ]);
                    $totalSynced++;
                }
            }
        }

        return $totalSynced;
    }
   
}
