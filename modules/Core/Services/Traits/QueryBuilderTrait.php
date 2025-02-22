<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Builder;
use Modules\PkgRealisationProjets\Models\AffectationProjet;

trait QueryBuilderTrait
{

        // TODO : ajouter une recherche sur les relation ManyToOne,
    // TODO : ajouter recherche par nom de filiere : Apprenant, ManyToOne/ManyToOne
    /**
     * Construit une requête de récupération des données.
     *
     * @param array $params Critères de recherche.
     * @return Builder
     */
    public function allQuery(array $params = []): Builder
    {
        // $query = AffectationProjet::with(['projet', 'groupe'])->newQuery();
        $query = AffectationProjet::query()->with(['projet', 'groupe']);

        // Appliquer la recherche globale
        if (!empty($params['search'])) {
            $query->where(function ($q) use ($params) {
                foreach ($this->getFieldsSearchable() as $field) {
                    $q->orWhere($field, 'LIKE', "%{$params['search']}%");
                }
            });
        }

        // Les filtre sopnt appliquer dans DynamiqueContextScope
        // Appliquer les filtres spécifiques (URL aplatie)
        foreach ($params as $field => $value) {
            if (in_array($field, $this->getFieldsSearchable()) && !empty($value)) {
                if (is_numeric($value)) {
                    // Utiliser "=" pour les valeurs numériques
                    $query->where($field, '=', $value);
                } else {
                    // Utiliser "LIKE" pour les chaînes
                    $query->where($field, 'LIKE', "%{$value}%");
                }
            }
        }

      

        // Appliquer le tri multi-colonnes
        $sortVariables = $this->viewState->getSortVariables($this->modelName);
        if (!empty($sortVariables)) {
            $this->applySort($query,$sortVariables);

            // $sortFields = explode(',', $params['sort']);
            // foreach ($sortFields as $sortField) {

            //     $fieldParts = explode('_', $sortField); // Divise en segments
            //     $direction = end($fieldParts);         // Récupère la direction (dernier élément)
            //     $field = implode('_', array_slice($fieldParts, 0, -1)); // Combine le reste pour former le champ

            //     if (in_array($field, $this->getFieldsSearchable())) {
            //         $query->orderBy($field, $direction);
            //     }
            // }
         }

        return $query;
    }

    
    public function applySort($query, $sort)
    {
        if ($sort) {
            $sortFields = explode(',', $sort["sort"]);
    
            foreach ($sortFields as $sortField) {
                $fieldParts = explode('_', $sortField);
                $direction = end($fieldParts);
                $field = implode('_', array_slice($fieldParts, 0, -1));
    
                // Vérifier si le champ est une relation sortable
                $filterableField = collect($this->fieldsFilterable)
                    ->firstWhere('field', $field);
    
                if ($filterableField && isset($filterableField['sortable'])) {
                    [$relationTable, $relationColumn] = explode('.', $filterableField['sortable']);
                    $query->join($relationTable, "{$this->model->getTable()}.{$field}", '=', "{$relationTable}.id")
                            ->select([
                                "{$this->model->getTable()}.*",
                                "{$relationTable}.{$relationColumn} as {$field}_sortable"
                            ])
                            ->orderBy("{$relationTable}.{$relationColumn}", $direction);
                } elseif (in_array($field, $this->getFieldsSearchable())) {
                    // Appliquer un tri normal pour les champs directs
                    $query->orderBy($field, $direction);
                }
            }
        }
    
        return $query;
    }
    
}
