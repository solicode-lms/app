<?php

namespace Modules\PkgCreationTache\Services\Traits\Tache;

use Modules\PkgCompetences\Models\PhaseEvaluation;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgCreationTache\Models\PhaseProjet;

/**
 * Trait TacheCrudTrait
 * 
 * Gestion des Hooks du cycle de vie CRUD pour les tâches.
 */
trait TacheCrudTrait
{
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
            $phaseProjet = PhaseProjet::where('reference', 'LIVE_CODING')->first();
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



        // 1) Synchroniser les réalisations de compétences si le niveau d'évaluation a changé
        $this->syncRealisationPrototypeOrProjet($tache);
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
}
