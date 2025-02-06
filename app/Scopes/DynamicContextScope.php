<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Modules\Core\Services\ContextState;

class DynamicContextScope implements Scope
{
    /**
     * Appliquer les filtres dynamiques basés sur ContexteState.
     *
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        // Obtenir l'instance de ContexteState
        $contextState = app(ContextState::class);

        // Récupérer toutes les variables du contexte
        $contextVariables = $contextState->all();

        // TODO : il ne support pas HasMany et ManyToMany relation
        // groupe et formateur_id par exemple
        // Obtenir les colonnes disponibles dans le modèle
        $modelAttributes = $model->getFillable();

        // Parcourir les variables de ContexteState et appliquer les filtres correspondants
        foreach ($contextVariables as $key => $value) {
            if (in_array($key, $modelAttributes) && !is_null($value)) {
                $builder->where($key, $value);
            }
        }
    }
}
