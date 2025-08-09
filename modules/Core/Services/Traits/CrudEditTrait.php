<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

trait CrudEditTrait
{
    public function edit(int $id){
      
        return DB::transaction(function () use ($id) {
            $empty = null; // ✅ Déclarer une variable d'abord
            $this->executeRules('before', 'edit', $empty, $id);
            $entity =  $this->model->find($id);
            $this->executeRules('after', 'edit', $entity, $id);
            $this->executeJob('after', 'create', $entity->id);

            // TODO : il doit être appliquer aussi afterShow
            $this->markNotificationsAsRead($id);

            return $entity;
        });
    }

}