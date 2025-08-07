<?php


namespace Modules\PkgGapp\Services;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\App\Traits\GappCommands;
use Modules\PkgGapp\Services\Base\BaseEMetadatumService;

/**
 * Classe EMetadatumService pour gérer la persistance de l'entité EMetadatum.
 */
class EMetadatumService extends BaseEMetadatumService
{
    use GappCommands;

    public function dataCalcul($data)
    {


        $eMetadatum = parent::dataCalcul($data);

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

      /**
     * Override de la méthode create
     */
    public function create($data)
    {
        // Appeler la méthode parente pour exécuter l'opération de création
        $metadatum = parent::create($data);

        $this->updateGappCrud($metadatum->eDataField? $metadatum->eDataField->eModel : $metadatum->eModel);

        return $metadatum;
    }

    /**
     * Override de la méthode update
     */
    public function update($id, array $data)
    {

        $metadatum = parent::update($id, $data);
        $this->updateGappCrud($metadatum->eDataField? $metadatum->eDataField->eModel : $metadatum->eModel);
        return $metadatum;
    }

}
