<?php


namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\PkgApprentissage\Services\Base\BaseRealisationChapitreService;

/**
 * Classe RealisationChapitreService pour gérer la persistance de l'entité RealisationChapitre.
 */
class RealisationChapitreService extends BaseRealisationChapitreService
{

      /**
     * Synchronise l'état de la tâche associée si l'état du chapitre change.
     *
     * @param \Modules\PkgRealisationChapitres\Models\RealisationChapitre $entity
     * @return void
     */
    public function afterUpdateRules($entity): void
    {
        if ($entity->wasChanged('etat_realisation_chapitre_id')) {
            // Trouver la tâche associée
            if ($entity->realisation_tache_id) {
                $realisationTache = RealisationTache::find($entity->realisation_tache_id);
                if ($realisationTache) {
                    $etatTache = $this->mapEtatChapitreToEtatTache($entity->etat_realisation_chapitre_id);
                    if ($etatTache) {
                        $realisationTache->update([
                            'etat_realisation_tache_id' => $etatTache->id
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Mapping entre les états chapitre et tâche.
     *
     * @param int $etatChapitreId
     * @return \Modules\PkgRealisationTache\Models\EtatRealisationTache|null
     */
    private function mapEtatChapitreToEtatTache(int $etatChapitreId)
    {
        $etatChapitre = EtatRealisationChapitre::find($etatChapitreId);

        if (!$etatChapitre) {
            return null;
        }

        // Tableau de mapping explicite
        $mapping = [
            'TODO'         => 'A_FAIRE',
            'IN_PROGRESS'  => 'EN_COURS',
            'PAUSED'       => 'EN_PAUSE',
            'IN_REVIEW'    => 'REVISION_NECESSAIRE',
            'TO_APPROVE'   => 'EN_VALIDATION',
            'DONE'         => 'TERMINEE',
            // READY_FOR_LIVE ou BLOCKED peuvent être mappés sur un état par défaut
            'READY_FOR_LIVE' => 'EN_COURS', 
            'BLOCKED'        => 'EN_PAUSE'
        ];

        $codeTache = $mapping[$etatChapitre->code] ?? null;
        if (!$codeTache) {
            return null;
        }

        return \Modules\PkgRealisationTache\Models\EtatRealisationTache::whereHas('workflowTache', function ($q) use ($codeTache) {
            $q->where('code', $codeTache);
        })->first();
    }



    public function dataCalcul($realisationChapitre)
    {
        // En Cas d'édit
        if(isset($realisationChapitre->id)){
          
        }
      
        return $realisationChapitre;
    }
   
}
