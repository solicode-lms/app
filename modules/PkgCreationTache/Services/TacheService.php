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
     * Applique les règles métier (Calcul note, Assignation phase).
     *
     * @param array $data Les données de la tâche.
     * @return void
     */
    public function beforeCreateRules(&$data)
    {
        $this->applyBusinessRules($data);
    }

    /**
     * Hook appelé avant la mise à jour d'une tâche.
     * Applique les règles métier (Calcul note, Assignation phase).
     *
     * @param array $data Les données à mettre à jour.
     * @param mixed $id L'identifiant de la tâche.
     * @return void
     */
    public function beforeUpdateRules(&$data, $id = null)
    {
        $this->applyBusinessRules($data, true, $id);
    }

    /**
     * Applique l'ensemble des règles métier sur les données de la tâche.
     */
    protected function applyBusinessRules(&$data, $isUpdate = false, $tacheId = null)
    {
        // Récupération des données contextuelles
        $projectId = $data['projet_id'] ?? null;
        $phaseEvalId = $data['phase_evaluation_id'] ?? null;

        if ($isUpdate) {
            $id = $tacheId ?? $data['id'] ?? null;
            if ($id) {
                $tache = $this->model->find($id);
                if ($tache) {
                    $projectId = $projectId ?? $tache->projet_id;
                    $phaseEvalId = $phaseEvalId ?? $tache->phase_evaluation_id;
                }
            }
        }

        if ($phaseEvalId) {
            $phaseEval = PhaseEvaluation::find($phaseEvalId);
            if ($phaseEval) {
                $code = $phaseEval->code; // N1, N2, N3

                // 1. Règle : Mise à jour automatique de la phase projet
                $this->updatePhaseProjet($data, $code);

                // 2. Règle : Calcul de la note pour Prototype (N2) et Réalisation (N3)
                if (in_array($code, ['N2', 'N3']) && $projectId) {
                    $projet = Projet::with(['mobilisationUas.uniteApprentissage.critereEvaluations.phaseEvaluation'])->find($projectId);

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
    }

    /**
     * Met à jour la phase projet en fonction du niveau d'évaluation.
     */
    protected function updatePhaseProjet(&$data, $phaseEvalCode)
    {
        // On ne surcharge la phase projet que si elle n'est pas explicitement fixée
        // OU si on veut forcer la cohérence. Ici on force la cohérence.
        $phaseProjet = null;
        if ($phaseEvalCode === 'N1') {
            $phaseProjet = PhaseProjet::where('reference', 'APPRENTISSAGE')->first();
        } elseif ($phaseEvalCode === 'N2') {
            $phaseProjet = PhaseProjet::where('reference', 'PROTOTYPE')->first();
        } elseif ($phaseEvalCode === 'N3') {
            $phaseProjet = PhaseProjet::where('reference', 'REALISATION')->first();
        }

        if ($phaseProjet) {
            $data['phase_projet_id'] = $phaseProjet->id;
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

        // 3) Synchroniser les ponts de compétences (RealisationUaPrototype/Projet) si N2/N3
        // Cela couvre le cas où la tâche est créée directement avec le bon niveau
        $this->syncCompetenceBridges($tache);
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

        // Synchroniser les ponts de compétences si le niveau d'évaluation a changé ou si nouveau projet
        $this->syncCompetenceBridges($tache);
    }

    /**
     * Synchronise les ponts de compétences (RealisationUaPrototype/Projet) pour cette tâche.
     * Crée les liens nécessaires entre les réalisations de tâche et les UA (via RealisationUa)
     * pour les phases N2 (Prototype) et N3 (Réalisation).
     *
     * @param mixed $tache La tâche concernée.
     * @return void
     */
    public function syncCompetenceBridges($tache)
    {
        // 1. Vérifier si N2 ou N3
        $tache->load('phaseEvaluation');
        $code = $tache->phaseEvaluation?->code;

        if (!in_array($code, ['N2', 'N3']))
            return;

        // 2. Récupérer les mobilisations du projet
        $mobilisations = \Modules\PkgCreationProjet\Models\MobilisationUa::where('projet_id', $tache->projet_id)->get();
        if ($mobilisations->isEmpty())
            return;

        // 3. Récupérer les réalisations de cette tâche
        $realisationTaches = $tache->realisationTaches;
        if ($realisationTaches->isEmpty())
            return;

        $realisationUaService = new \Modules\PkgApprentissage\Services\RealisationUaService();
        $realisationUaProjetService = app(\Modules\PkgApprentissage\Services\RealisationUaProjetService::class);
        $realisationUaPrototypeService = app(\Modules\PkgApprentissage\Services\RealisationUaPrototypeService::class);

        foreach ($realisationTaches as $rt) {
            // Charger la relation RealisationProjet si pas chargée
            if (!$rt->relationLoaded('realisationProjet')) {
                $rt->load('realisationProjet');
            }
            $realisationProjet = $rt->realisationProjet;

            if (!$realisationProjet)
                continue;

            $apprenantId = $realisationProjet->apprenant_id;

            foreach ($mobilisations as $mobilisation) {
                // Récupérer RealisationUa
                $realisationUA = $realisationUaService->getOrCreateApprenant(
                    $apprenantId,
                    $mobilisation->unite_apprentissage_id
                );

                if ($code === 'N2') {
                    $exists = \Modules\PkgApprentissage\Models\RealisationUaPrototype::where('realisation_tache_id', $rt->id)
                        ->where('realisation_ua_id', $realisationUA->id)
                        ->exists();
                    if (!$exists) {
                        $realisationUaPrototypeService->create([
                            'realisation_tache_id' => $rt->id,
                            'realisation_ua_id' => $realisationUA->id,
                            'bareme' => $mobilisation->bareme_evaluation_prototype ?? 0,
                        ]);
                    }
                } elseif ($code === 'N3') {
                    $exists = \Modules\PkgApprentissage\Models\RealisationUaProjet::where('realisation_tache_id', $rt->id)
                        ->where('realisation_ua_id', $realisationUA->id)
                        ->exists();
                    if (!$exists) {
                        $realisationUaProjetService->create([
                            'realisation_tache_id' => $rt->id,
                            'realisation_ua_id' => $realisationUA->id,
                            'bareme' => $mobilisation->bareme_evaluation_projet ?? 0,
                        ]);
                    }
                }
            }
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
