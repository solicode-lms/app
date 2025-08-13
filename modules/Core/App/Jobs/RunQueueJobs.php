<?php


// app/Jobs/RunQueueJobs.php
namespace Modules\Core\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job maître pour exécuter les jobs en attente dans la queue Laravel.
 *
 * 📌 Problème initial :
 * - L'ancienne implémentation exécutait Artisan::call('queue:work --once') 
 *   dans une boucle directement dans la requête HTTP (ex: /start).
 * - Cela gardait la connexion HTTP ouverte pendant tout le traitement.
 * - Sous Nginx + FastCGI, cela bloquait les autres requêtes AJAX (surtout 
 *   si elles utilisaient la même session PHP) et saturait les workers php-fpm.
 *
 * 💡 Solution :
 * - Déplacer l'exécution longue dans un Job Laravel asynchrone.
 * - La route /start ne fait plus que planifier ce job et répondre immédiatement.
 * - La progression est sauvegardée dans le cache (Redis, etc.) pour 
 *   que la route /status puisse être interrogée en AJAX (polling).
 *
 * ✅ Avantages :
 * - Plus de blocage des autres requêtes AJAX.
 * - Respect des bonnes pratiques Laravel (traitements lourds dans la queue).
 * - Possibilité de suivre la progression via un token unique.
 */
class RunQueueJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function handle()
    {
        Cache::put("traitement.{$this->token}.status", 'in_progress');

        while (true) {
            Artisan::call('queue:work', [
                '--once' => true,
                '--queue' => 'default',
                '--tries' => 3,
            ]);

            // Mettre à jour la progression si tu as une logique
            $remaining = \DB::table('jobs')->count();
            Cache::put("traitement.{$this->token}.progress", $remaining);

            if ($remaining === 0) {
                break;
            }
            usleep(200000);
        }

        Cache::put("traitement.{$this->token}.status", 'done');
    }
}
