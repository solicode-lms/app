<?php
 

namespace Modules\PkgAutorisation\Services;
use Modules\PkgAutorisation\Services\Base\BasePermissionService;

/**
 * Classe PermissionService pour gérer la persistance de l'entité Permission.
 */
class PermissionService extends BasePermissionService
{
     protected array $index_with_relations = ['roles'];

    public function dataCalcul($permission)
    {
        // En Cas d'édit
        if(isset($permission->id)){
          
        }
      
        return $permission;
    }
   
}
