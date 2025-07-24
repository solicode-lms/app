<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaService;

/**
 * Classe RealisationUaService pour gérer la persistance de l'entité RealisationUa.
 */
class RealisationUaService extends BaseRealisationUaService
{
    public function dataCalcul($realisationUa)
    {
        // En Cas d'édit
        if(isset($realisationUa->id)){
          
        }
      
        return $realisationUa;
    }
   
}
