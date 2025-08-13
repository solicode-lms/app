<?php

namespace Modules\Core\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\Core\App\Jobs\RunQueueJobs;
use Modules\Core\Controllers\Base\AdminController;

class PollingTraitementController extends AdminController
{
 

    /**
     * @DynamicPermissionIgnore
     */
    public function start()
    {
        try {
            $token = Str::uuid()->toString();

            // Initialiser le statut
            Cache::put("traitement.$token.status", 'in_progress');
            Cache::put("traitement.$token.progress", 0);

            // Lancer la commande Artisan en arrière-plan
            $this->runArtisanInBackground('traitement:run', [$token]);

            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (\Throwable $e) {
            Log::error('[Traitement Async] Erreur : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }



     /**
     * Vérifie l’état d’un traitement via son token
     * @DynamicPermissionIgnore
     */
    public function status(string $token)
    {
        $status = Cache::get("traitement.$token.status", 'unknown');
        $progress = Cache::get("traitement.$token.progress", 0);
        $messageError = Cache::get("traitement.$token.messageError", 0);
        $label = Cache::get("traitement.$token.label", "⏳ Traitement en cours...");

        return response()->json([
            'status' => $status,
            'progress' => $progress, 
            'messageError' => $messageError,
            'label' => $label
        ]);
    }



 /**
     * Lance une commande Artisan en arrière-plan (multi-OS).
     */
    protected function runArtisanInBackground(string $artisanCommand, array $params = [])
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
    private function executeCommandAsync(string $command)
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
