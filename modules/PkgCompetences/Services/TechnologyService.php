<?php

namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseTechnologyService;

/**
 * Classe TechnologyService pour gérer la persistance de l'entité Technology.
 */
class TechnologyService extends BaseTechnologyService
{
    protected array $index_with_relations = ['categoryTechnology'];

    public function dataCalcul($technology)
    {
        // En Cas d'édit
        if(isset($technology->id)){
          
        }
      
        return $technology;
    }
   
}
