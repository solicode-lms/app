<?php

namespace Modules\Core\App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Modules\PkgGapp\App\Enums\FieldTypeEnum;
trait GappCommands
{

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