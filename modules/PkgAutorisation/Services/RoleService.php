<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Services;
use Modules\PkgAutorisation\Services\Base\BaseRoleService;

/**
 * Classe RoleService pour gérer la persistance de l'entité Role.
 */
class RoleService extends BaseRoleService
{
    public function dataCalcul($role)
    {
        // En Cas d'édit
        if(isset($role->id)){
          
        }
      
        return $role;
    }
   
}
