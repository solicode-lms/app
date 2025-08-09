<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Modules\Core\App\Jobs\GenericAsyncServiceJob;
use Modules\Core\App\Jobs\TraitementCrudJob;

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









      // TODO : déplacer dans NotificationService
    /**
     * Marquer toutes les notifications liées à une realisation_tache comme lues.
     */
    protected function markNotificationsAsRead($id): void
    {
        $user = Auth::user();
        $jsonField = $this->modelName;
        if (!$user) {
            return;
        }
    
        /** @var \Modules\PkgNotification\Services\NotificationService $notificationService */
        $notificationService = app(\Modules\PkgNotification\Services\NotificationService::class);
    
        $notifications = $notificationService->newQuery()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->where("data->{$jsonField}", $id) // ⬅️ utilisation générique du chemin JSON
            ->get();
    
        foreach ($notifications as $notification) {
            $notificationService->markAsRead($notification->id);
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