<?php

namespace Modules\PkgCreationTache\Services\Traits\Tache;

use Modules\PkgNotification\Services\NotificationService;
use Modules\PkgNotification\Enums\NotificationType;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;
use Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet;
use Modules\PkgApprentissage\Services\RealisationUaService;

/**
 * Trait TacheRelationsTrait
 * 
 * Gestion des relations complexes et synchronisations (Réalisations Tâches / Compétences).
 */
trait TacheRelationsTrait
{
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

        // Si la tâche est assignée à une équipe spécifique, filtrer les apprenants
        if ($tache->equipe_projet_id) {
            $apprenantsEquipeIds = $tache->equipeProjet->apprenants->pluck('id')->toArray();
            
            $realisationProjets = $realisationProjets->filter(function($realisationProjet) use ($apprenantsEquipeIds) {
                return in_array($realisationProjet->apprenant_id, $apprenantsEquipeIds);
            });
        }

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
            // Appel à la méthode centralisée
            $realisationTache = $realisationTacheService->initialiserRealisationTache($tache, $realisationProjet, $etatInitial);

            if ($realisationTache) {
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

        // 2. Récupérer les réalisations de cette tâche
        $realisationTaches = $tache->realisationTaches;
        if ($realisationTaches->isEmpty())
            return;

        // Délégation du traitement au Service métier dédié (RealisationTacheService)
        // pour éviter la duplication de logique.
        $realisationTacheService = app(RealisationTacheService::class);

        foreach ($realisationTaches as $rt) {
            $realisationTacheService->syncRealisationPrototypeEtProjetAvecMobilisations($rt);
        }
    }
}
