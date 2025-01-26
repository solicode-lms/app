<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasReference
{
    /**
     * Boot le trait pour gérer les événements du modèle.
     */
    protected static function bootHasReference()
    {
        static::creating(function ($model) {
            if ($model->hasColumn('reference')) {
                $model->reference = $model->generateReference();
            }
        });

        static::updating(function ($model) {
            if ($model->hasColumn('reference')) {
                $model->reference = $model->generateReference();
            }
        });
    }

    /**
     * Génère une référence unique.
     *
     * @return string
     */
    public function generateReference(): string
    {
        return Str::uuid(); // Par défaut, une UUID
    }

    /**
     * Vérifie si une colonne existe dans la table du modèle.
     *
     * @param string $column
     * @return bool
     */
    public function hasColumn(string $column): bool
    {
        $value =  in_array($column, $this->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($this->getTable()));
        return $value;
    }

    /**
     * Permet de définir une référence personnalisée.
     *
     * @param string $reference
     * @return void
     */
    public function setCustomReference(string $reference): void
    {
        if ($this->hasColumn('reference')) {
            $this->reference = $reference;
        }
    }
}
