<?php

namespace App\Policies;

use Modules\PkgAutorisation\Models\User;

class GenericPolicy
{
    /**
     * Vérifie si l'utilisateur peut créer un objet.
     *
     * @param  User  $user
     * @param  mixed  $model
     * @return bool
     */
    public function create(User $user, $model): bool
    {
        // Vérifie si le modèle utilise le trait OwnedByUser
        if ($this->hasTrait($model, 'App\Traits\OwnedByUser')) {
            $owners = $model->getUserOwners(); // Retourne un tableau d'utilisateurs

            if (empty($owners)) {
                return true; // Aucun propriétaire, donc libre de créer
            }

            return in_array($user->id, array_map(fn($owner) => $owner->id, $owners));
        }

        return true;
    }

    /**
     * Vérifie si l'utilisateur peut mettre à jour l'objet.
     */
    public function update(User $user, $model): bool
    {
        return $this->hasOwnership($user, $model);
    }

    /**
     * Vérifie si l'utilisateur peut modifier l'objet.
     */
    public function edit(User $user, $model): bool
    {
        return $this->hasOwnership($user, $model);
    }

    /**
     * Vérifie si l'utilisateur peut supprimer l'objet.
     */
    public function delete(User $user, $model): bool
    {
        return $this->hasOwnership($user, $model);
    }

    /**
     * Vérifie si l'utilisateur peut voir l'objet.
     */
    public function view(User $user, $model): bool
    {
        return $this->hasOwnership($user, $model);
    }

    /**
     * Vérifie si l'utilisateur est propriétaire du modèle.
     */
    protected function hasOwnership(User $user, $model): bool
    {
        if ($this->hasTrait($model, 'App\Traits\OwnedByUser')) {
            $owners = $model->getUserOwners();
            return in_array($user->id, array_map(fn($owner) => $owner->id, $owners));
        }
        return false;
    }

    /**
     * Vérifie si un modèle utilise un trait spécifique.
     */
    protected function hasTrait($model, string $trait): bool
    {
        return in_array($trait, $this->getAllTraits($model));
    }

    /**
     * Récupère tous les traits utilisés par une classe, y compris ceux hérités.
     */
    protected function getAllTraits($class): array
    {
        $traits = [];

        // Récupère les traits directement utilisés par la classe
        do {
            $traits = array_merge($traits, class_uses($class));
        } while ($class = get_parent_class($class));

        // Ajoute les traits imbriqués (utilisés par d'autres traits)
        foreach ($traits as $trait) {
            $traits = array_merge($traits, class_uses($trait));
        }

        return array_unique($traits);
    }
}
