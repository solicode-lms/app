<?php


namespace Modules\PkgGapp\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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

      /**
     * Override de la méthode create
     */
    public function create($data)
    {
        // Appeler la méthode parente pour exécuter l'opération de création
        $result = parent::create($data);

        // Afficher la version de Node.js dans la console
        $this->logNodeVersion("Création d'une nouvelle entité avec ID: " . $result->id);

        return $result;
    }

    /**
     * Override de la méthode update
     */
    public function update($id, array $data): ?Model 
    {
        // Appeler la méthode parente pour exécuter l'opération de mise à jour
        $result = parent::update($id, $data);

        // Afficher la version de Node.js dans la console
        $this->logNodeVersion("Mise à jour de l'entité avec ID: " . $id);

        return $result;
    }

   

    /**
     * Exécute une commande Node.js pour afficher la version dans la console.
     */
    private function logNodeVersion($message)
    {

        $model_name = "Competence";
        Log::info("Génération de CRUD pour la model : {$model_name}");
        $nodeCommand = "gapp make:crud Competence ../";

        // Exécuter la commande (compatible Windows et Linux)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen('start /B ' . $nodeCommand, 'r'));
        } else {
            shell_exec($nodeCommand . ' > /dev/null 2>&1 &');
        }
    }
}
