<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\PkgApprentissage\Services\Base\BaseRealisationChapitreService;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;

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
        if (! $entity->wasChanged('etat_realisation_chapitre_id')) {
            return;
        }

        $this->synchroniserEtatTacheLiee($entity);
        $this->recalculerProgressionEtNoteUa($entity);
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

        $etatTache = $this->mapEtatChapitreToEtatTache($entity->etat_realisation_chapitre_id);
        if ($etatTache) {
            $realisationTache->update([
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
    private function mapEtatChapitreToEtatTache(int $etatChapitreId)
    {
        $etatChapitre = EtatRealisationChapitre::find($etatChapitreId);
        if (! $etatChapitre) {
            return null;
        }

        $mapping = [
            'TODO'           => 'A_FAIRE',
            'IN_PROGRESS'    => 'EN_COURS',
            'PAUSED'         => 'EN_PAUSE',
            'IN_REVIEW'      => 'REVISION_NECESSAIRE',
            'TO_APPROVE'     => 'EN_VALIDATION',
            'DONE'           => 'TERMINEE',
            'READY_FOR_LIVE' => 'EN_COURS',
            'BLOCKED'        => 'EN_PAUSE',
        ];

        $codeTache = $mapping[$etatChapitre->code] ?? null;
        if (! $codeTache) {
            return null;
        }

        return EtatRealisationTache::whereHas('workflowTache', function ($q) use ($codeTache) {
            $q->where('code', $codeTache);
        })->first();
    }
}
