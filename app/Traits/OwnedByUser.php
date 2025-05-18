<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Collection;

trait OwnedByUser
{
 
    
    // public function getUserOwners(): array
    // {
    //     $users = [];

    //     if (!$this->isOwnedByUser || empty($this->ownerRelationPath)) {
    //         return $users; // Retourne un tableau vide si aucune relation n'est définie
    //     }
    
    //     // Séparer les différents chemins possibles
    //     $relationPaths = explode(',', $this->ownerRelationPath);
    
    //     foreach ($relationPaths as $path) {
    //         $relations = explode('.', trim($path)); // Trim pour éviter les espaces indésirables
    //         $owner = $this;
    
    //         foreach ($relations as $relation) {
    //             if (method_exists($owner, $relation)) {
    //                 if ($owner->$relation === null) {
    //                     $owner = null;
    //                     break; // Arrêter cette itération et essayer un autre chemin
    //                 }
    //                 $owner = $owner->$relation;
    //             } else {
    //                 $owner = null;
    //                 break; // Arrêter si une relation est introuvable
    //             }
    //         }
    
    //         // Vérifier si le propriétaire final est bien un utilisateur et l'ajouter à la liste
    //         if ($owner instanceof User && !in_array($owner, $users)) {
    //             $users[] = $owner;
    //         }
    //     }
    
    //     return $users;
    // }

    /**
     * Retourne la liste des utilisateurs propriétaires en suivant
     * un ou plusieurs chemins relationnels, incluant BelongsTo et BelongsToMany.
     *
     * @return User[]
     */
    public function getUserOwners(): array
    {
        $users = [];

        if (! $this->isOwnedByUser || empty($this->ownerRelationPath)) {
            return $users;
        }

        // Séparer plusieurs chemins par virgule
        $paths = explode(',', $this->ownerRelationPath);

        foreach ($paths as $path) {
            $segments = explode('.', trim($path));
            // Démarrer avec l'objet courant
            $candidates = [$this];

            // Parcourir chaque segment de relation
            foreach ($segments as $relation) {
                $next = [];
                foreach ($candidates as $candidate) {
                    if (! method_exists($candidate, $relation)) {
                        continue;
                    }
                    $result = $candidate->$relation;
                    if ($result instanceof Collection) {
                        // belongsToMany ou hasMany
                        foreach ($result as $item) {
                            $next[] = $item;
                        }
                    } elseif ($result !== null) {
                        // belongsTo ou hasOne
                        $next[] = $result;
                    }
                }
                $candidates = $next;
                if (empty($candidates)) {
                    break;
                }
            }

            // Ajouter les utilisateurs finaux sans doublons
            foreach ($candidates as $owner) {
                if ($owner instanceof User && ! in_array($owner, $users, true)) {
                    $users[] = $owner;
                }
            }
        }

        return $users;
    }
    
    
}
