<?php

namespace Modules\PkgRealisationProjets\Services\Traits\RealisationProjet;

use Illuminate\Support\Facades\Auth;

use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;
use Modules\PkgAutorisation\Models\Role;
use Illuminate\Validation\ValidationException;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;

trait RealisationProjetCrudTrait
{

    /**
     * Règles métiers appliquées avant la création d'une réalisation de projet.
     *
     * @param array $data Données à insérer (passées par référence).
     * @return void
     */
    public function beforeCreateRules(array &$data): void
    {
        // Affecter l'état "TODO" par défaut si non spécifié
        if (empty($data['etats_realisation_projet_id'])) {
            $etatsRealisationProjetService = app(EtatsRealisationProjetService::class);
            $etatTodo = $etatsRealisationProjetService->getByCode('TODO');

            if ($etatTodo) {
                $data['etats_realisation_projet_id'] = $etatTodo->id;
            }
        }
    }

    /**
     * Actions post-création d'une réalisation de projet.
     *
     * - Envoie une notification à l'apprenant.
     * - Génère les tâches de réalisation associées.
     *
     * @param RealisationProjet $realisationProjet L'instance créée.
     * @return void
     */
    public function afterCreateRules($realisationProjet): void
    {
        if (!$realisationProjet instanceof RealisationProjet) {
            return; // 🛡️ Vérification de sécurité
        }

        // Étape 2 : Notification
        $this->notifierApprenant($realisationProjet);

        // Étape 3 : Création des RealisationTache pour ce projet spécifique
        $this->genererRealisationTaches($realisationProjet);
    }

    /**
     * Génère les réalisations de tâches pour le projet en cours.
     * Cette méthode remplace l'appel coûteux à TacheService::update.
     * 
     * @param RealisationProjet $realisationProjet
     * @return void
     */
    protected function genererRealisationTaches(RealisationProjet $realisationProjet): void
    {
        $projet = $realisationProjet->affectationProjet->projet ?? null;

        if (!$projet) {
            return;
        }

        // Chargement des services nécessaires à la demande
        $realisationTacheService = app(RealisationTacheService::class);
        $etatService = app(EtatRealisationTacheService::class);
        $evaluationTacheService = app(EvaluationRealisationTacheService::class);

        // Déterminer l'état initial
        $formateurId = $projet->formateur_id;
        $etatInitial = $formateurId ? $etatService->getDefaultEtatByFormateurId($formateurId) : null;

        // Préparer les évaluateurs si présents
        $affectation = $realisationProjet->affectationProjet;
        $evaluateurs = $affectation->evaluateurs ?? collect();

        foreach ($projet->taches as $tache) {
            // Vérification existence pour éviter doublons
            // Vérification existence pour éviter doublons via méthode dédiée
            $exists = $realisationTacheService->existsForTacheAndProject($tache->id, $realisationProjet->id);

            if ($exists) {
                continue;
            }

            // Création de la RealisationTache
            // Note : Les hooks de RealisationTacheService (before/afterCreateRules) géreront :
            // - La déduction de tache_affectation_id
            // - La synchro des compétences (RealisationUaPrototype/Projet)
            $realisationTache = $realisationTacheService->create([
                'tache_id' => $tache->id,
                'realisation_projet_id' => $realisationProjet->id,
                'etat_realisation_tache_id' => $etatInitial?->id,
                'dateDebut' => $tache->dateDebut,
                'dateFin' => $tache->dateFin,
            ]);

            if (!$realisationTache) {
                continue;
            }

            // Création des Évaluations liées (si évaluateurs assignés)
            if ($evaluateurs->isNotEmpty()) {
                foreach ($evaluateurs as $evaluateur) {
                    // Retrouver l'évaluation projet parente
                    $evaluationProjet = \Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet::where([
                        'realisation_projet_id' => $realisationProjet->id,
                        'evaluateur_id' => $evaluateur->id,
                    ])->first();

                    if ($evaluationProjet) {
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
     * Règles métiers appliquées avant la mise à jour d'un RealisationProjet.
     *
     * @param array $data Données à mettre à jour (passées par référence).
     * @param int $id Identifiant de l'entité à modifier.
     * @return void
     * @throws ValidationException En cas de violation de règles métier.
     */
    public function beforeUpdateRules(array &$data, int $id): void
    {
        $entity = $this->find($id);

        if (empty($entity)) {
            throw ValidationException::withMessages([
                'id' => "Projet de réalisation introuvable."
            ]);
        }

        // 🛡️ Vérification de changement d'état
        if (!empty($data["etats_realisation_projet_id"])) {
            $nouvelEtatId = $data["etats_realisation_projet_id"];

            $etatActuel = $entity->etatsRealisationProjet;

            // Charger le nouvel état pour validation
            $etatsRealisationProjetService = app(EtatsRealisationProjetService::class);
            $nouvelEtat = $etatsRealisationProjetService->find($nouvelEtatId);

            if (!$nouvelEtat) {
                throw ValidationException::withMessages([
                    'etats_realisation_projet_id' => "L'état sélectionné est invalide."
                ]);
            }

            // 🛡️ 1. Empêcher la modification d'un état actuel protégé
            if ($etatActuel) {
                if (
                    $etatActuel->is_editable_by_formateur
                    && $etatActuel->id !== $nouvelEtatId
                    && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
                ) {
                    throw ValidationException::withMessages([
                        'etats_realisation_projet_id' => "L'état actuel du projet ne peut être changé que par un formateur."
                    ]);
                }
            }

            // 🛡️ 2. Empêcher l'affectation d'un nouvel état protégé
            if (
                $nouvelEtat->is_editable_by_formateur
                && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
            ) {
                throw ValidationException::withMessages([
                    'etats_realisation_projet_id' => "Vous ne pouvez pas affecter cet état réservé au formateur."
                ]);
            }
        }

        // 🛡️ 3. Vérification cohérence dates (facultatif mais recommandé)
        if (isset($data['date_debut'], $data['date_fin']) && $data['date_debut'] > $data['date_fin']) {
            throw ValidationException::withMessages([
                'date_fin' => "La date de fin doit être postérieure à la date de début."
            ]);
        }
    }


}
