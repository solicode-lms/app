<?php
 

namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseResourceService;

/**
 * Classe ResourceService pour gérer la persistance de l'entité Resource.
 */
class ResourceService extends BaseResourceService
{

      protected array $index_with_relations = ['projet'];


    public function dataCalcul($resource)
    {
        // En Cas d'édit
        if(isset($resource->id)){
          
        }
      
        return $resource;
    }
   
}
