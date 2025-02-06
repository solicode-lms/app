<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Services;
use Modules\PkgGapp\Services\Base\BaseEPackageService;

/**
 * Classe EPackageService pour gérer la persistance de l'entité EPackage.
 */
class EPackageService extends BaseEPackageService
{
    public function dataCalcul($ePackage)
    {
        // En Cas d'édit
        if(isset($ePackage->id)){
          
        }
      
        return $ePackage;
    }
}
