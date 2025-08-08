<?php

namespace Modules\Core\App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class TraitementLourdJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected string $model;
    protected int $id;
    protected string $token;

    public function __construct(string $model, int $id, string $token)
    {
        $this->model = $model;
        $this->id = $id;
        $this->token = $token;
    }

    public function handle(): void
    {
        $serviceClass = "Modules\\PkgRealisationProjets\\Services\\{$this->model}Service";

        try {
            $service = new $serviceClass();
            $status = $service->runAsyncAfterCreate($this->id, $this->token); // 💥 À définir dans ton service
            Cache::put("traitement.{$this->token}.status",  $status, 3600);
        } catch (\Throwable $e) {
            Cache::put("traitement.{$this->token}.messageError",  $e->getMessage(), 3600);
            Cache::put("traitement.{$this->token}.status",  "error");
        }
    }
}