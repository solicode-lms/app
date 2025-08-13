<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RunTraitementCommand extends Command
{
    protected $signature = 'traitement:run {token}';
    protected $description = 'Exécute tous les jobs en attente jusqu\'à ce que la queue soit vide';

    public function handle()
    {
        $token = $this->argument('token');

        while (true) {
            Artisan::call('queue:work', [
                '--once' => true,
                '--queue' => 'default',
                '--tries' => 3,
            ]);

            $remaining = DB::table('jobs')->count();
            Cache::put("traitement.$token.progress", $remaining);

            if ($remaining === 0) {
                break;
            }
            usleep(200000);
        }

        Log::info("run");
         $this->info("✅ Tous les jobs sont terminés");
        Cache::put("traitement.$token.status", 'done');
    }
}
