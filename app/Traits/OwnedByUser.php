<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\PkgAutorisation\Models\User;

trait OwnedByUser
{
    /**
     * Retourne l'utilisateur propriétaire de l'objet.
     *
     * @return \App\Models\User|null
     */
    public function getOwner(): ?User
    {
        $relations = $this->getRelationsToCheck();
        // Parcourt toutes les relations détectées dynamiquement
        foreach ($relations as $relation) {
            // Vérifie si la relation existe sur le modèle
            if (!method_exists($this, $relation)) {
                continue;
            }

           

            $related = $this->{$relation};
            if (!$related) {
                continue;
            }

            // Si la relation retourne directement un utilisateur
            if ($related instanceof User) {
                return $related;
            }

            // Si l'objet lié a une relation 'user', essaye de récupérer le User
            if (method_exists($related, 'user')) {
                return $related->user ?? null;
            }
        }

        return null; // Aucun utilisateur trouvé
    }

    /**
     * Détecte toutes les relations `belongsTo` du modèle.
     *
     * @return array
     */
    protected function getRelationsToCheck(): array
    {
        $relations = [];
        $reflection = new \ReflectionClass($this);
    
        foreach ($reflection->getMethods() as $method) {
            // Vérifie que la méthode est publique, non statique, et sans paramètre
            if ($method->isPublic() && !$method->isStatic() && $method->getNumberOfParameters() === 0) {
                // Vérifie le type de retour de la méthode
                $returnType = $method->getReturnType();
                if ($returnType instanceof \ReflectionNamedType) {
                    // Vérifie si le type est une relation BelongsTo
                    if ($returnType->getName() === \Illuminate\Database\Eloquent\Relations\BelongsTo::class) {
                        $relations[] = $method->getName();
                    }
                }
            }
        }
    
        return $relations;
    }
    
}
