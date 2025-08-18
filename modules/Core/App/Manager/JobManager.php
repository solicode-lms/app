<?php

namespace Modules\Core\App\Manager;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Modules\Core\App\Jobs\TraitementCrudJob;

class JobManager
{
    protected ?string $token = null;
    protected ?int  $total = null;
    protected ?string $methodName = null;
    protected ?string $modelName = null;
    protected ?string $moduleName = null;
    protected ?int  $id = null;
    // protected changedFields;

    public function __construct(?string $token = null, ?int $total = 0)
    {
         // Si un token est fourni, on considère qu’on reprend un job existant
        if ($token) {
            $this->token = $token;
            $this->total = $total;
            $this->initProgress($total);
            $this->methodName = $this->cacheGet('method');
            $this->modelName = $this->cacheGet('model');
            $this->moduleName = $this->cacheGet('module');
            $this->id = $this->cacheGet('id');
            $this->changedFields = $this->cacheGet('changed_fields', []);
            $this->setTokenInService();
        }
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
    public function getChangedFields(): array
    {
        return $this->cacheGet('changed_fields', []);
    }

/**
     * Initialisation d’un nouveau job avec un methodName + moduleName
     */
    public function init(
        string $methodName,
        string $modelName,
        string $moduleName,
        ?int $id = null,
        array $changedFields = [],
        array $payload = [] 
        ): string {
   
        $this->token = Str::uuid()->toString();
        $this->methodName = $methodName;
        $this->moduleName =  ucfirst($moduleName);
        $this->modelName =  ucfirst($modelName);
        $this->id = $id;

        $this->setStatus('pending');
        $this->cachePut('method', $methodName);
        $this->cachePut('model', $modelName);
        $this->cachePut('module', $moduleName);
        $this->cachePut('id', $id);

        if (!empty($changedFields)) {
            $this->cachePut('changed_fields', $changedFields);
        }
        // Sauvegarder le payload personnalisé
        if (!empty($payload)) {
            $this->cachePut('payload', $payload);
        }
        return $this->token;
    }
    public static function initJob(
        string $methodName,
        string $modelName,
        string $moduleName,
        ?int $id,
        array $changedFields = [],
        array $payload = [] 
    ): self {
        $instance = new self();
        $instance->init($methodName, $modelName, $moduleName, $id, $changedFields, $payload);
        return $instance;
    }

    public function setLabel(string $label): void
    {
        $this->cachePut('label', $label);
    }

    public function getLabel(): string
    {
        return $this->cacheGet('label', "⏳ Traitement en cours...");
    }
    public function getPayload(): array
    {
        return $this->cacheGet('payload', []);
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

    public function dispatchTraitementCrudJob(){


        $service = $this->getServiceInstance();

        // $CRUD_JOBS_ENABLED = env('CRUD_JOBS_ENABLED', false);
        //  if($service->getCrudJobToken() ||  !$CRUD_JOBS_ENABLED){

        if($service->getCrudJobToken()){
             // On exécute directement la méthode sans passer par la queue
            $service = $this->getServiceInstance();
            if (method_exists($service, $this->methodName)) {
                $service->{$this->methodName}($this->id, $this->getToken());
            }
            return;
        }
        // if (app()->bound('executing_in_job')) {
           
        // }

         // Dispatch du job générique
        dispatch(new TraitementCrudJob(
            Auth::id(),
            ucfirst($this->moduleName),
            ucfirst($this->modelName),
            $this->methodName,
            $this->id,
            $this->getToken()
        ));

        $this->setTokenInService();
    }

    /**
     * Retourne une instance du service lié au job courant.
     *
     * @return mixed
     * @throws \RuntimeException
     */
    public function getServiceInstance()
    {
        if (!$this->moduleName || !$this->modelName) {
            throw new \RuntimeException("Impossible d'initialiser le service : moduleName ou modelName manquant.");
        }

        // Construire le FQCN du service
        $serviceClass = "Modules\\{$this->moduleName}\\Services\\{$this->modelName}Service";

        if (!class_exists($serviceClass)) {
            throw new \RuntimeException("Service {$serviceClass} introuvable.");
        }

        return app($serviceClass);
    }
    /**
     * Définit automatiquement le token dans le service lié au job courant
     * (uniquement pour TraitementCrudJob).
     *
     * @return mixed Instance du service avec le token configuré
     * @throws \RuntimeException
     */
    public function setTokenInService()
    {
        $service = $this->getServiceInstance();

        if (!method_exists($service, 'setCrudJobToken')) {
            throw new \RuntimeException("La méthode setCrudJobToken() est manquante dans " . get_class($service) . ".");
        }

        $service->setCrudJobToken($this->getToken());

        return $service;
    }



    public function isDirty(string $field): bool
    {
        $changed = $this->getChangedFields();
        return in_array($field, $changed, true);
    }

}
