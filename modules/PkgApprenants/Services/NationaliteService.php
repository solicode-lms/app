<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Services;
use Modules\PkgApprenants\Services\Base\BaseNationaliteService;

/**
 * Classe NationaliteService pour gérer la persistance de l'entité Nationalite.
 */
class NationaliteService extends BaseNationaliteService
{
    public function dataCalcul($nationalite)
    {
        // En Cas d'édit
        if(isset($nationalite->id)){
          
        }
      
        return $nationalite;
    }
   
}
