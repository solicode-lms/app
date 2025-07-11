<?php

namespace Modules\PkgValidationProjets\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgValidationProjets\Models\EtatEvaluationProjet;
use Modules\PkgValidationProjets\Services\Base\BaseEvaluationRealisationTacheService;

/**
 * Classe EvaluationRealisationTacheService pour gérer la persistance de l'entité EvaluationRealisationTache.
 */
class EvaluationRealisationTacheService extends BaseEvaluationRealisationTacheService
{

    protected array $index_with_relations = [
        'evaluateur',
        'realisationTache', // relation directe avec realisation_taches
        'realisationTache.tache.livrables', // pour avoir dateFin, note, ordre, etc.
        'realisationTache.realisationProjet', // pour accéder au projet et apprenant
        'realisationTache.realisationProjet.apprenant', // pour CONCAT nom + prénom
        'realisationTache.realisationProjet.apprenant.groupes', // pour récupérer le groupe
        'realisationTache.realisationProjet.apprenant.groupes.filiere', // pour nom_filiere
        'realisationTache.livrablesRealisations.livrable.taches'
    ];
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


            $realisationTacheService = new RealisationTacheService();
            $evaluationRealisationProjetService = new EvaluationRealisationProjetService();
            // Charger toutes les relations nécessaires d’un seul coup
            $evaluationRealisationTache->loadMissing([
                'realisationTache.evaluationRealisationTaches',
                'evaluationRealisationProjet.evaluationRealisationTaches'
            ]);

            $realisationTache = $evaluationRealisationTache->realisationTache;

            // Calcul de la moyenne des notes des évaluations liées à la tâche
            $averageNote = $realisationTache->evaluationRealisationTaches->avg('note');

            // Mise à jour directe de la note de la tâche

            $realisationTacheService->update($realisationTache,[
                'note' => $averageNote,
            ] );
           

            // Récupération du projet via les relations chargées
            $evaluationRealisationProjet = $evaluationRealisationTache->evaluationRealisationProjet;

            // Récupération des évaluations liées au projet
            $evaluationRealisationTaches = $evaluationRealisationProjet->evaluationRealisationTaches;

            // Vérifications des états
            $allNotesNull = $evaluationRealisationTaches->every(fn($t) => is_null($t->note));
            $allEvaluated = $evaluationRealisationTaches->every(fn($t) => !is_null($t->note));

            // Détermination du nouvel état
            $newEtatCode = $allNotesNull ? 'A_FAIRE' : ($allEvaluated ? 'TERMINEE' : 'EN_COURS');

            // Mise à jour de l’état du projet
            $evaluationRealisationProjetService->update($evaluationRealisationProjet,[
                'etat_evaluation_projet_id' => $this->getEtatEvaluationProjetByCode($newEtatCode)->id,
            ]);
        });
    }

    /**
     * Méthode utilitaire pour éviter de refaire la requête à chaque fois
     */
    protected function getEtatEvaluationProjetByCode(string $code)
    {
        static $cache = [];

        return $cache[$code] ??= EtatEvaluationProjet::where('code', $code)->firstOrFail();
    }


    public function defaultSort($query)
    {
        $model = $query->getModel();
        $table = $model->getTable();

        return $query
            ->select("{$table}.*")
            ->join('realisation_taches as rt', 'rt.id', '=', "{$table}.realisation_tache_id")
            ->join('taches as t', 't.id', '=', 'rt.tache_id')
            ->orderBy('t.ordre', 'asc');
    }

    public function dataCalcul($evaluationRealisationTache)
    {
        // En Cas d'édit
        if(isset($evaluationRealisationTache->id)){
          
        }
      
        return $evaluationRealisationTache;
    }
   
}
