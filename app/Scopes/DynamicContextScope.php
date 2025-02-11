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
        // Obtenir l'instance de ViewState
        $viewState = app(ViewStateService::class);
        $contextState = app(ContextState::class);
        
        // Identifier la clé de scope basée sur le modèle
        $modelName =  Str::lcfirst(class_basename($model));
        $scopeVariables = $viewState->getScopeVariables($modelName);

           
        // Obtenir les colonnes disponibles dans le modèle
        $modelAttributes = $model->getFillable();

        // Appliquer les filtres spécifiques au modèle
        foreach ($scopeVariables as $key => $value) {
            if (in_array($key, $modelAttributes) && !is_null($value)) {
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

                    // Appliquer whereHas() dynamiquement
                    $builder->whereHas($relationName, function ($query) use ($relationId) {
                        $query->where('id', $relationId);
                    });
                }
            }
        }
    }
}
