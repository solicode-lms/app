<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

trait CrudUpdateTrait
{

    public function updateOnlyExistanteAttribute($idOrItem, array $data)
    {
        return DB::transaction(function () use ($idOrItem,$data) {

            $entity = is_object($idOrItem) ? $idOrItem : $this->find($idOrItem);
            $id = $entity->id;


            $this->executeRules('before', 'update', $data, $id);
    
            if (!$entity) {
                return false;
            }

            if ($this->hasOrdreColumn()) {
                $ancienOrdre = $entity->ordre;
        
                if (!isset($data['ordre']) || $data['ordre'] === null) {
                    $data['ordre'] = $ancienOrdre ?? $this->getNextOrdre();
                }
        
                $nouvelOrdre = $data['ordre'];
        
                if ($nouvelOrdre !== $ancienOrdre) {
                    $this->reorderOrdreColumn($ancienOrdre, $nouvelOrdre, $entity->id, $entity->projet_id);
                }
            }



            // Filtrer les champs non fillable
            $data_fillable = array_intersect_key($data, array_flip($this->model->getFillable()));
            
            $entity->update($data_fillable);

            $this->executeRules('after', 'update', $entity, $id);
                $this->executeJob('after', 'create', $entity->id);
            return $entity;
        });
    }

    /**
     * Met à jour un élément existant.
     * Si les valeur ManytoMany n'existe pas dans $data il seron supprimer
     *
     * @param mixed $id Identifiant de l'élément à mettre à jour.
     * @param array $data Données à mettre à jour.
     * @return Entity modifié
    */
    public function update($idOrItem, array $data)
    {
        return DB::transaction(function () use ($idOrItem, $data) {
            $entity = is_object($idOrItem) ? $idOrItem : $this->find($idOrItem);
            if (!$entity) {
                return false;
            }

            $id = $entity->id;
            $this->executeRules('before', 'update', $data, $id);

            if ($this->hasOrdreColumn()) {
                $ancienOrdre = $entity->ordre;
                $data['ordre'] = $data['ordre'] ?? $ancienOrdre ?? $this->getNextOrdre();
                $nouvelOrdre = $data['ordre'];

                if ($nouvelOrdre !== $ancienOrdre) {
                    $groupId = $this->ordreGroupColumn && isset($entity->{$this->ordreGroupColumn})
                        ? $entity->{$this->ordreGroupColumn}
                        : null;
                    $this->reorderOrdreColumn($ancienOrdre, $nouvelOrdre, $entity->id, $groupId);
                }
            }

            $data_fillable = array_intersect_key($data, array_flip($this->model->getFillable()));
           
            $entity->update($data_fillable);
            $this->syncManyToManyRelations($entity, $data);
            $this->executeRules('after', 'update', $entity, $id);

            return $entity;
        });
    }

  /**
     * Méthode surchargable pour mettre à jour un enregistrement.
     * Par défaut, elle utilise la méthode Eloquent standard.
     *
     * @param Model $entity Enregistrement à modifier
     * @param array $data Données de mise à jour
     * @return void
     */
    protected function updateRecord(Model $entity, array $data): void
    {
        $entity->update($data);
    }
}