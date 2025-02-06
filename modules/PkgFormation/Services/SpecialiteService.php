<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Services;
use Modules\PkgFormation\Services\Base\BaseSpecialiteService;

/**
 * Classe SpecialiteService pour gérer la persistance de l'entité Specialite.
 */
class SpecialiteService extends BaseSpecialiteService
{
    public function dataCalcul($specialite)
    {
        // En Cas d'édit
        if(isset($specialite->id)){
          
        }
      
        return $specialite;
    }
}
