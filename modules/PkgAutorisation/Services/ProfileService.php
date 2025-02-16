<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Services;
use Modules\PkgAutorisation\Services\Base\BaseProfileService;

/**
 * Classe ProfileService pour gérer la persistance de l'entité Profile.
 */
class ProfileService extends BaseProfileService
{
    public function dataCalcul($profile)
    {
        // En Cas d'édit
        if(isset($profile->id)){
          
        }
      
        return $profile;
    }
}
