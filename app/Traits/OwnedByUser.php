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

        $relations = explode('.', $this->ownerRelationPath);
        $owner = $this;

        foreach ($relations as $relation) {
            if (method_exists($owner, $relation)) {
                $owner = $owner->$relation;
            } else {
                return null; // Relation introuvable
            }
        }

        return $owner;
    }
    
}
