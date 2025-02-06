<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Services;
use Modules\PkgRealisationProjets\Services\Base\BaseRealisationProjetService;

/**
 * Classe RealisationProjetService pour gérer la persistance de l'entité RealisationProjet.
 */
class RealisationProjetService extends BaseRealisationProjetService
{
    public function dataCalcul($realisationProjet)
    {
        // En Cas d'édit
        if(isset($realisationProjet->id)){
          
        }
      
        return $realisationProjet;
    }
}
