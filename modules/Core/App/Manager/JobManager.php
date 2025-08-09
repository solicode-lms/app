<?php

namespace Modules\Core\App\Manager;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class JobManager
{
    protected ?string $token = null;
    protected ?int  $total = null;

    protected ?string $methodName = null;
    protected ?string $modelName = null;
    protected ?string $moduleName = null;

    public function __construct(?string $token = null, ?int $total = 0)
    {
         // Si un token est fourni, on considère qu’on reprend un job existant
        if ($token) {
            $this->token = $token;
            $this->total = $total;
            $this->initProgress($total);
            $this->methodName = $this->cacheGet('method');
            $this->moduleName = $this->cacheGet('model');
            $this->moduleName = $this->cacheGet('module');
        }
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

/**
     * Initialisation d’un nouveau job avec un methodName + moduleName
     */
    public function init(string $methodName, string $modelName, string $moduleName): string
    {
        $this->token = Str::uuid()->toString();
        $this->methodName = $methodName;
        $this->moduleName =  ucfirst($moduleName);
        $this->modelName =  ucfirst($modelName);

        $this->setStatus('pending');
        $this->cachePut('method', $methodName);
        $this->cachePut('model', $modelName);
        $this->cachePut('module', $moduleName);

        return $this->token;
    }

    /* ------------------------------
     * Helpers Cache
     * ------------------------------ */
    protected function key(string $suffix): string
    {
        return "traitement.{$this->token}.{$suffix}";
    }

    public function cachePut(string $suffix, mixed $value, int $ttl = 3600): void
    {
        Cache::put($this->key($suffix), $value, $ttl);
    }

    public function cacheGet(string $suffix, mixed $default = null): mixed
    {
        return Cache::get($this->key($suffix), $default);
    }

    /* ------------------------------
     * Status & Messages
     * ------------------------------ */
    public function setStatus(string $status): void
    {
        $this->cachePut('status', $status);
    }

    public function setError(string $message): void
    {
        $this->setStatus('error');
        $this->cachePut('messageError', $message);
    }

    public function setMessage(string $message): void
    {
        $this->cachePut('message', $message);
    }

    /* ------------------------------
     * Progression
     * ------------------------------ */
    public function initProgress(int $total): void
    {
        $total = max(0, $total);
        $this->cachePut('total', $total);
        $this->cachePut('done', 0);
        $this->cachePut('progress', $total === 0 ? 100 : 0);
        $this->setStatus($total === 0 ? 'done' : 'running');
    }

    public function tick(int $step = 1): void
    {
        $total = (int) $this->cacheGet('total', 0);
        $done  = (int) $this->cacheGet('done', 0) + max(0, $step);

        $progress = $total > 0 ? (int) floor(($done / $total) * 100) : 100;

        $this->cachePut('done', $done);
        $this->cachePut('progress', max(0, min(100, $progress)));
    }

    public function finish(): void
    {
        $this->cachePut('progress', 100);
        $this->setStatus('done');
    }

    public function fail(?callable $onAfterCreateFail = null, bool $exposeTrace = false, \Throwable $e = null): void
    {
        if ($e) {
            $this->setError($e->getMessage());
            if ($exposeTrace) {
                $this->cachePut('trace', collect($e->getTrace())->take(10)->all());
            }
        }

        $methodName = $this->cacheGet('method');

        // Suppression uniquement pour afterCreate
        if ($methodName && str_starts_with($methodName, 'afterCreateJob') && $onAfterCreateFail) {
            $onAfterCreateFail();
        }
    }

    /* ------------------------------
     * Progress Updater (Closure)
     * ------------------------------ */
    // public function progressUpdater(int $total): \Closure
    // {
      

    //     return function (int $step = 1) {
    //         $this->tick($step);
    //     };
    // }
}
