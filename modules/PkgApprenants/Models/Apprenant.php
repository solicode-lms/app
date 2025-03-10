<?php


namespace Modules\PkgApprenants\Models;

use Modules\Core\App\Traits\HasDynamicAttributes;
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
    // public function getNombreRealisationTachesEnCoursAttribute(): int
    // {
    //     return $this->queryRealisationTachesEnCours()->count();
    // }

    /**
     * Construire la requête pour récupérer les tâches en cours
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function queryRealisationTachesEnCours()
    // {
    //     return RealisationTache::whereHas('realisationProjet', function ($query) {
    //             $query->where('apprenant_id', $this->id);
    //         })
    //         ->whereHas('etatRealisationTache', function ($q) {
    //             $q->where('nom', 'En cours'); // Filtrer uniquement les tâches en cours
    //         });


    //         // $subQuery->whereHas('realisationTaches', function ($q) {
    //         //     $q->whereHas('etatRealisationTache', function ($etat) {
    //         //         $etat->where('nom', 'En cours'); // Filtrer uniquement les tâches en cours
    //         //     });
    //         // });
    // }

}
