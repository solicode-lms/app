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



protected function runArtisanInBackground(string $artisanCommand, array $params = [])
{
    $phpPath = "php";
    $artisan = base_path('artisan');

    // Commande de base
    $fullCommand = sprintf(
        '%s %s %s %s',
        escapeshellarg($phpPath),
        escapeshellarg($artisan),
        $artisanCommand,
        implode(' ', array_map('escapeshellarg', $params))
    );

    if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
        // 🚀 Windows : ajouter variables d'environnement Xdebug

    
        // PowerShell + start /B
        $fullCommand = 'powershell -Command "' . $fullCommand . '"';

        $this->executeCommandAsync($fullCommand);
        // $cmd = sprintf('start /B "" %s', $fullCommand);

        
        // pclose(popen($cmd, 'r'));

    } else {
        // 🚀 Linux : simple exécution en arrière-plan
        $cmd = sprintf('%s > /dev/null 2>&1 &', $fullCommand);
        exec($cmd);
    }
}

private function executeCommandAsync($command)
    {
        Log::info("Exécution ASYNCHRONE de la commande : " . $command);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen("start /B {$command} > " . storage_path('logs/async_cmd.log') . " 2>&1", "r"));
        } else {
            shell_exec("{$command} > " . storage_path('logs/async_cmd.log') . " 2>&1 &");
        }
    }
 

}
