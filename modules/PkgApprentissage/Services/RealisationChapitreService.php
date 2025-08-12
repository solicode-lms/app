<?php

namespace Modules\PkgApprentissage\Services;

use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\PkgApprentissage\Services\Base\BaseRealisationChapitreService;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;

/**
 * Service métier pour la gestion des RealisationChapitre.
 */
class RealisationChapitreService extends BaseRealisationChapitreService
{
    
    /**
     * Job déclenché après mise à jour d’un chapitre.
     */
    public function updatedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token);
        $realisationChapitre = $this->find($id);

        if (! $realisationChapitre) {
            return;
        }

        // 🔹 Si l'état du chapitre a changé
        if ($jobManager->isDirty('etat_realisation_chapitre_id')) {
            if ($realisationChapitre->realisationTache) {
                $jobManager->setLabel("Mise à jour état tâche depuis chapitre");
                $this->modifierEtatRealisationTache($realisationChapitre);
                $jobManager->tick();
            } else {
                $jobManager->setLabel("Calcul progression UA depuis chapitre");
                (new RealisationUaService())->calculerProgression($realisationChapitre->realisationUa);
                $jobManager->tick();
            }
        }
    }

    /**
     * Recalculer la progression pour une liste d'IDs de RealisationChapitre.
     *
     * @param \Illuminate\Support\Collection|array $realisationChapitresIds
     * @return void
     */
    public function calculerProgressionDepuisRealisationChapitresIds($realisationChapitresIds): void
    {
        $realisationChapitresIds = collect($realisationChapitresIds)->filter()->unique();

        if ($realisationChapitresIds->isEmpty()) {
            return;
        }

        $realisationChapitres = $this->newQuery()
            ->whereIn('id', $realisationChapitresIds)
            ->with(['realisationTache', 'realisationUa'])
            ->get();

        foreach ($realisationChapitres as $realisationChapitre) {
            $realisationTache = $realisationChapitre->realisationTache;

            if ($realisationTache) {
                // 🔁 Mapper et synchroniser l’état de chapitre à partir de l’état de tâche
                $etatChapitre = $this->mapEtatTacheToEtatChapitre($realisationTache->etat_realisation_tache_id);

                if ($etatChapitre && $realisationChapitre->etat_realisation_chapitre_id !== $etatChapitre->id) {
                    $realisationChapitre->etat_realisation_chapitre_id = $etatChapitre->id;
                    $realisationChapitre->saveQuietly(); // ⚠️ éviter les observers
                }
            }

            // 🔁 Recalcul de la progression de l’UA associée
            if ($realisationChapitre->realisationUa) {
                $uaService = new RealisationUaService();
                $uaService->calculerProgression($realisationChapitre->realisationUa);
            }
        }
    }

    /**
     * Calculer la progression après la modification de réalisation de tâche.
     *
     * @param \Modules\PkgRealisationTache\Models\RealisationTache $realisationTache
     * @return void
     */
    public function calculerProgression($realisationTache)
    {
        if ($realisationTache->realisationChapitres->isEmpty()) {
            return;
        }

        foreach ($realisationTache->realisationChapitres as $realisationChapitre) {

            // 🔹 Mapper l'état de tâche vers état de chapitre
            $etatChapitre = $this->mapEtatTacheToEtatChapitre($realisationTache->etat_realisation_tache_id);

            // 🔹 Mise à jour silencieuse pour éviter de déclencher des observers
            if ($etatChapitre && $realisationChapitre->etat_realisation_chapitre_id !== $etatChapitre->id) {
                $realisationChapitre->etat_realisation_chapitre_id = $etatChapitre->id;
                $realisationChapitre->saveQuietly();
            }

            // Calcule de progression 
            $realisationUaService = new RealisationUaService();
            $realisationUaService->calculerProgression($realisationChapitre->realisationUa);
        }
    }




    /**
     * La modification de réalisation de chapitre
     */
    private function modifierEtatRealisationTache($realisationChapitre): void
    {
        if (! $realisationChapitre->realisation_tache_id) {
            return;
        }

        $realisationTache = RealisationTache::find($realisationChapitre->realisation_tache_id);
        if (! $realisationTache) {
            return;
        }

        $etatTache = $this->mapEtatChapitreToEtatTache($realisationChapitre->etat_realisation_chapitre_id, $realisationTache->realisationProjet->affectationProjet->projet->formateur_id);
        $realisationTacheService = new RealisationTacheService();
        if ($etatTache &&  $realisationTache->etat_realisation_tache_id != $etatTache->id) {
            $realisationTacheService->update($realisationTache->id,[
                'etat_realisation_tache_id' => $etatTache->id,
            ]);
        }
    }


    /**
     * Règle de mapping : état chapitre → état tâche.
     */
    private function mapEtatChapitreToEtatTache(int $etatChapitreId, int $formateurId = null)
    {
        $etatChapitre = EtatRealisationChapitre::find($etatChapitreId);
        if (! $etatChapitre) {
            return null;
        }

        $mapping = [
            'TODO'           => 'TODO',
            'IN_PROGRESS'    => 'IN_PROGRESS',
            'PAUSED'         => 'EN_PAUSE',
            'READY_FOR_LIVE_CODING' => 'READY_FOR_LIVE_CODING',
            'IN_LIVE_CODING'      => 'IN_LIVE_CODING',
            'TO_APPROVE'     => 'TO_APPROVE',
            'DONE'           => 'APPROVED',
        ];

        $codeTache = $mapping[$etatChapitre->code] ?? null;
        if (! $codeTache) {
            return null;
        }

        $etatTacheService = new EtatRealisationTacheService();
        return $etatTacheService->findByFormateurIdAndWorkflowCode($formateurId, $codeTache);

    }

       /**
     * Mapper un état de tâche à un état de chapitre
     */
    private function mapEtatTacheToEtatChapitre(int $etatTacheId)
    {
        $etatTache = EtatRealisationTache::with('workflowTache')->find($etatTacheId);

        if (!$etatTache || !$etatTache->workflowTache) {
            return null;
        }

        // Table de mapping entre les codes
        $mapping = [
            'TODO'            => 'TODO',
            'IN_PROGRESS'           => 'IN_PROGRESS',
            'PAUSED'           => 'PAUSED',
            'REVISION_NECESSAIRE'=> 'IN_PROGRESS',
            'READY_FOR_LIVE_CODING' => 'READY_FOR_LIVE_CODING',
            'IN_LIVE_CODING' => 'IN_LIVE_CODING',
            'TO_APPROVE'      => 'TO_APPROVE',
            'APPROVED'           => 'DONE'
        ];

        $codeChapitre = $mapping[$etatTache->workflowTache->code] ?? null;

        if (!$codeChapitre) {
            return null;
        }

        return EtatRealisationChapitre::where('code', $codeChapitre)->first();
    }
}
