<?php

namespace Modules\PkgRealisationProjets\Services\Traits\AffectationProjet;

use Carbon\Carbon;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;

trait AffectationProjetCalculTrait
{
    /**
     * Détermine la date de lancement officiel du projet pour l'affectation.
     * Si non renseignée, elle est déduite du premier démarrage (IN_PROGRESS)
     * ou fixée au jour de début à 09:00.
     *
     * @param AffectationProjet $affectation
     * @return Carbon
     */
    public function determinerDateLancement(AffectationProjet $affectation): Carbon
    {
        if ($affectation->cache_date_lancement) {
            return Carbon::parse($affectation->cache_date_lancement);
        }

        // Recherche de la première tâche passée en "IN_PROGRESS" par un apprenant du groupe
        $premierDemarrage = HistoriqueRealisationTache::whereHas('realisationTache.realisationProjet', function ($q) use ($affectation) {
                $q->where('affectation_projet_id', $affectation->id);
            })
            ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                $q->where('code', 'IN_PROGRESS');
            })
            ->min('created_at');

        $dateLancement = $premierDemarrage 
            ? Carbon::parse($premierDemarrage)
            : ($affectation->date_debut ? Carbon::parse($affectation->date_debut)->setTime(9, 0, 0) : Carbon::parse($affectation->created_at));

        $affectation->update(['cache_date_lancement' => $dateLancement]);

        return $dateLancement;
    }
}
