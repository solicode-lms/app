<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Services;
use Modules\PkgApprenants\Services\Base\BaseVilleService;

/**
 * Classe VilleService pour gérer la persistance de l'entité Ville.
 */
class VilleService extends BaseVilleService
{
    public function dataCalcul($ville)
    {
        // En Cas d'édit
        if(isset($ville->id)){
          
        }
      
        return $ville;
    }
   
}
