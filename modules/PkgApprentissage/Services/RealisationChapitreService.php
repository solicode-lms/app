<?php


namespace Modules\PkgApprentissage\Services;

use Illuminate\Support\Collection;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Modules\PkgApprentissage\Models\RealisationUa;
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


             // Synchronisation de la tâche liée
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

             // Mise à jour de l'état de la RealisationUa
            if ($entity->realisation_ua_id) {
                $realisationUaService = new RealisationUaService();
                $realisationUa = RealisationUa::with('realisationChapitres')->find($entity->realisation_ua_id);

                if ($realisationUa) {
                    $nouvelEtatCode = $this->calculerEtatUaDepuisChapitres($realisationUa->realisationChapitres);

                    if ($nouvelEtatCode) {
                        $etat = EtatRealisationUa::where('code', $nouvelEtatCode)->first();
                        if ($etat && $realisationUa->etat_realisation_ua_id !== $etat->id) {
                            $realisationUaService->update($realisationUa->id, [
                                'etat_realisation_ua_id' => $etat->id
                            ]);
                        }
                    }
                }
            }

        }
    }


    private function calculerEtatUaDepuisChapitres(Collection $chapitres): ?string
    {
        if ($chapitres->isEmpty()) {
            return 'TODO';
        }

        // On extrait les codes des états
        $etatCodes = $chapitres->pluck('etatRealisationChapitre.code')->filter();

        // 🎯 Cas 1 : au moins un chapitre a commencé → IN_PROGRESS_CHAPITRE
        if ($etatCodes->contains('IN_PROGRESS')) {
            return 'IN_PROGRESS_CHAPITRE';
        }

        // 🎯 Cas 2 : tous les chapitres sont terminés (== DONE) → IN_PROGRESS_PROTOTYPE
        $tousDone = $chapitres->every(fn($chap) => optional($chap->etatRealisationChapitre)->code === 'DONE');
        if ($tousDone) {
            return 'IN_PROGRESS_PROTOTYPE';
        }

        // Aucun changement déclencheur → état inchangé
        return null;
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
