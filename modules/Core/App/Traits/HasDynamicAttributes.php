<?php

namespace Modules\Core\App\Traits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasDynamicAttributes
{
    protected static array $dynamicAttributes = [];

    public static function bootHasDynamicAttributes()
    {
        static::addGlobalScope('dynamicAttributes', function (Builder $builder) {
            $table = (new static)->getTable(); // Récupérer le nom de la table
        
            // Assurer la sélection de toutes les colonnes existantes
            $builder->select("{$table}.*");
        
            // Ajouter la colonne dynamique en plus des colonnes existantes
            foreach (static::$dynamicAttributes as $alias => $query) {
                $builder->addSelect(DB::raw("($query) as $alias"));
            }
        });
    }
   

    public static function addDynamicAttribute(string $name, string $query)
    {
        static::$dynamicAttributes[$name] = $query;
    }
}