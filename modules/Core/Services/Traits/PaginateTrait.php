<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait PaginateTrait
{
    /**
     * Renvoie une pagination des résultats.
     */
    public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    {
        $perPage = $perPage ?: $this->paginationLimit;
    
        return $this->model::withScope(function () use ($params, $perPage, $columns) {
            $query = $this->allQuery($params);
            
            // TODO : Gapp : EagerLoading Charger les relations nécessaires : DataFields de type ManyToOne, ManyToMany ayant DisplayInTable
            // $relationsToLoad = ["projet", "groupe"];
            // $query->with(array_unique($relationsToLoad));
    
                    // Calcul du nombre total des résultats filtrés
            $this->totalFilteredCount = $query->count();

            return $query->paginate($perPage, $columns);
        });
    }
}
