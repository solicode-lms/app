<?php

namespace Modules\Core\Services;

use Illuminate\Support\Facades\Log;

class TraitementAsyncService
{
    /**
     * Lance une commande Artisan en arrière-plan (multi-OS).
     */
    public function runArtisanInBackground(string $artisanCommand, array $params = [])
    {
        $phpPath   = 'php'; // Ou PHP_BINARY si CLI configuré
        $artisan   = base_path('artisan');
        $arguments = implode(' ', array_map('escapeshellarg', $params));

        $fullCommand = sprintf(
            '%s %s %s %s',
            escapeshellarg($phpPath),
            escapeshellarg($artisan),
            $artisanCommand,
            $arguments
        );

        if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
            // Windows : PowerShell + démarrage en arrière-plan
            $fullCommand = 'powershell -Command "' . $fullCommand . '"';
            $this->executeCommandAsync($fullCommand);
        } else {
            // Linux/Mac : démarrage en arrière-plan avec redirection
            exec(sprintf('%s > /dev/null 2>&1 &', $fullCommand));
        }
    }

    /**
     * Exécute une commande système en arrière-plan et loggue la sortie.
     */
    public function executeCommandAsync(string $command)
    {
        $logFile = storage_path('logs/async_cmd.log');
        Log::info("Exécution ASYNCHRONE de la commande : " . $command);

        if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
            pclose(popen("start /B {$command} > {$logFile} 2>&1", "r"));
        } else {
            shell_exec("{$command} > {$logFile} 2>&1 &");
        }
    }
}
