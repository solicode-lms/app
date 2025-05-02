<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait CrudReadTrait
{
      /**
     * Renvoie tous les éléments correspondants aux critères donnés.
     *
     * @param array $search Critères de recherche.
     * @param int|null $skip Nombre d'éléments à ignorer.
     * @param int|null $limit Nombre maximal d'éléments à récupérer.
     * @param array $columns Colonnes à récupérer.
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->withScope(fn() =>  $this->model::all());
    }
        /**
     * Récupère un élément par son identifiant.
     *
     * @param int $id Identifiant de l'élément à récupérer.
     * @param array $columns Colonnes à récupérer.
     * @return mixed
     */
    public function find(int $id, array $columns = ['*']){
        return $this->model->find($id, $columns);
    }
         /**
     * Récupère un élément à partir de sa référence unique.
     *
     * @param string $reference Le champ de référence unique.
     * @param array $columns Colonnes à récupérer.
     * @return Model|null
     */
    public function getByReference(string $reference, array $columns = ['*'])
    {
        return $this->model->where('reference', $reference)->first($columns);
    }

}