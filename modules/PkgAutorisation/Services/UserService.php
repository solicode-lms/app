<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Services;
use Modules\PkgAutorisation\Services\Base\BaseUserService;

/**
 * Classe UserService pour gérer la persistance de l'entité User.
 */
class UserService extends BaseUserService
{
    public function dataCalcul($user)
    {
        // En Cas d'édit
        if(isset($user->id)){
          
        }
      
        return $user;
    }
}
