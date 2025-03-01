<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Services;
use Modules\PkgGestionTaches\Services\Base\BasePrioriteTacheService;

/**
 * Classe PrioriteTacheService pour gérer la persistance de l'entité PrioriteTache.
 */
class PrioriteTacheService extends BasePrioriteTacheService
{
    public function dataCalcul($prioriteTache)
    {
        // En Cas d'édit
        if(isset($prioriteTache->id)){
          
        }
      
        return $prioriteTache;
    }
   
}
