<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Modules\Core\App\Jobs\TraitementCrudJob;
use Modules\Core\App\Manager\JobManager;

trait JobTrait
{

    /**
     * Exécute un job différé lié à une action CRUD.
     *
     * @param 'before'|'after' $when
     * @param string           $action   Exemple: create, update...
     * @param int|null         $id       ID de l'entité
     * @return string|null     Token pour suivre l’état du job (ou null si méthode absente)
     */
    protected function executeJob(string $when, string $action, ?int $id = null): ?string
    {
        $methodName = "{$when}" . ucfirst($action) . "Job";
        
        // Si la méthode n'existe pas dans le service → on ne lance rien
        if (!method_exists($this, $methodName)) {
            return null;
        }
        
        $jobManager = new JobManager();
        $jobManager->init($methodName, $this->modelName, $this->moduleName);
        $this->crudJobToken = $jobManager->getToken();

        

        // Dispatch du job générique
        dispatch(new TraitementCrudJob(
            ucfirst($this->moduleName),
            ucfirst($this->modelName),
            $methodName,
            $id,
            $jobManager->getToken()
        ));

        return $jobManager->getToken();
    }


    // // /** @var string|null */
    // // protected ?string $job_token = null;
    // // public function getJobToken(): ?string
    // // {
    // //     return $this->job_token;
    // // }

    // /**
    //  * Exécute dynamiquement un traitement différé (Job) avant ou après une action.
    //  *
    //  * @param  'before'|'after'  $when
    //  * @param  string            $action  ex: create, update...
    //  * @param  int|null          $id
    //  * @return string|null       token pour suivre l’état du job (ou null si méthode absente)
    //  */
    // protected function executeJob(string $when, string $action, int|null $id = null): ?string
    // {
    //     $methodName = "{$when}" . ucfirst($action) . "Job";

    //     if (!method_exists($this, $methodName)) {
    //         return null;
    //     }

    //     $token = $this->initJob($methodName);

    //     // Dispatch du job générique (le job appellera $service->$methodName($id, $token))
    //     dispatch(new TraitementCrudJob(
    //         ucfirst($this->moduleName),
    //         ucfirst($this->modelName),
    //         $methodName,
    //         $id,
    //         $token
    //     ));

    //     return $token;
    // }

    // public function initJob($methodName){

    //     $token = $this->jobMakeToken();
    //     $this->jobSetStatus($token, 'pending');

    //     // Enregistre le token côté instance (utile si on veut le réutiliser)
    //     $this->job_token = $token;

    //     // 💾 Sauvegarder le nom de la méthode pour jobFail()
    //     $this->jobCachePut($token, 'method', $methodName);

    //     return  $token;

    // }

    // /* ------------------------------
    //  * Helpers génériques de Job/Cache
    //  * ------------------------------ */

    // protected function jobMakeToken(): string
    // {
    //     return Str::uuid()->toString();
    // }

    // protected function jobKey(string $token, string $suffix): string
    // {
    //     return "traitement.{$token}.{$suffix}";
    // }

    // protected function jobCachePut(string $token, string $suffix, mixed $value, int $ttl = 3600): void
    // {
    //     Cache::put($this->jobKey($token, $suffix), $value, $ttl);
    // }

    // protected function jobCacheGet(string $token, string $suffix, mixed $default = null): mixed
    // {
    //     return Cache::get($this->jobKey($token, $suffix), $default);
    // }

    // /* ------------------------------
    //  * Gestion d’état & messages
    //  * ------------------------------ */

    // protected function jobSetStatus(string $token, string $status): void
    // {
    //     $this->jobCachePut($token, 'status', $status);
    // }

    // protected function jobSetError(string $token, string $message): void
    // {
    //     $this->jobSetStatus($token, 'error');
    //     $this->jobCachePut($token, 'messageError', $message);
    // }

    // protected function jobSetMessage(string $token, string $message): void
    // {
    //     $this->jobCachePut($token, 'message', $message);
    // }

    // /* ------------------------------
    //  * Progression (%)
    //  * ------------------------------ */

    // /**
    //  * Initialise la progression (stocke total/done/progress et met le status à 'running').
    //  */
    // protected function jobInitProgress(string $token, int $total): void
    // {
    //     $total = max(0, $total);
    //     $this->jobCachePut($token, 'total', $total);
    //     $this->jobCachePut($token, 'done', 0);
    //     $this->jobCachePut($token, 'progress', $total === 0 ? 100 : 0);
    //     $this->jobSetStatus($token, $total === 0 ? 'done' : 'running');
    // }

    // /**
    //  * Incrémente l’avancement et recalcule le pourcentage.
    //  */
    // protected function jobTick(string $token, int $step = 1): void
    // {
    //     $total = (int) $this->jobCacheGet($token, 'total', 0);
    //     $done  = (int) $this->jobCacheGet($token, 'done', 0) + max(0, $step);

    //     if ($total > 0) {
    //         $progress = (int) floor(($done / $total) * 100);
    //     } else {
    //         $progress = 100;
    //     }

    //     $this->jobCachePut($token, 'done', $done);
    //     $this->jobCachePut($token, 'progress', max(0, min(100, $progress)));
    // }

    // /**
    //  * Termine avec succès (100% + status=done).
    //  */
    // protected function jobFinish(string $token): void
    // {
    //     $this->jobCachePut($token, 'progress', 100);
    //     $this->jobSetStatus($token, 'done');
    // }

    // /**
    //  * Termine en erreur en stockant le message (et optionnellement l’exception).
    //  */
    // protected function jobFail($id , string $token, \Throwable $e, bool $exposeTrace = false): void
    // {
    //     $this->jobSetError($token, $e->getMessage());

    //     if ($exposeTrace) {
    //         $this->jobCachePut($token, 'trace', collect($e->getTrace())->take(10)->all());
    //     }

    //     // 📥 Récupérer le nom de la méthode enregistré au dispatch
    //     $methodName = $this->jobCacheGet($token, 'method');

    //     // ❗ Suppression UNIQUEMENT pour le job "after create"
    //     if ($methodName && str_starts_with($methodName, 'afterCreateJob')) {
    //          $this->destroy($id);
    //     }
    // }

    // /**
    //  * Fournit un petit "updater" pratique à utiliser dans le job.
    //  * Exemple d’usage :
    //  *   $update = $this->jobProgressUpdater($token, $total);
    //  *   foreach (...) { ... $update(); }
    //  */
    // protected function jobProgressUpdater(string $token, int $total): \Closure
    // {
    //     $this->jobInitProgress($token, $total);

    //     return function (int $step = 1) use ($token) {
    //         $this->jobTick($token, $step);
    //     };
    // }
}
