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
            
            if ($this->hasOrdreColumn()) {
                // Tri par défaut par "ordre" si non défini explicitement
                if (!isset($params['order_by'])) {
                    $query->orderBy('ordre');
                }
            }

            
            if(!empty($this->index_with_relations)){
                $query->with(array_unique($this->index_with_relations));
            }
            
    
            $results = $query->paginate($perPage, $columns);

            // Calcul du nombre total des résultats filtrés
            $this->totalFilteredCount = $results->total(); 

            return  $results;
        });
    }
}
