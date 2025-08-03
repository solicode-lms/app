<?php

namespace Modules\PkgApprentissage\Services;

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
     * Règles exécutées après mise à jour d’un chapitre.
     */
    public function afterUpdateRules($entity): void
    {
        if ($entity->wasChanged('etat_realisation_chapitre_id')) {
             $this->synchroniserEtatTacheLiee($entity);
             $this->recalculerProgressionEtNoteUa($entity);
        }

      
       
    }

    /**
     * Règle 1 — Synchroniser l’état de la tâche liée (si existe).
     */
    private function synchroniserEtatTacheLiee($entity): void
    {
        if (! $entity->realisation_tache_id) {
            return;
        }

        $realisationTache = RealisationTache::find($entity->realisation_tache_id);
        if (! $realisationTache) {
            return;
        }

        $etatTache = $this->mapEtatChapitreToEtatTache($entity->etat_realisation_chapitre_id, $realisationTache->realisationProjet->affectationProjet->projet->formateur_id);
        $realisationTacheService = new RealisationTacheService();
        if ($etatTache &&  $realisationTache->etat_realisation_tache_id != $etatTache->id) {
            $realisationTacheService->update($realisationTache->id,[
                'etat_realisation_tache_id' => $etatTache->id,
            ]);
        }
    }

    /**
     * Règle 2 — Recalculer la note et la progression de l’UA.
     */
    private function recalculerProgressionEtNoteUa($entity): void
    {
        if (! $entity->realisationUa) {
            return;
        }

        $service = new RealisationUaService();
        $service->calculerProgressionEtNote($entity->realisationUa);
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
            'DONE'           => 'DONE',
            'BLOCKED' => 'BLOCKED'
        ];

        $codeTache = $mapping[$etatChapitre->code] ?? null;
        if (! $codeTache) {
            return null;
        }

        $etatTacheService = new EtatRealisationTacheService();
        return $etatTacheService->findByFormateurIdAndWorkflowCode($formateurId, $codeTache);

    }
}
