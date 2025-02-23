<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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
        $query = $this->model->newQuery();

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
        // foreach ($params as $field => $value) {
        //     if (in_array($field, $this->getFieldsSearchable()) && !empty($value)) {
        //         if (is_numeric($value)) {
        //             // Utiliser "=" pour les valeurs numériques
        //             $query->where($field, '=', $value);
        //         } else {
        //             // Utiliser "LIKE" pour les chaînes
        //             $query->where($field, 'LIKE', "%{$value}%");
        //         }
        //     }
        // }
        $filterVariables = $this->viewState->getFilterVariables($this->modelName);
        $this->filter($query,$this->model,$filterVariables);
      

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

        /**
     * Cette fonction est utilisé aussi Dans DynamiqueContextScope
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed $model
     * @return void
     */
    public function filter(Builder $builder, $model, $filterVariables){
   
        // Obtenir les colonnes disponibles dans le modèle
        $modelAttributes = $model->getFillable();

        // Charger automatiquement les relations nécessaires
        $relationsToLoad = [];

        foreach ($filterVariables as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            // Vérifier si la clé contient une relation imbriquée (ex: competence.module.ecole.filiere_id)
            if (Str::contains($key, '.')) {
                $relations = explode('.', $key);
                $attribute = array_pop($relations); // Récupère le dernier élément (filiere_id)

                // Vérifier si la première relation existe sur le modèle
                if (method_exists($model, $relations[0])) {
                    
                    // Ajouter la relation à charger
                    $relationsToLoad[] = implode('.', $relations);
                    
                    // Appliquer whereHas récursivement
                    $builder->whereHas(implode('.', $relations), function ($query) use ($attribute, $value) {
                        $query->where($attribute, $value);
                    });
                }
            } elseif (in_array($key, $modelAttributes)) {
                // Appliquer un filtre simple si c'est une colonne du modèle
                $builder->where($key, $value);
            }
        }


        // Vérifier si le modèle a une propriété manyToMany définie
        if (property_exists($model, 'manyToMany') && is_array($model->manyToMany)) {
            foreach ($model->manyToMany as $relationInfo) {
                $relationName = $relationInfo['relation']; // ex: apprenants, formateurs
                $foreignKey = $relationInfo['foreign_key']; // ex: apprenant_id, formateur_id

                // Vérifier si une clé correspondant à la relation existe dans ViewState
                if (isset($filterVariables[$foreignKey]) && !is_null($filterVariables[$foreignKey])) {
                    $relationId = $filterVariables[$foreignKey];
                    // Ajouter la relation à charger
                    $relationsToLoad[] = $relationName;
                    // Appliquer whereHas() dynamiquement
                    $builder->whereHas($relationName, function ($query) use ($relationId) {
                        $query->where('id', $relationId);
                    });
                }
            }
        }

        // Appliquer le eager loading si des relations doivent être chargées
        if (!empty($relationsToLoad)) {
            $builder->with(array_unique($relationsToLoad));
        }
    }
    
}
