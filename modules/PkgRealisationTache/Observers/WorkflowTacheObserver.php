<?php

namespace Modules\PkgRealisationTache\Observers;

use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgFormation\Models\Formateur;

class WorkflowTacheObserver
{
    public function created(WorkflowTache $workflowTache)
    {
        $formateurs = Formateur::all();

        foreach ($formateurs as $formateur) {
            $etat = EtatRealisationTache::where('formateur_id', $formateur->id)
                ->where('nom', $workflowTache->titre)
                ->first();

            if ($etat) {
                // ✅ Associer l’état existant s’il correspond
                if($etat->workflow_tache_id !=$workflowTache->id ){
                     $etat->workflow_tache_id = $workflowTache->id;
                    $etat->save();
                }
            } else {
                // ✅ Sinon créer un nouvel état
                EtatRealisationTache::create([
                    'nom' => $workflowTache->titre,
                    'description' => $workflowTache->description,
                    'reference' => uniqid('etat_'),
                    'formateur_id' => $formateur->id,
                    'sys_color_id' => $workflowTache->sys_color_id,
                    'workflow_tache_id' => $workflowTache->id,
                    'is_editable_only_by_formateur' => $workflowTache->is_editable_only_by_formateur ?? false,
                ]);
            }
        }
    }

    public function updated(WorkflowTache $workflowTache)
    {
        $etats = EtatRealisationTache::where('workflow_tache_id', $workflowTache->id)->get();

        foreach ($etats as $etat) {
            if (
                $etat->nom === $workflowTache->getOriginal('titre') &&
                $etat->sys_color_id === $workflowTache->getOriginal('sys_color_id') &&
                $etat->is_editable_only_by_formateur === $workflowTache->getOriginal('is_editable_only_by_formateur')
            ) {
                // ✅ Mise à jour uniquement si l’état n’a pas été modifié manuellement
                $etat->update([
                    'nom' => $workflowTache->titre,
                    'description' => $workflowTache->description,
                    'sys_color_id' => $workflowTache->sys_color_id,
                    'is_editable_only_by_formateur' => $workflowTache->is_editable_only_by_formateur ?? false,
                ]);
            }
        }
    }

    public function deleted(WorkflowTache $workflowTache)
    {
        $etats = EtatRealisationTache::where('workflow_tache_id', $workflowTache->id)->get();

        foreach ($etats as $etat) {
            $isUsed = $etat->realisationTaches()->exists(); // relation hasMany('realisationTaches')
            if (!$isUsed) {
                // ✅ État inutilisé → on peut le supprimer
                $etat->delete();
            }
        }
    }

}
