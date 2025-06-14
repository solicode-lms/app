<?php

namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseNiveauCompetenceService;

/**
 * Classe NiveauCompetenceService pour gérer la persistance de l'entité NiveauCompetence.
 */
class NiveauCompetenceService extends BaseNiveauCompetenceService
{
     protected array $index_with_relations = ['competence'];


    public function dataCalcul($niveauCompetence)
    {
        // En Cas d'édit
        if(isset($niveauCompetence->id)){
          
        }
      
        return $niveauCompetence;
    }
   
}
