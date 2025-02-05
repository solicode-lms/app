<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Services;
use Modules\PkgGapp\Services\Base\BaseEMetadatumService;

/**
 * Classe EMetadatumService pour gérer la persistance de l'entité EMetadatum.
 */
class EMetadatumService extends BaseEMetadatumService
{
    public function dataCalcul($eMetadatum)
    {
        // En Cas d'édit
        if(isset($eMetadatum->id)){
          
        }
      
        return $eMetadatum;
    }
}
