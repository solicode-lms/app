<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\PkgAutorisation\Models\User;

trait OwnedByUser
{
 
    
    public function getUserOwner()
    {
        if (!$this->isOwnedByUser || empty($this->ownerRelationPath)) {
            return null; // Si pas de gestion de propriété, retourne null
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
    
            // Si un chemin aboutit à un propriétaire valide, on retourne immédiatement
            if ($owner !== null) {
                return $owner;
            }
        }
    
        return null; // Aucun chemin valide trouvé
    }
    
    
}
