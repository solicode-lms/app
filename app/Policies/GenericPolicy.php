<?php

namespace App\Policies;

use Modules\PkgAutorisation\Models\User;

class GenericPolicy
{
    /**
     * Vérifie si l'utilisateur peut mettre à jour l'objet.
     *
     * @param  User  $user
     * @param  mixed  $model
     * @return bool
     */
    public function update(User $user, $model): bool
    {
        // Vérifie si le modèle utilise le trait OwnedByUser
        if ($this->hasTrait($model, 'App\Traits\OwnedByUser')) {
            $owner = $model->getOwner();
            return $owner && $owner->id === $user->id;
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur peut supprimer l'objet.
     *
     * @param  User  $user
     * @param  mixed  $model
     * @return bool
     */
    public function delete(User $user, $model): bool
    {
        // Vérifie si le modèle utilise le trait OwnedByUser
        if ($this->hasTrait($model, 'App\Traits\OwnedByUser')) {
            $owner = $model->getOwner();
            return $owner && $owner->id === $user->id;
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur peut voir l'objet.
     *
     * @param  User  $user
     * @param  mixed  $model
     * @return bool
     */
    public function view(User $user, $model): bool
    {
        // Vérifie si le modèle utilise le trait OwnedByUser
        if ($this->hasTrait($model, 'App\Traits\OwnedByUser')) {
            $owner = $model->getOwner();
            return $owner && $owner->id === $user->id;
        }

        return false;
    }

    /**
     * Vérifie si un modèle utilise un trait spécifique (directement ou via héritage).
     *
     * @param  mixed  $model
     * @param  string $trait
     * @return bool
     */
    protected function hasTrait($model, string $trait): bool
    {
        return in_array($trait, $this->getAllTraits($model));
    }

    /**
     * Récupère tous les traits utilisés par une classe, y compris ceux hérités.
     *
     * @param  mixed  $class
     * @return array
     */
    protected function getAllTraits($class): array
    {
        $traits = [];

        // Récupère les traits directement utilisés par la classe
        do {
            $traits = array_merge($traits, class_uses($class));
        } while ($class = get_parent_class($class));

        // Ajoute les traits utilisés par les traits eux-mêmes (traits imbriqués)
        foreach ($traits as $trait) {
            $traits = array_merge($traits, class_uses($trait));
        }

        return array_unique($traits); // Élimine les doublons
    }
}
