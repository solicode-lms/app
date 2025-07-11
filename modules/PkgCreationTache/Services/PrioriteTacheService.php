<?php
 

namespace Modules\PkgCreationTache\Services;
use Modules\PkgCreationTache\Services\Base\BasePrioriteTacheService;

/**
 * Classe PrioriteTacheService pour gérer la persistance de l'entité PrioriteTache.
 */
class PrioriteTacheService extends BasePrioriteTacheService
{
    protected array $index_with_relations = ['formateur'];
    

    public function dataCalcul($prioriteTache)
    {
        // En Cas d'édit
        if(isset($prioriteTache->id)){
          
        }
      
        return $prioriteTache;
    }
   
}
