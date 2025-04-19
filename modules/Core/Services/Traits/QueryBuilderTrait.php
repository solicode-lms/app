<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Services\UserModelFilterService;

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
     
        // Si vide, essayer de récupérer le filtre enregistré
        $userModelFilterService = new UserModelFilterService();
        $isReset = $this->viewState->isResetRequested($this->modelName);
        if ($isReset) {
            // 🔄 Réinitialisation explicite demandée
            $filterVariables = [];
            $userModelFilterService->storeLastFilter($this->modelName, $filterVariables); // optionnel : reset base
            $this->viewState->removeIsResetRequested($this->modelName);

        }
        elseif (!$this->userHasSentFilter) {
            // 📂 Pas de filtre envoyé = chargement auto
            $saved_filter = $userModelFilterService->getLastSavedFilter($this->modelName) ?? [];
            $filterVariables = array_merge($saved_filter,$filterVariables);
            foreach ($filterVariables as $key => $value) {
                $this->viewState->set("filter.{$this->modelName}.{$key}", $value);
            }
        } else {
            // ✅ Filtre soumis → sauvegarder
            $userModelFilterService->storeLastFilter($this->modelName, $filterVariables);
        }

        $this->filter($query,$this->model,$filterVariables);
      

        // Appliquer le tri multi-colonnes
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

    public function applySort($query, $sortFields)
    {
        if ($sortFields) {
            foreach ($sortFields as $field => $direction) {
    
                // 1. Vérifie si le champ a une définition sortByPath dans la relation manyToOne du modèle
                if (isset($this->model->manyToOne)) {
                    foreach ($this->model->manyToOne as $relationKey => $relationConfig) {
                        if (
                            isset($relationConfig['foreign_key']) &&
                            $relationConfig['foreign_key'] === $field &&
                            isset($relationConfig['sortByPath'])
                        ) {
                            $sortPath = $relationConfig['sortByPath'];
                            $sortSegments = explode('.', $sortPath);
                            $sortAlias = str_replace('.', '_', $sortPath);
    
                            // Génère un alias unique pour le tri, évite conflits de noms
                            $query->leftJoinRelation(implode('.', array_slice($sortSegments, 0, -1)))
                                  ->select("{$this->model->getTable()}.*")
                                  ->addSelect([
                                      "{$sortPath} as {$sortAlias}"
                                  ])
                                  ->orderBy($sortPath, $direction);
                            continue 2;
                        }
                    }
                }
    
                // 2. Sinon, appliquer un tri normal si le champ est sortable
                if (in_array($field, $this->getFieldsSortable())) {
                    $query->orderBy($field, $direction);
                }
            }
    
            return $query;
        }
    
        // Tri par défaut : 'ordre' si présent, sinon 'updated_at'
        $model = $query->getModel();
        if (Schema::hasColumn($model->getTable(), 'ordre')) {
            return $query->orderBy('ordre', 'asc');
        }
    
        return $query->orderBy('updated_at', 'desc');
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
