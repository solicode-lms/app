<?php
 

namespace Modules\PkgGestionTaches\Models;

use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgGestionTaches\Models\Base\BaseRealisationTache;
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;

class RealisationTache extends BaseRealisationTache
{
   
   /**
 * Récupérer les réalisations des livrables associés à la tâche de cette réalisation,
 * uniquement pour l'apprenant lié à cette RealisationTache.
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
public function getRealisationLivrable()
{
    return LivrablesRealisation::whereHas('livrable', function ($query) {
            $query->whereHas('taches', function ($q) {
                $q->where('id', $this->tache_id);
            });
        })
        ->whereHas('realisationProjet', function ($q) {
            $q->where('apprenant_id', $this->realisationProjet->apprenant_id);
        })
        ->get();
}

    public function __toString()
    {
        return ($this->tache?->titre ?? "") .  " - ". $this->realisationProjet?->apprenant ?? "";
    }

}
