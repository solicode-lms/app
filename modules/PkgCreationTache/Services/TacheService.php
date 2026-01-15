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
     * Hook appelé avant la création d'une tâche.
     * Calcule automatiquement la note si c'est une tâche de type Evaluateur (N2, N3).
     *
     * @param array $data Les données de la tâche.
     * @return void
     */
    public function beforeCreateRules(&$data)
    {
        $this->calculateAndSetNote($data);
    }

    /**
     * Hook appelé avant la mise à jour d'une tâche.
     * Recalcule la note si nécessaire (ex: modification du projet ou de la phase).
     *
     * @param array $data Les données à mettre à jour.
     * @param mixed $id L'identifiant de la tâche.
     * @return void
     */
    public function beforeUpdateRules(&$data, $id = null)
    {
        $this->calculateAndSetNote($data, true, $id);
    }

    /**
     * Calcule et injecte la note de la tâche en fonction des UA du projet et de la phase d'évaluation.
     *
     * @param array $data Données de la tâche.
     * @param bool $isUpdate Indique si c'est une mise à jour (pour récupérer les données existantes).
     * @param mixed $tacheId L'ID de la tâche (cas update).
     */
    protected function calculateAndSetNote(&$data, $isUpdate = false, $tacheId = null)
    {
        $projectId = $data['projet_id'] ?? null;
        $phaseEvalId = $data['phase_evaluation_id'] ?? null;

        if ($isUpdate) {
            // En update, on doit récupérer les infos manquantes depuis l'entité existante
            $id = $tacheId ?? $data['id'] ?? null;
            if ($id) {
                // On utilise find sans relations pour être léger
                $tache = $this->model->find($id);
                if ($tache) {
                    $projectId = $projectId ?? $tache->projet_id;
                    $phaseEvalId = $phaseEvalId ?? $tache->phase_evaluation_id;
                }
            }
        }

        if ($projectId && $phaseEvalId) {
            // Récupérer le code de la phase d'évaluation
            $phaseEval = \Modules\PkgCompetences\Models\PhaseEvaluation::find($phaseEvalId);
            if (!$phaseEval)
                return;

            $code = $phaseEval->code; // N1, N2, N3

            // On ne calcule que pour Prototype (N2) et Réalisation (N3)
            if (in_array($code, ['N2', 'N3'])) {
                // Récupérer le projet et ses mobilisations
                // Optimisation : On ne charge que ce qui est nécessaire pour le calcul
                $projet = \Modules\PkgCreationProjet\Models\Projet::with(['mobilisationUas.uniteApprentissage.critereEvaluations.phaseEvaluation'])->find($projectId);

                if ($projet) {
                    $note = $projet->mobilisationUas->sum(function ($mobilisation) use ($code) {
                        if (!$mobilisation->uniteApprentissage)
                            return 0;
                        return $mobilisation->uniteApprentissage->critereEvaluations
                            ->filter(fn($c) => optional($c->phaseEvaluation)->code === $code)
                            ->sum('bareme');
                    });

                    $data['note'] = $note;
                }
            }
        }
    }

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
        if (!isset($tache->projet)) {
            return;
        }

        $notificationService = new NotificationService();

        // 1) Récupérer toutes les réalisations de projet (apprenants) via les affectations de ce projet
        $realisationProjets = $tache->projet
            ->affectationProjets
            ->flatMap(fn($affectation) => $affectation->realisationProjets);

        $realisationTacheService = new RealisationTacheService();
        $evaluationTacheService = new EvaluationRealisationTacheService();
        $etatService = new EtatRealisationTacheService();
        $evaluationProjetService = new EvaluationRealisationProjetService();

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
                'tache_id' => $tache->id,
                'realisation_projet_id' => $realisationProjet->id,
                'etat_realisation_tache_id' => $etatInitial?->id,
                'dateDebut' => $tache->dateDebut,
                'dateFin' => $tache->dateFin,
            ]);

            // Si la création est annulée (ex: chapitre déjà validé), on saute cette itération
            if (!$realisationTache) {
                continue;
            }

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
                        'evaluateur_id' => $evaluateur->id,
                    ]);

                    if (!empty($evaluationProjet)) {
                        $evaluationTacheService->create([
                            'realisation_tache_id' => $realisationTache->id,
                            'evaluateur_id' => $evaluateur->id,
                            'evaluation_realisation_projet_id' => $evaluationProjet->id,
                            // 'note' et 'message' restent à remplir lors de l’évaluation
                        ]);
                    }

                }
            }
        }

        // Mise à jour de la date de modification du projet parent
        $tache->projet->touch();
    }

    /**
     * Hook appelé après la mise à jour d’une tâche.
     *
     * @param  mixed  $tache
     * @return void
     */
    public function afterUpdateRules($tache)
    {
        if (isset($tache->projet)) {
            $tache->projet->touch();
        }
    }

    /**
     * Surcharge de la suppression pour mettre à jour la date du projet.
     *
     * @param  mixed  $id
     * @return mixed
     */
    public function destroy($id)
    {
        $tache = $this->find($id);
        $result = parent::destroy($id);

        if ($tache && isset($tache->projet)) {
            $tache->projet->touch();
        }

        return $result;
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


    public function allQuery(array $params = [], $query = null): Builder
    {
        $query = parent::allQuery($params, $query);

        // // Joindre les tables Tache et PrioriteTache avec LEFT JOIN pour inclure les tâches sans priorité
        // $query->leftJoin('priorite_taches', 'taches.priorite_tache_id', '=', 'priorite_taches.id')
        //         ->orderByRaw('COALESCE(priorite_taches.ordre, 9999) ASC') // Trier par priorité (les NULL en dernier)
        //         ->select('taches.*'); // Sélectionner les colonnes de la table principale

        return $query;
    }

}
