<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Services\UserModelFilterService;
use Modules\Core\Services\ViewStateService;

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

        // $this->viewState = app(ViewStateService::class);
        
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

    /**
     * Construit la requête "all". On y applique successivement :
     * 1) recherche globale,
     * 2) filter (AND),
     * 3) where strict (AND),
     * 4) orWhere groupé (OR),
     * 5) tri multi-colonnes
     */
    public function allQuery(array $params = [],$query = null): Builder
    {
        if($query == null) {
            $query = $this->newQuery();
        }
     
        $table = $this->model->getTable();

        // 1) Appliquer la recherche globale
        if (!empty($params['search'])) {
            $query->where(function ($q) use ($params,$table) {
                foreach ($this->getFieldsSearchable() as $field) {
                    $q->orWhere("{$table}.{$field}", 'LIKE', "%{$params['search']}%");
                }
            });
        }

        // 2) Récupérer / initialiser les variables de filtre (ViewState + UserModelFilterService)
        $filterVariables = $this->viewState->getFilterVariables($this->modelName);


        $this->loadLastFilterIfEmpty();
   


        // 3) Appliquer les filtres "filter" (AND)
        $this->filter($query, $this->model, $filterVariables);

        // 4) Appliquer les conditions "where" (AND strictes) du ViewState
        $whereVariables = $this->viewState->getWhereVariables($this->modelName);
        $this->where($query, $this->model, $whereVariables);

        // 5) Appliquer les conditions "orWhere" (mode OR), MAIS regroupées dans un même groupe
        $orWhereVariables = $this->viewState->getOrWhereVariables($this->modelName);
        if (!empty($orWhereVariables)) {
            // On crée une closure pour tout mettre entre parenthèses
            $query->where(function (Builder $q) use ($orWhereVariables) {
                // À l’intérieur, applyCondition(..., true) appliquera successivement
                // tous les orWhere / orWhereHas pour chaque filtre
                $this->applyCondition($q, $this->model, $orWhereVariables, true);
            });
        }

        // 6) Appliquer le tri multi-colonnes
        $sortVariables = $this->viewState->getSortVariables($this->modelName);
        if (!empty($sortVariables)) {
            $this->applySort($query,$sortVariables);
        }else{
            // appliquer les tris par défaut
            $this->applySort($query,null);
        }

        return $query;
    }
    /**
     * Applique un tri dynamique à la requête selon les champs spécifiés.
     *
     * Cette méthode gère :
     * 1. Le tri sur des relations via `sortByPath` défini dans le tableau `manyToOne` du modèle.
     * 2. Le tri sur des champs simples déclarés dans `getFieldsSortable()`.
     * 3. Un tri par défaut (sur `ordre` ou `updated_at`) si aucun champ de tri n'est précisé.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     *        La requête sur laquelle appliquer le tri.
     * @param array|null $sortFields
     *        Tableau associatif des champs à trier avec leur direction (ex: ['etat_realisation_tache_id' => 'asc']).
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     *         La requête modifiée avec les clauses de tri appliquées.
     */

    
    /**
     * filter : wrapper pour applyCondition en mode "where" (AND),
     * en utilisant les variables "filter" du ViewState.
     *
     * @param Builder       $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array         $filterVariables
     */
    public function filter(Builder $builder, $model, array $filterVariables): void
    {
        $this->applyCondition($builder, $model, $filterVariables, false);
    }

        /**
     * where : applique strictement les variables "where" du ViewState (mode AND).
     *
     * @param Builder       $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function where(Builder $builder, $model, array $whereVariables): void
    {
        $this->applyCondition($builder, $model, $whereVariables, false);
    }

    /**
     * orWhere : applique strictement les variables "OrWhere" du ViewState (mode OR).
     *
     * @param Builder       $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function orWhere(Builder $builder, $model, array $orWhereVariables): void
    {
        $this->applyCondition($builder, $model, $orWhereVariables, true);
    }

    /**
     * Cette fonction est utilisé aussi Dans DynamiqueContextScope
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed $model
     * @return void
     */
    public function applyCondition(Builder $builder, $model, $filterVariables, bool $useOr = false){
   
         // Liste des attributs "fillable" pour détecter les colonnes simples
        $modelAttributes = $model->getFillable();
        $table = $model->getTable(); // Récupérer dynamiquement le nom de la table

        // Charger automatiquement les relations nécessaires
        $relationsToLoad = [];

        // Choix dynamique des méthodes Eloquent : where vs orWhere
        $methodWhere = $useOr ? 'orWhere' : 'where';
        // Méthode pour les relations imbriquées
        $methodHas   = $useOr ? 'OrWhereHas' : 'whereHas';

        foreach ($filterVariables as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            // 1) Cas d’une relation imbriquée ("relation1.relation2.attribut")
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
                    $builder->{$methodHas}(implode('.', $relations), function ($query) use ($attribute, $value, $methodWhere) {
                        // À l’intérieur du callback, on utilise TOUT LE TEMPS ->where(...)
                        // (même si $useOr = true), car l’opérateur OR global est pris en charge
                        // par orWhereHas au niveau supérieur.
                        $query->where($attribute, $value);
                    });
                }
            // 2) Cas d’un attribut simple (colonne "fillable")
            } elseif (in_array($key, $modelAttributes)) {
                // Correction : Ajout dynamique du préfixe de la table pour éviter les ambiguïtés SQL
                $builder->{$methodWhere}("{$table}.{$key}", $value);
            }
        }

        // 3) Cas d’une relation many-to-many si définie dans $model->manyToMany
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
                    $builder->{$methodHas}($relationName, function ($query) use ($relationId, $methodWhere) {
                        $query->where('id', $relationId);
                    });
                }
            }
        }

        // il faut par charger les relation : il sont charger dans 
        // paginate
        // Appliquer le eager loading si des relations doivent être chargées
        // if (!empty($relationsToLoad)) {
        //     $builder->with(array_unique($relationsToLoad));
        // }
    }

    /**
     * Applique dynamiquement les jointures nécessaires à partir d’un chemin relationnel
     * et retourne le nom de la colonne à sélectionner.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $relationPath  Exemple : "module.filiere.id"
     * @return string               Colonne qualifiée à utiliser dans SELECT
     */
    protected function applyDynamicJoins($query, string $relationPath): string
    {
        $relations = explode('.', $relationPath);
        $column = array_pop($relations);

        $baseTable = $this->model->getTable();
        $currentModel = $this->model;
        $lastTable = $baseTable;

        static $aliasCount = 0;

        foreach ($relations as $relationName) {
            if (!method_exists($currentModel, $relationName)) {
                throw new \Exception("Relation [$relationName] non trouvée sur le modèle " . get_class($currentModel));
            }

            $relation = $currentModel->{$relationName}();
            $relatedModel = $relation->getRelated();
            $relatedTable = $relatedModel->getTable();
            $alias = "{$relatedTable}_t" . $aliasCount++;

            $foreignKey = method_exists($relation, 'getForeignKeyName')
                ? $relation->getForeignKeyName()
                : $relation->getQualifiedForeignPivotKeyName();

            $ownerKey = method_exists($relation, 'getOwnerKeyName')
                ? $relation->getOwnerKeyName()
                : $relation->getQualifiedRelatedPivotKeyName();

            $query->join("{$relatedTable} as {$alias}", "{$lastTable}.{$foreignKey}", '=', "{$alias}.{$ownerKey}");

            $currentModel = $relatedModel;
            $lastTable = $alias;
        }

        return "$lastTable.$column";
    }

    
}
