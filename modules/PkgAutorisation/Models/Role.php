<?php
// la class Role doit hérité de la classe : Spatie\Permission\Models\Role
// 06/01/25


namespace Modules\PkgAutorisation\Models;

use App\Traits\HasReference;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{

    use HasReference;
    
    // TODO : ajouter ce code à GApp pour une rleation ManyToManyPolymorphique
    // il est déclarer dans : ModelsRole
    // Définir la relation inverse avec les modèles liés via morphique
    // public function users()
    // {
    //     return $this->morphedByMany(User::class, 'model', 'model_has_roles', 'role_id', 'model_id');
    // }


    public const FORMATEUR_ROLE = "formateur";
    public const GAPP_ROLE = "gapp";

    public const APPRENANT_ROLE = "apprenant";

    public function __toString()
    {
        return $this->name;
    }

}
