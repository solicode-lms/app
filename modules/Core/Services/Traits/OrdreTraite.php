<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Services\UserModelFilterService;

trait OrdreTraite
{
 
 
    /**
     * Summary of reorderOrdreColumn
     * @param mixed $ancienOrdre
     * @param int $nouvelOrdre
     * @param int $idEnCoursModification : pour ne pas changer l'ordre de l'objet en cours de modification
     * @param mixed $groupValue
     * @return void
     */
    protected function reorderOrdreColumn(?int $ancienOrdre, int $nouvelOrdre, int $idEnCoursModification = null, $groupValue = null): void
    {
        $this->normalizeOrdreIfNeeded($groupValue);

        if ($ancienOrdre !== null && $nouvelOrdre === $ancienOrdre) {
            return;
        }

        $query = $this->model->newQuery();

        if ($idEnCoursModification !== null) {
            $query->where('id', '!=', $idEnCoursModification);
        }

        // ✅ Appliquer la contrainte de groupe si nécessaire
        if ($this->ordreGroupColumn && $groupValue !== null) {
            $query->where($this->ordreGroupColumn, $groupValue);
        }

        if ($ancienOrdre === null) {
            $query->where('ordre', '>=', $nouvelOrdre)
                ->orderBy('ordre', 'desc')
                ->get()
                ->each(function ($item) {
                    $item->ordre += 1;
                    $item->save();
                });
        } else {
            if ($nouvelOrdre > $ancienOrdre) {
                $query->whereBetween('ordre', [$ancienOrdre + 1, $nouvelOrdre])
                    ->orderBy('ordre')
                    ->get()
                    ->each(function ($item) {
                        $item->ordre -= 1;
                        $item->save();
                    });
            } else {
                $query->whereBetween('ordre', [$nouvelOrdre, $ancienOrdre - 1])
                    ->orderBy('ordre', 'desc')
                    ->get()
                    ->each(function ($item) {
                        $item->ordre += 1;
                        $item->save();
                    });
            }
        }
    }

    

    protected function normalizeOrdreIfNeeded($groupValue = null): void
    {
        $query = $this->model->newQuery();
    
        if ($this->ordreGroupColumn && $groupValue !== null) {
            $query->where($this->ordreGroupColumn, $groupValue);
        }
    
        $elementsSansOrdre = $query->where(function($q){
                                    $q->whereNull('ordre')->orWhere('ordre', '');
                                })
                                ->orderBy('id')
                                ->get();
    
        if ($elementsSansOrdre->isEmpty()) {
            return;
        }
    
        // Trouver l'ordre maximal actuel dans le groupe
        $maxOrdreQuery = $this->model->newQuery();
        if ($this->ordreGroupColumn && $groupValue !== null) {
            $maxOrdreQuery->where($this->ordreGroupColumn, $groupValue);
        }
        $maxOrdre = $maxOrdreQuery->max('ordre') ?? 0;
    
        foreach ($elementsSansOrdre as $element) {
            $maxOrdre++;
            $element->ordre = $maxOrdre;
            $element->save();
        }
    }


}
