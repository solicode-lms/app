<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait CrudTrait
{
    /**
     * Exécute dynamiquement les règles métier avant ou après une action.
     *
     * @param string $when  'before' ou 'after'
     * @param string $action 'create', 'update', 'delete', etc.
     * @param array|object|null $dataOrEntity  Les données ou l'entité cible
     * @param int|null $id  L'identifiant (optionnel) si pertinent
     * @return void
     */
    protected function executeRules(string $when, string $action, array|object|null &$dataOrEntity = null, int|null $id = null): void
    {
        $methodName = "{$when}".ucfirst($action)."Rules";

        if (method_exists($this, $methodName)) {
            // Appel intelligent : passer les deux paramètres si la méthode les accepte
            $reflection = new \ReflectionMethod($this, $methodName);
            $params = $reflection->getNumberOfParameters();

            if ($params === 2) {
                $this->{$methodName}($dataOrEntity, $id);
            } else {
                $this->{$methodName}($dataOrEntity);
            }
        }
    }


    protected function getNextOrdre(): int
    {
        return ($this->model->max('ordre') ?? 0) + 1;
    }
    protected function hasOrdreColumn(): bool
    {
        return \Illuminate\Support\Facades\Schema::hasColumn($this->createInstance()->getTable(), 'ordre');
    }


}