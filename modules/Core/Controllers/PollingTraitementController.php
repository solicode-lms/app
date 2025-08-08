<?php

namespace Modules\Core\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\Core\Controllers\Base\AdminController;

class PollingTraitementController extends AdminController
{
 

    /**
     * @DynamicPermissionIgnore
     */
    public function start()
    {
        try {

            
            set_time_limit(0); // ⏳ Aucune limite de temps
            // ini_set('max_execution_time', 120); // en secondes

            // Lancer tous les jobs en attente via queue:work --once dans une boucle
                while (true) {
                    // Exécute un job
                    Artisan::call('queue:work', [
                        '--once' => true,
                        '--queue' => 'default',
                        '--tries' => 3,
                    ]);

                    // Sortir si plus de jobs (table jobs vide)
                    if (DB::table('jobs')->count() === 0) {
                        break;
                    }

                    // Petite pause pour ne pas saturer le CPU
                    usleep(200000); // 200 ms
                }
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('[Traitement Async] Erreur lors de queue:work --once : ' . $e->getMessage());
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

        return response()->json([
            'status' => $status,
            'progress' => $progress, 
            'messageError' => $messageError
        ]);
    }

}
