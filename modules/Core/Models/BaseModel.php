<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    public bool $isOwnedByUser = false;

    /**
     * Boot method pour gérer les événements du modèle.
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement une référence unique lors de la création
        static::creating(function ($model) {
            // Vérifier si le champ 'reference' existe avant de tenter de l'utiliser
            if ($model->hasColumn('reference') && !$model->reference) {
                $model->reference = $model->createReference();
            }
        });
    }

    /**
     * Créer une référence unique (UUID par défaut).
     *
     * @return string
     */
    public function createReference()
    {
        return Str::uuid(); // Peut être remplacé par une autre logique si nécessaire
    }

    /**
     * Vérifie si une colonne existe dans le modèle.
     *
     * @param string $column
     * @return bool
     */
    public function hasColumn(string $column): bool
    {
        return in_array($column, $this->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($this->getTable()));
    }

    /**
     * Définir une référence personnalisée.
     *
     * @param string $value
     */
    public function setCustomReference($value)
    {
        if ($this->hasColumn('reference')) {
            $this->reference = $value;
        }
    }
}
