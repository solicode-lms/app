<?php
// la class Role doit hérité de la classe : Spatie\Permission\Models\Role
// 06/01/25


namespace Modules\PkgAutorisation\Models;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{

    public const FORMATEUR_ROLE = "formateur";
    public function __toString()
    {
        return $this->name;
    }

}
