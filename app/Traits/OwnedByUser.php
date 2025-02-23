<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\PkgAutorisation\Models\User;

trait OwnedByUser
{
 
    
    public function getUserOwners(): array
    {
        $users = [];

        if (!$this->isOwnedByUser || empty($this->ownerRelationPath)) {
            return $users; // Retourne un tableau vide si aucune relation n'est définie
        }
    
        // Séparer les différents chemins possibles
        $relationPaths = explode(',', $this->ownerRelationPath);
    
        foreach ($relationPaths as $path) {
            $relations = explode('.', trim($path)); // Trim pour éviter les espaces indésirables
            $owner = $this;
    
            foreach ($relations as $relation) {
                if (method_exists($owner, $relation)) {
                    if ($owner->$relation === null) {
                        $owner = null;
                        break; // Arrêter cette itération et essayer un autre chemin
                    }
                    $owner = $owner->$relation;
                } else {
                    $owner = null;
                    break; // Arrêter si une relation est introuvable
                }
            }
    
            // Vérifier si le propriétaire final est bien un utilisateur et l'ajouter à la liste
            if ($owner instanceof User && !in_array($owner, $users)) {
                $users[] = $owner;
            }
        }
    
        return $users;
    }
    
    
}
