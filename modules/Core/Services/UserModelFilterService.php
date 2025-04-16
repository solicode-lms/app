<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Services;
use Modules\Core\Services\Base\BaseUserModelFilterService;

/**
 * Classe UserModelFilterService pour gérer la persistance de l'entité UserModelFilter.
 */
class UserModelFilterService extends BaseUserModelFilterService
{
    public function dataCalcul($userModelFilter)
    {
        // En Cas d'édit
        if(isset($userModelFilter->id)){
          
        }
      
        return $userModelFilter;
    }
   
}
