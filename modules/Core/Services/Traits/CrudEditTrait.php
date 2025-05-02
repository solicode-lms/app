<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait CrudEditTrait
{
    public function edit(int $id){
      
        $this->executeRules('before', 'edit', null, $id);
        $entity =  $this->model->find($id);
        $this->executeRules('after', 'edit', $entity, $id);
        return $entity;
    }

}