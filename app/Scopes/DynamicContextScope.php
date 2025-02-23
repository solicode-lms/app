<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Str;
use Modules\Core\Services\ContextState;
use Modules\Core\Services\ViewStateService;

class DynamicContextScope implements Scope
{
    /**
     * Appliquer les filtres dynamiques basés sur ViewState selon le modèle.
     *
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        // Désactiver le scope si la variable globale est activée
        // if (!$model::$activeScope) {
        //     return;
        // }

        // Obtenir l'instance de ViewState
        $viewState = app(ViewStateService::class);
        $contextState = app(ContextState::class);
        
        // Identifier la clé de scope basée sur le modèle
        $modelName =  Str::lcfirst(class_basename($model));
        $scopeVariables = $viewState->getScopeVariables($modelName);

           
        // Obtenir les colonnes disponibles dans le modèle
        $modelAttributes = $model->getFillable();

        // Charger automatiquement les relations nécessaires
        $relationsToLoad = [];

        foreach ($scopeVariables as $key => $value) {
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
                if (isset($scopeVariables[$foreignKey]) && !is_null($scopeVariables[$foreignKey])) {
                    $relationId = $scopeVariables[$foreignKey];
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
