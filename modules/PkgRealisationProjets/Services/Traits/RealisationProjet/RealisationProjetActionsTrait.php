<?php

namespace Modules\PkgRealisationProjets\Services\Traits\RealisationProjet;

use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Services\RealisationUaProjetService;
use Modules\PkgApprentissage\Services\RealisationUaPrototypeService;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;
use Modules\PkgApprentissage\Models\RealisationUaProjet;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\PkgNotification\Enums\NotificationType;

trait RealisationProjetActionsTrait
{
    /**
     * Envoie une notification à l'apprenant assigné au projet.
     *
     * @param \Modules\PkgRealisationProjets\Models\RealisationProjet $realisationProjet
     * @return void
     */
    private function notifierApprenant(RealisationProjet $realisationProjet): void
    {
        $apprenant = $realisationProjet->apprenant;

        if ($apprenant && $apprenant->user) {
            /** @var \Modules\PkgNotification\Services\NotificationService $notificationService */
            $notificationService = app(\Modules\PkgNotification\Services\NotificationService::class);

            $notificationService->sendNotification(
                userId: $apprenant->user->id,
                title: 'Nouveau Projet de Réalisation Assigné',
                message: "Vous avez été assigné à un nouveau projet de réalisation. Consultez votre espace projets.",
                data: [
                    'lien' => route('realisationProjets.index', [
                        'contextKey' => 'realisationProjet.index',
                        'action' => 'show',
                        'id' => $realisationProjet->id
                    ]),
                    'realisationProjet' => $realisationProjet->id
                ],
                type: NotificationType::NOUVEAU_PROJET->value
            );
        }
    }

    /**
     * Met à jour dynamiquement l'état du projet selon l'état de ses tâches.
     *
     * @param RealisationProjet $realisationProjet
     * @return void
     */
    public function mettreAJourEtatDepuisRealisationTaches(RealisationProjet $realisationProjet): void
    {
        if (!$realisationProjet instanceof RealisationProjet) {
            return;
        }

        $realisationProjet->loadMissing('realisationTaches.etatRealisationTache.workflowTache');

        $codesTaches = $realisationProjet->realisationTaches
            ->pluck('etatRealisationTache.workflowTache.code')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($codesTaches)) {
            return;
        }

        $nouvelEtatCode = null;

        // ✅ DONE : toutes les tâches sont DONE
        if (collect($codesTaches)->every(fn($code) => $code === 'APPROVED')) {
            $nouvelEtatCode = 'DONE';

            // ✅ TO_APPROVE : toutes les tâches sont TO_APPROVE ou DONE
        } elseif (collect($codesTaches)->every(fn($code) => in_array($code, ['TO_APPROVE', 'APPROVED']))) {
            $nouvelEtatCode = 'TO_APPROVE';

            // ✅ PAUSED : toutes les tâches sont PAUSED
        } elseif (collect($codesTaches)->every(fn($code) => $code === 'PAUSED')) {
            $nouvelEtatCode = 'PAUSED';

        } elseif (collect($codesTaches)->every(fn($code) => $code === 'TODO')) {
            $nouvelEtatCode = 'TODO';

            // ✅ IN_PROGRESS : au moins une tâche est IN_PROGRESS
        } else {
            $nouvelEtatCode = 'IN_PROGRESS';
        }

