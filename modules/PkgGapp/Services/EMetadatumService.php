<?php


namespace Modules\PkgGapp\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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
        $metadatum = parent::create($data);

        // Afficher la version de Node.js dans la console
        $this->updateGappCrud($metadatum->eDataField? $metadatum->eDataField->eModel : $metadatum->eModel);

        return $metadatum;
    }

    /**
     * Override de la méthode update
     */
    public function update($id, array $data): ?Model 
    {

        $metadatum = parent::update($id, $data);
        $this->updateGappCrud($metadatum->eDataField? $metadatum->eDataField->eModel : $metadatum->eModel);
        return $metadatum;
    }

    private function updateGappCrud($model)
    {
        if (!$model) {
            Log::error("Impossible de générer le CRUD : modèle non défini.");
            return;
        }

        $modelName = $model->name;
        $message = "Génération du CRUD pour {$modelName} en cours ..";
        $makeCrudCommand = "gapp make:crud {$modelName} ../";
        $metaExportCommand = "gapp meta:export ../";

        $this->pushServiceMessage("info","Gapp", $message);
        dd($message);
        // Exécution SYNCHRONE du CRUD
        $this->executeCommandAsync($makeCrudCommand);

        // Exécution ASYNCHRONE de l'export des métadonnées
        $this->executeCommandAsync($metaExportCommand);
    }
   

     /**
     * Exécute une commande en mode synchrone
     */
    private function executeCommandSync($command, $logMessage)
    {
        Log::info("Exécution SYNCHRONE : " . $logMessage);

        $output = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
            ? shell_exec($command . " 2>&1") 
            : shell_exec($command . " 2>&1");

        if (!empty($output)) {
            Log::info("Sortie de la commande :\n" . trim($output));
        } else {
            Log::error("La commande n'a retourné aucune sortie : " . $command);
        }
    }

    /**
     * Exécute une commande en mode asynchrone
     */
    private function executeCommandAsync($command)
    {
        Log::info("Exécution ASYNCHRONE de la commande : " . $command);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen("start /B " . $command, "r"));
        } else {
            shell_exec($command . " > /dev/null 2>&1 &");
        }
    }

    /**
     * Met à jour le CRUD et lance l'export des métadonnées
     */

}
