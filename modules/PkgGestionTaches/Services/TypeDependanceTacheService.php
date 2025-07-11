<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Services;
use Modules\PkgRealisationTache\Services\Base\BaseTypeDependanceTacheService;

/**
 * Classe TypeDependanceTacheService pour gérer la persistance de l'entité TypeDependanceTache.
 */
class TypeDependanceTacheService extends BaseTypeDependanceTacheService
{
    public function dataCalcul($typeDependanceTache)
    {
        // En Cas d'édit
        if(isset($typeDependanceTache->id)){
          
        }
      
        return $typeDependanceTache;
    }
   
}
