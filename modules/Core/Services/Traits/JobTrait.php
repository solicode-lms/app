<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Support\Facades\Auth;
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
            Auth::id(),
            ucfirst($this->moduleName),
            ucfirst($this->modelName),
            $methodName,
            $id,
            $jobManager->getToken()
        ));

        return $jobManager->getToken();
    }


}
