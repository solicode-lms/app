<?php


namespace Modules\PkgValidationProjets\Services;

use Illuminate\Support\Facades\DB;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Modules\PkgValidationProjets\Models\EtatEvaluationProjet;
use Modules\PkgValidationProjets\Models\EvaluationRealisationProjet;
use Modules\PkgValidationProjets\Services\Base\BaseEvaluationRealisationProjetService;

/**
 * Classe EvaluationRealisationProjetService pour gérer la persistance de l'entité EvaluationRealisationProjet.
 */
class EvaluationRealisationProjetService extends BaseEvaluationRealisationProjetService
{


    /**
     * Synchronise les enregistrements dans la table `evaluation_realisation_projets`
     * afin qu’il y ait exactement une ligne par (realisation_projet_id, evaluateur_id)
     * pour tous les évaluateurs actuellement affectés à ce projet, sur toutes les réalisations du projet.
     *
     * @param  \Modules\PkgGestionTaches\Models\AffectationProjet  $affectationProjet
     * @return void
     */
    public function SyncEvaluationRealisationProjet($affectationProjet)
    {
        // 1) Récupérer la liste des évaluateurs (leurs IDs) affectés à ce projet
        $evaluateursAssignes = $affectationProjet
            ->evaluateurs()
            ->pluck('id')
            ->toArray();

        // 2) Déterminer l'état initial (le plus petit ordre)
        $defaultEtat = EtatEvaluationProjet::orderBy('ordre')->first();
        $defaultEtatId = $defaultEtat ? $defaultEtat->id : null;

        // 3) Pour chaque RealisationProjet lié à cette AffectationProjet
        $realisationProjets = $affectationProjet->realisationProjets; // relation hasMany
        foreach ($realisationProjets as $realisationProjet) {
            $realisationProjetId = $realisationProjet->id;

            // 4) Récupérer tous les enregistrements existants d’EvaluationRealisationProjet
            $existingRecords = EvaluationRealisationProjet::query()
                ->where('realisation_projet_id', $realisationProjetId)
                ->get(['id', 'evaluateur_id'])
                ->keyBy('evaluateur_id');

            // 5) Synchroniser pour cette réalisation
            DB::transaction(function() use (
                $realisationProjetId,
                $evaluateursAssignes,
                $existingRecords,
                $defaultEtatId
            ) {
                // 5.a) Ajouter les évaluateurs manquants
                foreach ($evaluateursAssignes as $evalId) {
                    if (! isset($existingRecords[$evalId])) {

                        $this->create([
                            'realisation_projet_id'      => $realisationProjetId,
                            'evaluateur_id'              => $evalId,
                            'etat_evaluation_projet_id'  => $defaultEtatId,
                            'date_evaluation' => now()
                        ]);
                    }
                }

                // 5.b) Supprimer les évaluateurs retirés
                $evaluateursExistants = $existingRecords->keys()->toArray();
                $toDelete = array_diff($evaluateursExistants, $evaluateursAssignes);
                if (! empty($toDelete)) {
                    EvaluationRealisationProjet::query()
                        ->where('realisation_projet_id', $realisationProjetId)
                        ->whereIn('evaluateur_id', $toDelete)
                        ->delete();
                }
            });
        }
    }


   public function afterCreateRules($evaluationRealisationProjet, $id)
{
    // 1) Récupérer toutes les RealisationTache liées au même project
    $realisationTaches = RealisationTache::where('realisation_projet_id', $evaluationRealisationProjet->realisation_projet_id)
                       ->get();

    // 2) Pour chaque RealisationTache, créer un EvaluationRealisationTache avec note = null
    foreach ($realisationTaches as $tache) {
        (new EvaluationRealisationTacheService)->create([
            'evaluation_realisation_projet_id' => $evaluationRealisationProjet->id,
            'realisation_tache_id'             => $tache->id,
            'evaluateur_id'                    => $evaluationRealisationProjet->evaluateur_id,
            'note'                             => null,
            'message'                          => null,
        ]);
    }
}



    public function dataCalcul($evaluationRealisationProjet)
    {
        // En Cas d'édit
        if(isset($evaluationRealisationProjet->id)){
          
        }
      
        return $evaluationRealisationProjet;
    }
   
}
