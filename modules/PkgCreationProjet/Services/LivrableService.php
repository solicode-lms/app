<?php
 

namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseLivrableService;

/**
 * Classe LivrableService pour gérer la persistance de l'entité Livrable.
 */
class LivrableService extends BaseLivrableService
{

     protected array $index_with_relations = ['projet'];

    public function dataCalcul($livrable)
    {
        // En Cas d'édit
        if(isset($livrable->id)){
          
        }
      
        return $livrable;
    }
   
}
