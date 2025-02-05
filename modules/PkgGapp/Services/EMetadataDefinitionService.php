<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Services;
use Modules\PkgGapp\Services\Base\BaseEMetadataDefinitionService;

/**
 * Classe EMetadataDefinitionService pour gérer la persistance de l'entité EMetadataDefinition.
 */
class EMetadataDefinitionService extends BaseEMetadataDefinitionService
{
    public function dataCalcul($eMetadataDefinition)
    {
        // En Cas d'édit
        if(isset($eMetadataDefinition->id)){
          
        }
      
        return $eMetadataDefinition;
    }
}
