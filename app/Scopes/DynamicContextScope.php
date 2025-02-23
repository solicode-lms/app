<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Str;
use Modules\Core\Services\ContextState;
use Modules\Core\Services\Traits\QueryBuilderTrait;
use Modules\Core\Services\ViewStateService;

class DynamicContextScope implements Scope
{

    use QueryBuilderTrait;


    /**
     * Appliquer les filtres dynamiques basés sur ViewState selon le modèle.
     *
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        // Désactiver le scope si la variable globale est activée
        if (!$model::$activeScope) {
            return;
        }

        // Obtenir l'instance de ViewState
        $viewState = app(ViewStateService::class);
        // Identifier la clé de scope basée sur le modèle
        $modelName =  Str::lcfirst(class_basename($model));

        
        $scopeVariables = $viewState->getScopeVariables($modelName);
        
    

        $this->filter($builder,$model,$scopeVariables);

    }


}