        // Appliquer l’état si différent
        if ($nouvelEtatCode) {
            $etat = EtatsRealisationProjet::where('code', $nouvelEtatCode)->first();

            if ($etat && $realisationProjet->etats_realisation_projet_id !== $etat->id) {
                $realisationProjet->etats_realisation_projet_id = $etat->id;
                $realisationProjet->save();
            }
        }
    }

    private function getOrCreateRealisationUa(int $uniteApprentissageId, int $realisationMicroCompetenceId): int
    {
        return RealisationUa::firstOrCreate([
            'unite_apprentissage_id' => $uniteApprentissageId,
            'realisation_micro_competence_id' => $realisationMicroCompetenceId,
        ])->id;
    }

    public function syncApprenantsAvecRealisationProjets($affectationProjet, $nouveauxApprenants)
    {
        $apprenantsExistants = $affectationProjet->realisationProjets->pluck('apprenant_id');

        $apprenantsAJouter = $nouveauxApprenants->whereNotIn('id', $apprenantsExistants);
        $apprenantsASupprimer = $apprenantsExistants->diff($nouveauxApprenants->pluck('id'));

        // Suppression des réalisations obsolètes
        if ($apprenantsASupprimer->isNotEmpty()) {
            $this->model->query()
                ->where('affectation_projet_id', $affectationProjet->id)
                ->whereIn('apprenant_id', $apprenantsASupprimer)
                ->delete();
        }

        // Ajout des nouvelles réalisations
        foreach ($apprenantsAJouter as $apprenant) {
            $this->create([
                'apprenant_id' => $apprenant->id,
                'affectation_projet_id' => $affectationProjet->id,
                'date_debut' => $affectationProjet->date_debut,
                'date_fin' => $affectationProjet->date_fin,
                'rapport' => null,
                'etats_realisation_projet_id' => null,
            ]);
        }
    }

    /**
     * Synchronise les réalisations existantes avec une nouvelle Mobilisation UA.
     * Cette méthode est critique pour maintenir la cohérence lorsqu'une compétence est ajoutée
     * à un projet APRES que des apprenants aient déjà commencé à travailler dessus.
     *
     * Elle effectue les opérations suivantes pour chaque apprenant du projet :
     * 1. S'assure qu'une 'RealisationUa' existe pour lier l'apprenant à la compétence.
     * 2. Crée les entrées de notation 'RealisationUaPrototype' pour les tâches N2 existantes.
     * 3. Crée les entrées de notation 'RealisationUaProjet' pour les tâches N3 existantes.
     *
     * @param int $projetId L'ID du projet modifié.
     * @param MobilisationUa $mobilisation La nouvelle mobilisation ajoutée.
     * @return void
     */
    public function syncRealisationUaAndCompetenceBridges(int $projetId, MobilisationUa $mobilisation): void
    {
        // 1. Récupérer toutes les réalisations liées à ce projet
        $realisationProjets = $this->model->whereHas('affectationProjet', function ($q) use ($projetId) {
            $q->where('projet_id', $projetId);
        })->get();

        $realisationUaService = new RealisationUaService();

        // Créer l'entrée de base "RealisationUa" pour chaque apprenant
        foreach ($realisationProjets as $realisationProjet) {
            $realisationUaService->getOrCreateApprenant(
                $realisationProjet->apprenant_id,
                $mobilisation->unite_apprentissage_id
            );
        }

        // 2. Déléguer la création des ponts (RealisationUaPrototype / RealisationUaProjet) au TacheService
        // On récupère toutes les tâches N2 et N3 du projet
        $phases = \Modules\PkgCompetences\Models\PhaseEvaluation::whereIn('code', ['N2', 'N3'])->pluck('id');

        $taches = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
            ->whereIn('phase_evaluation_id', $phases)
            ->get();

        $tacheService = new \Modules\PkgCreationTache\Services\TacheService();

        foreach ($taches as $tache) {
            $tacheService->syncCompetenceBridges($tache);
        }
    }

    /**
     * Propage la suppression d'une mobilisation d'UA.
     * Cette méthode nettoie les RealisationUaPrototype et RealisationUaProjet orphelins.
     *
     * @param int $projetId
     * @param int $uniteApprentissageId
     * @return void
     */
    public function removeMobilisationFromProjectRealisations(int $projetId, int $uniteApprentissageId): void
    {
        // 1. Récupérer toutes les réalisations du projet avec leurs tâches
        $realisationProjets = $this->model->whereHas('affectationProjet', function ($q) use ($projetId) {
            $q->where('projet_id', $projetId);
        })->with('realisationTaches')->get();

        foreach ($realisationProjets as $realisationProjet) {

            // Trouver la RealisationUa de l'apprenant concerné
            $realisationUA = \Modules\PkgApprentissage\Models\RealisationUa::where('unite_apprentissage_id', $uniteApprentissageId)
                ->whereHas(
                    'realisationMicroCompetence',
                    fn($query) =>
                    $query->where('apprenant_id', $realisationProjet->apprenant_id)
                )->first();

            if (!$realisationUA) {
                continue;
            }

            $realisationTacheIds = $realisationProjet->realisationTaches->pluck('id');

            // Suppression des notes partielles N2
            RealisationUaPrototype::whereIn('realisation_tache_id', $realisationTacheIds)
                ->where('realisation_ua_id', $realisationUA->id)
                ->delete();

            // Suppression des notes partielles N3
            RealisationUaProjet::whereIn('realisation_tache_id', $realisationTacheIds)
                ->where('realisation_ua_id', $realisationUA->id)
                ->delete();
        }
    }
}
