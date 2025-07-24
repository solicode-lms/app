<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaPrototypeService;

/**
 * Classe RealisationUaPrototypeService pour gérer la persistance de l'entité RealisationUaPrototype.
 */
class RealisationUaPrototypeService extends BaseRealisationUaPrototypeService
{
    public function dataCalcul($realisationUaPrototype)
    {
        // En Cas d'édit
        if(isset($realisationUaPrototype->id)){
          
        }
      
        return $realisationUaPrototype;
    }
   
}
