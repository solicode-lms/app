<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait CrudDeleteTrait
{
        /**
     * Supprime un élément par son identifiant.
     *
     * @param mixed $id Identifiant de l'élément à supprimer.
     * @return bool|null
     */
    public function destroy($id){
       
        $entity = $this->model->find($id);
        $this->executeRules('before', 'delete', $entity, $id);
        // TODO :throw exception if $entity is null
        $entity->delete();
        $this->executeRules('after', 'delete', $entity, $id);
        return  $entity;
    }

}