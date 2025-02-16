<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Services;
use Modules\PkgGapp\Services\Base\BaseEDataFieldService;

/**
 * Classe EDataFieldService pour gérer la persistance de l'entité EDataField.
 */
class EDataFieldService extends BaseEDataFieldService
{
    public function dataCalcul($eDataField)
    {
        // En Cas d'édit
        if(isset($eDataField->id)){
          
        }
      
        return $eDataField;
    }
   
}
