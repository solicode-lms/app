<?php

namespace Modules\Core\App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasDynamicAttributes
{
    protected array $dynamicAttributes = []; // Rend la variable spécifique à l'instance

    public static function bootHasDynamicAttributes()
    {
        static::addGlobalScope('dynamicAttributes', function (Builder $builder) {
            $model = new static;

            // Vérifier si le modèle a des attributs dynamiques
            if (!empty($model->dynamicAttributes)) {
                $table = $model->getTable();

                // Sélectionner les colonnes du modèle
                $builder->select("{$table}.*");

                // Ajouter les colonnes dynamiques
                foreach ($model->dynamicAttributes as $alias => $query) {
                    $builder->addSelect(DB::raw("($query) as $alias"));
                }
            }
        });
    }

    public function addDynamicAttribute(string $name, string $query)
    {
        $this->dynamicAttributes[$name] = $query;
    }

    public function getDynamicAttributes(): array
    {
        return $this->dynamicAttributes;
    }
}
