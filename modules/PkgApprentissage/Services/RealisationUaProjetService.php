<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaProjetService;

/**
 * Classe RealisationUaProjetService pour gérer la persistance de l'entité RealisationUaProjet.
 */
class RealisationUaProjetService extends BaseRealisationUaProjetService
{
    public function dataCalcul($realisationUaProjet)
    {
        // En Cas d'édit
        if(isset($realisationUaProjet->id)){
          
        }
      
        return $realisationUaProjet;
    }
   
}
