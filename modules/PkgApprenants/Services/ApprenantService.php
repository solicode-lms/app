<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Services;
use Modules\PkgApprenants\Services\Base\BaseApprenantService;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class ApprenantService extends BaseApprenantService
{
    public function dataCalcul($apprenant)
    {
        // En Cas d'édit
        if(isset($apprenant->id)){
          
        }
      
        return $apprenant;
    }
}
