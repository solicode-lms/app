<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Services;
use Modules\PkgApprenants\Services\Base\BaseSousGroupeService;

/**
 * Classe SousGroupeService pour gérer la persistance de l'entité SousGroupe.
 */
class SousGroupeService extends BaseSousGroupeService
{
    public function dataCalcul($sousGroupe)
    {
        // En Cas d'édit
        if(isset($sousGroupe->id)){
          
        }
      
        return $sousGroupe;
    }
   
}
