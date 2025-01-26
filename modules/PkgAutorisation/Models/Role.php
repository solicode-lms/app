<?php
// la class Role doit hérité de la classe : Spatie\Permission\Models\Role
// 06/01/25


namespace Modules\PkgAutorisation\Models;

use App\Traits\HasReference;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{

    use HasReference;
    
    public const FORMATEUR_ROLE = "formateur";
    public const GAPP_ROLE = "gapp";
    public function __toString()
    {
        return $this->name;
    }

}
