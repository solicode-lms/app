<?php

namespace Modules\PkgRealisationTache\Services;

use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\Base\BaseWorkflowTacheService;

class WorkflowTacheService extends BaseWorkflowTacheService
{
    protected array $index_with_relations = ['sysColor'];

    /**
     * Récupère ou crée le WorkflowTache "Révision nécessaire".
     */
    public function getOrCreateWorkflowRevision(): WorkflowTache
    {
        return WorkflowTache::firstOrCreate(
            ['code' => 'REVISION_NECESSAIRE'],
            [
                'titre' => 'Révision nécessaire',
                'description' => 'La tâche a été révisée par le formateur.',
                'sys_color_id' => 4,
                'reference' => 'REVISION_NECESSAIRE',
            ]
        );
    }

    /**
     * Synchronise tous les EtatRealisationTache pour tous les workflows et formateurs.
     */
public function resyncEtatsFormateurs(): int
{
    $formateurs = Formateur::all();
    $workflows = WorkflowTache::all();
    $totalSynced = 0;

    foreach ($workflows as $workflow) {
        foreach ($formateurs as $formateur) {
            $etat = EtatRealisationTache::where('formateur_id', $formateur->id)
                ->where('nom', $workflow->titre)
                ->first();

            if ($etat) {
                if ($this->etatPeutEtreResynchronise($etat, $workflow)) {
                    $etat->update([
                        'workflow_tache_id' => $workflow->id,
                        'description' => $workflow->description,
                        'sys_color_id' => $workflow->sys_color_id,
                        'is_editable_only_by_formateur' => $workflow->is_editable_only_by_formateur ?? false,
                        'ordre' => $workflow->ordre,
                    ]);
                    $totalSynced++;
                }
            } else {
                $this->createEtatFromWorkflow($formateur->id, $workflow);
                $totalSynced++;
            }
        }
    }

    return $totalSynced;
}

protected function etatPeutEtreResynchronise(EtatRealisationTache $etat, WorkflowTache $workflow): bool
{
    return (
        $etat->sys_color_id === $workflow->sys_color_id &&
        $etat->is_editable_only_by_formateur === ($workflow->is_editable_only_by_formateur ?? false) 
    );
}

    /**
     * Gère la création ou la liaison des états lors de la création d’un WorkflowTache.
     */
    public function resyncEtatsPourWorkflow(WorkflowTache $workflow): void
    {
        $formateurs = Formateur::all();

        foreach ($formateurs as $formateur) {
            $etat = EtatRealisationTache::where('formateur_id', $formateur->id)
                ->where('nom', $workflow->titre)
                ->first();

            if ($etat) {
                $this->syncEtatExistante($etat, $workflow);
            } else {
                $this->createEtatFromWorkflow($formateur->id, $workflow);
            }
        }
    }

    /**
     * Met à jour les états liés si aucune modification manuelle n’a été faite.
     */
    public function updateEtatsPourWorkflow(WorkflowTache $workflow): void
    {
        $etats = EtatRealisationTache::where('workflow_tache_id', $workflow->id)->get();

        foreach ($etats as $etat) {
            if (
                $etat->nom === $workflow->getOriginal('titre') &&
                $etat->sys_color_id === $workflow->getOriginal('sys_color_id') &&
                $etat->is_editable_only_by_formateur === $workflow->getOriginal('is_editable_only_by_formateur')
            ) {
                $etat->update([
                    'nom' => $workflow->titre,
                    'description' => $workflow->description,
                    'sys_color_id' => $workflow->sys_color_id,
                    'is_editable_only_by_formateur' => $workflow->is_editable_only_by_formateur ?? false,
                    'ordre' => $workflow->ordre,
                ]);
            }
        }
    }

    /**
     * Supprime ou détache les états liés à un WorkflowTache supprimé.
     */
    public function detachEtatsPourWorkflow(WorkflowTache $workflow): void
    {
        $etats = EtatRealisationTache::where('workflow_tache_id', $workflow->id)->get();

        foreach ($etats as $etat) {
            if (!$etat->realisationTaches()->exists()) {
                $etat->delete();
            } 
        }
    }

    /**
     * Synchronise un état existant (créé manuellement ou précédemment lié).
     */
    protected function syncEtatExistante(EtatRealisationTache $etat, WorkflowTache $workflow): int
    {
        if ($etat->workflow_tache_id === $workflow->id) {
            return 0; // déjà à jour
        }

        if (is_null($etat->workflow_tache_id)) {
            $etat->update([
                'workflow_tache_id' => $workflow->id,
                'description' => $workflow->description,
                'sys_color_id' => $workflow->sys_color_id,
                'is_editable_only_by_formateur' => $workflow->is_editable_only_by_formateur ?? false,
                'ordre' => $workflow->ordre,
            ]);
            return 1;
        }

        return 0; // état lié à un autre workflow → pas de modification
    }

    /**
     * Crée un nouvel EtatRealisationTache à partir d’un WorkflowTache.
     */
    protected function createEtatFromWorkflow(int $formateurId, WorkflowTache $workflow): void
    {
        EtatRealisationTache::create([
            'nom' => $workflow->titre,
            'description' => $workflow->description,
            'reference' => uniqid('etat_'),
            'formateur_id' => $formateurId,
            'sys_color_id' => $workflow->sys_color_id,
            'workflow_tache_id' => $workflow->id,
            'ordre' => $workflow->ordre,
            'is_editable_only_by_formateur' => $workflow->is_editable_only_by_formateur ?? false,
        ]);
    }
}
