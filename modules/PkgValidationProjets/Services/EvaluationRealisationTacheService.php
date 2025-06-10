<?php

namespace Modules\PkgValidationProjets\Services;

use Illuminate\Support\Facades\DB;
use Modules\PkgGestionTaches\Services\RealisationTacheService;
use Modules\PkgValidationProjets\Models\EtatEvaluationProjet;
use Modules\PkgValidationProjets\Services\Base\BaseEvaluationRealisationTacheService;

/**
 * Classe EvaluationRealisationTacheService pour gérer la persistance de l'entité EvaluationRealisationTache.
 */
class EvaluationRealisationTacheService extends BaseEvaluationRealisationTacheService
{


   /**
     * Met à jour la note de la réalisation de tâche et l'état de la réalisation du projet
     * après la modification d'une évaluation de tâche.
     *
     * @param \App\Models\EvaluationRealisationTache $evaluationRealisationTache
     * @return void
     */
    public function afterUpdateRules($evaluationRealisationTache)
    {
        DB::transaction(function () use ($evaluationRealisationTache) {
            // Récupérer l'ID de la réalisation de tâche associée
            $realisationTacheId = $evaluationRealisationTache->realisation_tache_id;

            // Calculer la moyenne des notes des évaluations liées à la réalisation de tâche
            $averageNote = $this->model::where('realisation_tache_id', $realisationTacheId)
                ->avg('note');

            // Instancier le service RealisationTacheService
            $realisationTacheService = new RealisationTacheService();
            
            // Mettre à jour la note de la réalisation de tâche
            $realisationTacheService->update($realisationTacheId, [
                'note' => $averageNote,
            ]);

            // Récupérer la réalisation de tâche mise à jour
            $realisationTache = $realisationTacheService->find($realisationTacheId);

            // Instancier le service EvaluationRealisationProjetService
            $evaluationRealisationProjetService = new EvaluationRealisationProjetService();
            
            // Récupérer la réalisation de projet associée
            $evaluationRealisationProjet = $evaluationRealisationProjetService->find(
                $evaluationRealisationTache->evaluation_realisation_projet_id
            );

            // Récupérer toutes les évaluations des tâches liées au projet
            $evaluationRealisationTaches = $this->model::where('evaluation_realisation_projet_id', $evaluationRealisationProjet->id)
                ->get();

            // Déterminer l'état du projet en fonction des notes des tâches
            $allNotesNull = $evaluationRealisationTaches->every(function ($tache) {
                return is_null($tache->note);
            });

            $allEvaluated = $evaluationRealisationTaches->every(function ($tache) {
                return !is_null($tache->note);
            });

            // Définir le nouvel état du projet
            $newEtatCode = 'EN_COURS'; // État par défaut après modification
            if ($allNotesNull) {
                $newEtatCode = 'A_FAIRE';
            } elseif ($allEvaluated) {
                $newEtatCode = 'TERMINEE';
            }

            // Trouver l'état correspondant dans la table etats_realisation_projets
            $etatEvaluationProjet = EtatEvaluationProjet::where('code', $newEtatCode)->firstOrFail();

            // Mettre à jour l'état de la réalisation du projet
            $evaluationRealisationProjetService->update($evaluationRealisationProjet->id, [
                'etat_evaluation_projet_id' => $etatEvaluationProjet->id,
            ]);
        });
    }




    public function dataCalcul($evaluationRealisationTache)
    {
        // En Cas d'édit
        if(isset($evaluationRealisationTache->id)){
          
        }
      
        return $evaluationRealisationTache;
    }
   
}
