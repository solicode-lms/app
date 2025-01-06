<?php

namespace App\Policies;

use Modules\PkgAutorisation\Models\User;
use Modules\PkgCompetences\Models\Appreciation;

class AppreciationPolicy
{
    /**
     * Détermine si l'utilisateur peut modifier l'Appreciation.
     *
     * @param  \App\Models\User  $user
     * @param  \Modules\PkgCompetences\Models\Base\Appreciation  $appreciation
     * @return bool
     */
    public function update(User $user, Appreciation $appreciation)
    {
        // Autoriser seulement si l'utilisateur est le créateur (formateur_id)
        return $user->id === $appreciation->formateur_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer l'Appreciation.
     *
     * @param  \App\Models\User  $user
     * @param  \Modules\PkgCompetences\Models\Base\Appreciation  $appreciation
     * @return bool
     */
    public function delete(User $user, Appreciation $appreciation)
    {
        // Autoriser seulement si l'utilisateur est le créateur (formateur_id)
        return $user->id === $appreciation->formateur_id;
    }
}
