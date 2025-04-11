<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait QueryBuilderTrait
{
    public function newQuery(){

        // $this->dataSources
        // Exemple de configuration : DataSouce
        // protected $dataSources = [
        //     "apprenantSansTacheEnCours" => [
        //         "title" => "Apprenants qui n'ont pas de tâches en cours",
        //         "method" => "apprenantSansTacheEnCoursQuery"
        //     ],
        // ];
        $dataSource = $this->viewState->getDataSourceVariables($this->modelName);
    
        if ($dataSource && isset($this->dataSources[$dataSource["code"]])) {
            $sourceConfig = $this->dataSources[$dataSource["code"]];
    
            // On récupère dynamiquement la méthode de requête à appeler
            $method = $sourceConfig['method'] ?? null;
    
            if ($method && method_exists($this, $method)) {
                $this->title = $sourceConfig['title'] ?? $this->title;
                return $this->$method();
            }
        }
        
        return $this->model->newQuery();
    }

    // TODO : ajouter une recherche sur les relation ManyToOne,
    // TODO : ajouter recherche par nom de filiere : Apprenant, ManyToOne/ManyToOne
    /**
     * Construit une requête de récupération des données.
     *
     * @param array $params Critères de recherche.
     * @return Builder
     */
    public function allQuery(array $params = [],$query = null): Builder
    {
        if($query == null) {
            $query = $this->newQuery();
        }
     
        $table = $this->model->getTable();

        // Appliquer la recherche globale
        if (!empty($params['search'])) {
            $query->where(function ($q) use ($params,$table) {
                foreach ($this->getFieldsSearchable() as $field) {
                    $q->orWhere("{$table}.{$field}", 'LIKE', "%{$params['search']}%");
                }
            });
        }

        $filterVariables = $this->viewState->getFilterVariables($this->modelName);
        $this->filter($query,$this->model,$filterVariables);
      

        // Appliquer le tri multi-colonnes
        $sortVariables = $this->viewState->getSortVariables($this->modelName);
        if (!empty($sortVariables)) {
            $this->applySort($query,$sortVariables);
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
                } elseif (in_array($field, $this->getFieldsSortable())) {
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
        $table = $model->getTable(); // Récupérer dynamiquement le nom de la table

        // Charger automatiquement les relations nécessaires
        $relationsToLoad = [];

        foreach ($filterVariables as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            // Vérifier si la clé contient une relation imbriquée (ex: competence.module.ecole.filiere_id)
            if (Str::contains($key, '.')) {


                // Parfois le début de segment commence par une Lettre Majuscule dure à 
                // une solution appliquer pour passer le key en params de laravel 
                $relations = array_map(function ($segment) {
                    return lcfirst($segment);
                }, explode('.', $key));

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
                // Correction : Ajout dynamique du préfixe de la table pour éviter les ambiguïtés SQL
                $builder->where("{$table}.{$key}", $value);
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
