<?php


namespace Modules\PkgApprenants\Models;
use Modules\PkgApprenants\Models\Base\BaseApprenant;
use Modules\PkgGestionTaches\Models\RealisationTache;

class Apprenant extends BaseApprenant
{

    public function getFormateurId()
    {
        return optional($this->realisationProjets->first()?->affectationProjet?->projet?->formateur)->id;
    }

    public function __toString()
    {
        return ($this->nom ?? "") . " " . $this->prenom ?? "";
    }

    /**
     * Obtenir le nombre de réalisations de tâches en cours pour cet apprenant.
     *
     * @return int
     */
    public function getNombreRealisationTachesEnCoursAttribute(): int
    {
        return RealisationTache::whereHas('realisationProjet', function ($query) {
                $query->where('apprenant_id', $this->id);
            })
            ->whereHas('etatRealisationTache', function ($q) {
                $q->where('nom', 'En cours'); // Filtrer uniquement les tâches en cours
            })
            ->count();
    }

}
