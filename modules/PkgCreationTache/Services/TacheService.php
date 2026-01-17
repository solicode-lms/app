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
use Modules\PkgCompetences\Models\PhaseEvaluation;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgCreationTache\Models\PhaseProjet;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Services\RealisationUaProjetService;
use Modules\PkgApprentissage\Services\RealisationUaPrototypeService;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;
use Modules\PkgApprentissage\Models\RealisationUaProjet;

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
     * 1. **Mise à jour automatique de la Phase Projet** : Basée sur le code de la phase d'évaluation (N1 -> Apprentissage, N2 -> Prototype, N3 -> Réalisation).
     * 2. **Calcul de la Note (N2/N3)** : Pour les tâches d'évaluation, la note est calculée automatiquement comme la somme des barèmes des UA mobilisées sur le projet pour ce niveau.
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

        // 1) Créer les réalisations de tâches pour les apprenants
        $this->createRealisationTaches($tache);

        // Mise à jour de la date de modification du projet parent
        $tache->projet->touch();

        // 2) Synchroniser les réalisations de compétences (RealisationUaPrototype/Projet) si N2/N3
        $this->syncRealisationPrototypeOrProjet($tache);
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

        // 1) Créer les réalisations de tâches manquantes (ex: nouveaux apprenants)
        $this->createRealisationTaches($tache);

        // 2) Synchroniser les réalisations de compétences si le niveau d'évaluation a changé
        $this->syncRealisationPrototypeOrProjet($tache);
    }

    /**
     * Crée les réalisations de tâches pour tous les apprenants du projet.
     */
    public function createRealisationTaches($tache)
    {
        $notificationService = new NotificationService();

        // Récupérer toutes les réalisations de projet (apprenants) via les affectations de ce projet
        $realisationProjets = $tache->projet
            ->affectationProjets
            ->flatMap(fn($affectation) => $affectation->realisationProjets);

        $realisationTacheService = new RealisationTacheService();
        $evaluationTacheService = new EvaluationRealisationTacheService();
        $etatService = new EtatRealisationTacheService();
        $evaluationProjetService = new EvaluationRealisationProjetService();

        // Déterminer l'état initial selon l'utilisateur courant (s'il est formateur)
        // Note: Lors d'un update, l'utilisateur est peut-être différent, mais pour la création initiale c'est ok.
        $formateurId = $tache->projet->formateur_id; // On se base sur le formateur du projet plutôt que Auth pour la consistence en batch

        $etatInitial = $formateurId
            ? $etatService->getDefaultEtatByFormateurId($formateurId)
            : null;

        foreach ($realisationProjets as $realisationProjet) {
            // Unicité : on vérifie si la tâche est déjà réalisée pour cet apprenant
            $exists = $tache->realisationTaches()
                ->where('realisation_projet_id', $realisationProjet->id)
                ->exists();

            if ($exists) {
                continue;
            }

            // Création de la RealisationTache
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

            // Notifications aux apprenants pour la nouvelle tâche
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

            // Si l’affectation de projet a des évaluateurs, créer les évaluations
            $affectation = $realisationProjet->affectationProjet;
            if ($affectation?->evaluateurs->isNotEmpty()) {
                foreach ($affectation->evaluateurs as $evaluateur) {
                    $evaluationProjet = EvaluationRealisationProjet::firstWhere([
                        'realisation_projet_id' => $realisationProjet->id,
                        'evaluateur_id' => $evaluateur->id,
                    ]);

                    if (!empty($evaluationProjet)) {
                        $evaluationTacheService->create([
                            'realisation_tache_id' => $realisationTache->id,
                            'evaluateur_id' => $evaluateur->id,
                            'evaluation_realisation_projet_id' => $evaluationProjet->id,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Synchronise les objets de réalisation de compétences (RealisationUaPrototype/Projet) pour cette tâche.
     * Cette méthode crée les ponts nécessaires entre les réalisations de tâche (élèves) et les UA mobilisées sur le projet.
     * Elle est déclenchée lors de la création ou mise à jour de tâches d'évaluation (N2/N3).
     *
     * @param mixed $tache La tâche concernée.
     * @return void
     */
    public function syncRealisationPrototypeOrProjet($tache)
    {
        // 1. Vérifier si N2 ou N3
        $tache->load('phaseEvaluation');
        $code = $tache->phaseEvaluation?->code;

        if (!in_array($code, ['N2', 'N3']))
            return;

        // 2. Récupérer les mobilisations actuelles du projet (les UA valides)
        $mobilisations = MobilisationUa::where('projet_id', $tache->projet_id)->get();
        $validUaIds = $mobilisations->pluck('unite_apprentissage_id')->toArray();

        // 3. Récupérer les réalisations de cette tâche
        $realisationTaches = $tache->realisationTaches;
        if ($realisationTaches->isEmpty())
            return;

        $realisationUaService = new RealisationUaService();
        $realisationUaProjetService = app(RealisationUaProjetService::class);
        $realisationUaPrototypeService = app(RealisationUaPrototypeService::class);

        foreach ($realisationTaches as $rt) {
            // Charger la relation RealisationProjet si pas chargée
            if (!$rt->relationLoaded('realisationProjet')) {
                $rt->load('realisationProjet');
            }
            $realisationProjet = $rt->realisationProjet;

            if (!$realisationProjet)
                continue;

            // A. NETTOYAGE : Supprimer les ponts vers des UA qui ne sont plus mobilisées
            // On supprime les entrées liées à cette réalisation de tâche dont l'UA n'est plus dans $validUaIds
            if ($code === 'N2') {
                RealisationUaPrototype::where('realisation_tache_id', $rt->id)
                    ->whereHas('realisationUa', function ($q) use ($validUaIds) {
                        $q->whereNotIn('unite_apprentissage_id', $validUaIds);
                    })->delete();
            } elseif ($code === 'N3') {
                RealisationUaProjet::where('realisation_tache_id', $rt->id)
                    ->whereHas('realisationUa', function ($q) use ($validUaIds) {
                        $q->whereNotIn('unite_apprentissage_id', $validUaIds);
                    })->delete();
            }

            // B. CRÉATION / MISE À JOUR : Ajouter les ponts manquants pour les mobilisations actuelles
            $apprenantId = $realisationProjet->apprenant_id;

            foreach ($mobilisations as $mobilisation) {
                // Récupérer ou créer RealisationUa
                $realisationUA = $realisationUaService->getOrCreateApprenant(
                    $apprenantId,
                    $mobilisation->unite_apprentissage_id
                );

                if ($code === 'N2') {
                    $exists = RealisationUaPrototype::where('realisation_tache_id', $rt->id)
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
                    $exists = RealisationUaProjet::where('realisation_tache_id', $rt->id)
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

    /**
     * Crée les tâches (Tutoriels) associées aux chapitres d'une UA.
     */
    public function createTasksFromUa($projetId, $ua)
    {
        if (is_numeric($ua)) {
            $ua = \Modules\PkgCompetences\Models\UniteApprentissage::with('chapitres')->find($ua);
        }

        if (!$ua || $ua->chapitres->isEmpty())
            return;

        $phaseN1Id = \Modules\PkgCompetences\Models\PhaseEvaluation::where('code', 'N1')->value('id');
        $phaseApprentissage = \Modules\PkgCreationTache\Models\PhaseProjet::where('reference', 'APPRENTISSAGE')->first();
        $phaseProjetId = $phaseApprentissage ? $phaseApprentissage->id : null;

        $ordre = 1;
        $maxOrdrePhase = 0;

        if ($phaseProjetId) {
            $maxOrdrePhase = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->where('phase_projet_id', $phaseProjetId)->max('ordre');
        }

        if ($maxOrdrePhase) {
            $ordre = $maxOrdrePhase + 1;
        } else {
            $maxOrdrePrecedent = 0;
            if ($phaseApprentissage) {
                $previousPhaseIds = \Modules\PkgCreationTache\Models\PhaseProjet::where('ordre', '<', $phaseApprentissage->ordre)->pluck('id');
                if ($previousPhaseIds->isNotEmpty()) {
                    $maxOrdrePrecedent = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                        ->whereIn('phase_projet_id', $previousPhaseIds)->max('ordre');
                }
            }
            $ordre = $maxOrdrePrecedent ? $maxOrdrePrecedent + 1 : 1;
        }

        foreach ($ua->chapitres as $chapitre) {
            $exists = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->where('titre', 'Tutoriel : ' . $chapitre->nom)->exists();

            if (!$exists) {
                $this->create([
                    'projet_id' => $projetId,
                    'titre' => 'Tutoriel : ' . $chapitre->nom,
                    'description' => "Tutoriel lié au chapitre : " . $chapitre->nom,
                    'phase_evaluation_id' => $phaseN1Id,
                    'priorite' => $ordre,
                    'ordre' => $ordre,
                    'chapitre_id' => $chapitre->id,
                    'phase_projet_id' => $phaseProjetId
                ]);
                $ordre++;
            }
        }
    }
}
