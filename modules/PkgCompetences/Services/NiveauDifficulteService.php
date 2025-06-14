<?php


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseNiveauDifficulteService;

/**
 * Classe NiveauDifficulteService pour gérer la persistance de l'entité NiveauDifficulte.
 */
class NiveauDifficulteService extends BaseNiveauDifficulteService
{
    protected array $index_with_relations = ['formateur'];

    public function dataCalcul($niveauDifficulte)
    {
        // En Cas d'édit
        if(isset($niveauDifficulte->id)){
          
        }
      
        return $niveauDifficulte;
    }
   
}
