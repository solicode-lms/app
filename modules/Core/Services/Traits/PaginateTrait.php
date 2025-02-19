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
        $query = $this->allQuery($params);
        return $query->paginate($perPage, $columns);
    }
}
