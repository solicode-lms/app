<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasReference
{
    /**
     * Indique si la référence peut être mise à jour après la création.
     *
     * @var bool
     */
    protected bool $allowReferenceUpdate = true;

    /**
     * Boot le trait pour gérer les événements du modèle.
     */
    protected static function bootHasReference()
    {
        static::creating(function ($model) {
            if ($model->hasColumn('reference') && empty($model->reference)) {
                $model->reference = $model->generateReference();
            }
        });

        static::updating(function ($model) {
            if ($model->hasColumn('reference') && $model->shouldUpdateReference()) {
                $model->reference = $model->generateReference();
            }
        });

        static::saving(function ($model) {
            if ($model->exists && $model->hasColumn('reference') && $model->shouldUpdateReference()) {
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
     * Vérifie si la référence peut être mise à jour.
     *
     * @return bool
     */
    public function shouldUpdateReference(): bool
    {
        return $this->allowReferenceUpdate;
    }

    /**
     * Active ou désactive la mise à jour automatique de la référence.
     *
     * @param bool $allow
     * @return $this
     */
    public function setAllowReferenceUpdate(bool $allow): self
    {
        $this->allowReferenceUpdate = $allow;
        return $this;
    }

    /**
     * Vérifie si une colonne existe dans la table du modèle.
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
