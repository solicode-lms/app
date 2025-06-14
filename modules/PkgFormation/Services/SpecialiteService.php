<?php
 

namespace Modules\PkgFormation\Services;
use Modules\PkgFormation\Services\Base\BaseSpecialiteService;

/**
 * Classe SpecialiteService pour gérer la persistance de l'entité Specialite.
 */
class SpecialiteService extends BaseSpecialiteService
{
     protected array $index_with_relations = ['formateurs'];


    public function dataCalcul($specialite)
    {
        // En Cas d'édit
        if(isset($specialite->id)){
          
        }
      
        return $specialite;
    }
   
}
