<?php

namespace Modules\Core\App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

trait GappCommands
{
    /**
     * Chemin de Gapp installé globalement pour l'utilisateur essarraj.
     */
    private function getGappPath(): string
    {
        // On récupère le chemin depuis le .env, par défaut on tente la commande globale 'gapp.cmd'
        $path = env('GAPP_PATH', 'gapp.cmd');

        // On ne vérifie l'existence du fichier que si ce n'est pas une commande globale simple
        if ($path !== 'gapp' && $path !== 'gapp.cmd' && !file_exists($path)) {
            throw new \RuntimeException(
                "Gapp introuvable au chemin spécifié : {$path}"
            );
        }

        return $path;
    }


    /**
     * Construit une commande Gapp.
     */
    private function buildGappCommand(array $arguments): array
    {
        return array_merge(
            [$this->getGappPath()],
            $arguments
        );
    }

    /**
     * Export des métadonnées.
     */
    private function metaExport(bool $sync = false): void
    {
        $message = 'Export metaData en cours ..';

        $command = $this->buildGappCommand([
            'meta:export',
            '.',
        ]);

        $this->pushServiceMessage(
            'success',
            'Gapp',
            $message
        );

        if ($sync) {
            $this->executeCommandSync(
                $command,
                'Gapp meta:export'
            );
        } else {
            $this->executeCommandSync(
                $command,
                'Gapp meta:export'
            );
        }
    }

    /**
     * Seed des métadonnées.
     */
    private function metaSeed(bool $sync = false): void
    {
        $message = 'Seed metaData en cours ..';

        $command = $this->buildGappCommand([
            'meta:seed',
            '.',
        ]);

        $this->pushServiceMessage(
            'success',
            'Gapp',
            $message
        );

        if ($sync) {
            $this->executeCommandSync(
                $command,
                'Gapp meta:seed'
            );
        } else {
            $this->executeCommandSync(
                $command,
                'Gapp meta:seed'
            );
        }
    }

    /**
     * Seed d'un DataField.
     */
    private function metaSeedByDataFieldReference(
        string $dataFieldReference,
        bool $sync = false
    ): void {
        $message = 'Seed metaData en cours ..';

        $command = $this->buildGappCommand([
            'meta:seedDataField',
            '.',
            $dataFieldReference,
        ]);

        $this->pushServiceMessage(
            'success',
            'Gapp',
            $message
        );

        if ($sync) {
            $this->executeCommandSync(
                $command,
                'Gapp meta:seedDataField'
            );
        } else {
            $this->executeCommandSync(
                $command,
                'Gapp meta:seedDataField'
            );
        }
    }

    /**
     * Génère le CRUD puis exporte les métadonnées.
     */
    private function updateGappCrud($model): void
    {
        if (!$model) {
            Log::error(
                'Impossible de générer le CRUD : modèle non défini.'
            );

            return;
        }

        $modelName = $model->name;

        $message =
            "Génération du CRUD pour {$modelName} en cours ..";

        $this->pushServiceMessage(
            'success',
            'Gapp',
            $message
        );

        $makeCrudCommand = $this->buildGappCommand([
            'make:crud',
            $modelName,
            '.',
        ]);

        $metaExportCommand = $this->buildGappCommand([
            'meta:export',
            '.',
        ]);

        /*
         * Le CRUD doit terminer avant l'export.
         */
        $success = $this->executeCommandSync(
            $makeCrudCommand,
            "Gapp make:crud {$modelName}"
        );

        if ($success) {
            $this->executeCommandSync(
                $metaExportCommand,
                'Gapp meta:export'
            );
        }
    }

    /**
     * Exécution SYNCHRONE.
     */
    private function executeCommandSync(
        array $command,
        string $logMessage = ''
    ): bool {
        try {
            Log::info(
                'Exécution SYNCHRONE Gapp',
                [
                    'command' => $command,
                    'message' => $logMessage,
                    'path' => base_path(),
                ]
            );

            $result = Process::path(base_path())
                // ->env($this->getGappEnvironment())
                ->timeout(300)
                ->run($command);

            Log::info(
                'Résultat commande Gapp',
                [
                    'exit_code' => $result->exitCode(),
                    'output' => $result->output(),
                    'error' => $result->errorOutput(),
                ]
            );

            if ($result->successful()) {
                return true;
            }

            Log::error(
                'Échec commande Gapp',
                [
                    'command' => $command,
                    'exit_code' => $result->exitCode(),
                    'output' => $result->output(),
                    'error' => $result->errorOutput(),
                ]
            );

            return false;

        } catch (\Throwable $e) {

            Log::error(
                'Exception Gapp',
                [
                    'command' => $command,
                    'error' => $e->getMessage(),
                ]
            );

            return false;
        }
    }
}
