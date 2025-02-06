<?php


namespace Modules\PkgGapp\Services;

use Modules\PkgGapp\App\Enums\FieldTypeEnum;
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
          


        }else{
            if( isset($eMetadatum->e_metadata_definition_id)){
                $metadataDefinition = (new EMetadataDefinitionService())
                ->find($eMetadatum->e_metadata_definition_id);
                if($metadataDefinition->type == "Json"){
                    $eMetadatum->value_json = $metadataDefinition->default_value;
                   
                }
               

            }
        }
      
        return $eMetadatum;
    }
}
