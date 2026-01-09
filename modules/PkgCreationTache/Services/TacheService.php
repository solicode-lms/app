<?php

namespace Modules\PkgCreationTache\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\PkgCreationTache\Services\Base\BaseTacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgNotification\Enums\NotificationType;
use Modules\PkgNotification\Services\NotificationService;
use Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 */
class TacheService extends BaseTacheService
{

    protected array $index_with_relations = [
        'projet', 
        'livrables'
    ];
    protected $ordreGroupColumn = "projet_id";


    /**
     * Hook appelé après la création d’une tâche
     * pour générer les réalisations et évaluations associées.
     *
     * @param  \Modules\PkgCreationTache\Models\Tache  $tache
     * @return void
     */
    public function afterCreateRules($tache): void
    {
        // Si la tâche n'est pas liée à un projet, on ne fait rien.
        if (! isset($tache->projet)) {
            return;
        }

        $notificationService = new NotificationService();

        // 1) Récupérer toutes les réalisations de projet (apprenants) via les affectations de ce projet
        $realisationProjets = $tache->projet
            ->affectationProjets
            ->flatMap(fn($affectation) => $affectation->realisationProjets);

        $realisationTacheService       = new RealisationTacheService();
        $evaluationTacheService        = new EvaluationRealisationTacheService();
        $etatService                   = new EtatRealisationTacheService();
        $evaluationProjetService        = new EvaluationRealisationProjetService();

        // Déterminer l'état initial selon l'utilisateur courant (s'il est formateur)
        $formateurId = Auth::user()->hasRole(Role::FORMATEUR_ROLE)
            ? Auth::user()->formateur?->id
            : null;

        $etatInitial = $formateurId
            ? $etatService->getDefaultEtatByFormateurId($formateurId)
            : null;

        /** 
         * 2) Pour chaque réalisation de projet (apprenant), créer une RealisationTache 
         *    puis, pour chaque évaluateur affecté à l’affectation de projet, créer une EvaluationRealisationTache.
         */
        foreach ($realisationProjets as $realisationProjet) {
            // 2.a) Création de la RealisationTache
            $realisationTache = $realisationTacheService->create([
                'tache_id'                  => $tache->id,
                'realisation_projet_id'     => $realisationProjet->id,
                'etat_realisation_tache_id' => $etatInitial?->id,
                'dateDebut'                 => $tache->dateDebut,
                'dateFin'                   => $tache->dateFin,
            ]);

            // 2.b) Notifications aux apprenants pour la nouvelle tâche
            $userApprenantId = $realisationProjet->apprenant?->user_id;
            if ($userApprenantId) {
                $notificationService->sendNotificationToReadData(
                    'realisationTache',
                    $realisationTache->id,
                    $userApprenantId,
                    "Nouvelle tâche attribuée : {$tache->titre}",
                    "Vous avez une nouvelle tâche à réaliser : {$tache->titre}",
                    NotificationType::NOUVELLE_TACHE->value
                );
            }

            // 2.c) Si l’affectation de projet a des évaluateurs, créer les évaluations
            $affectation = $realisationProjet->affectationProjet;
            if ($affectation?->evaluateurs->isNotEmpty()) {
                foreach ($affectation->evaluateurs as $evaluateur) {


                     // 2.c.i) Créer (ou récupérer) EvaluationRealisationProjet 
                    $evaluationProjet = EvaluationRealisationProjet::firstWhere([
                        'realisation_projet_id' => $realisationProjet->id,
                        'evaluateur_id'        => $evaluateur->id,
                    ]);

                    if(!empty($evaluationProjet)){
                        $evaluationTacheService->create([
                            'realisation_tache_id' => $realisationTache->id,
                            'evaluateur_id'        => $evaluateur->id,
                            'evaluation_realisation_projet_id' => $evaluationProjet->id,
                            // 'note' et 'message' restent à remplir lors de l’évaluation
                        ]);
                    }
                    
                }
            }
        }
    }


   /**
     * Récupérer les tâches associées aux projets d'un formateur donné.
     *
     * @param int $formateurId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTacheByFormateurId(int $formateurId)
    {
        return $this->model->whereHas('projet', function ($query) use ($formateurId) {
            $query->where('formateur_id', $formateurId);
        })->get();
    }

    /**
     * Récupérer les tâches associées aux projets d'un apprenant donné.
     *
     * @param int $apprenantId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTacheByApprenantId(int $apprenantId)
    {
        return $this->model->whereHas('realisationTaches', function ($query) use ($apprenantId) {
            $query->whereHas('realisationProjet', function ($q) use ($apprenantId) {
                $q->where('apprenant_id', $apprenantId);
            });
        })->get();
    }


    /**
     * Récupérer les tâches associées à une affectation de projet donnée.
     *
     * @param int $affectationProjetId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTacheByAffectationProjetId(int $affectationProjetId)
    {
        return $this->model->whereHas('projet', function ($query) use ($affectationProjetId) {
            $query->whereHas('affectationProjets', function ($q) use ($affectationProjetId) {
                $q->where('id', $affectationProjetId);
            });
        })->get();
    }


    public function allQuery(array $params = [],$query = null): Builder
    {
        $query = parent::allQuery($params,$query);

        // // Joindre les tables Tache et PrioriteTache avec LEFT JOIN pour inclure les tâches sans priorité
        // $query->leftJoin('priorite_taches', 'taches.priorite_tache_id', '=', 'priorite_taches.id')
        //         ->orderByRaw('COALESCE(priorite_taches.ordre, 9999) ASC') // Trier par priorité (les NULL en dernier)
        //         ->select('taches.*'); // Sélectionner les colonnes de la table principale

        return  $query;
    }

}
