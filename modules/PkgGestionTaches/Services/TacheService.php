<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Services;
use Modules\PkgGestionTaches\Services\Base\BaseTacheService;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 */
class TacheService extends BaseTacheService
{
    public function dataCalcul($tache)
    {
        // En Cas d'édit
        if(isset($tache->id)){
          
        }
      
        return $tache;
    }
   
}
