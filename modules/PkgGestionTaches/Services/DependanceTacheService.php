<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Services;
use Modules\PkgGestionTaches\Services\Base\BaseDependanceTacheService;

/**
 * Classe DependanceTacheService pour gérer la persistance de l'entité DependanceTache.
 */
class DependanceTacheService extends BaseDependanceTacheService
{
    public function dataCalcul($dependanceTache)
    {
        // En Cas d'édit
        if(isset($dependanceTache->id)){
          
        }
      
        return $dependanceTache;
    }
   
}
