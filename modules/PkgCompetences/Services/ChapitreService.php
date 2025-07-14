<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseChapitreService;

/**
 * Classe ChapitreService pour gérer la persistance de l'entité Chapitre.
 */
class ChapitreService extends BaseChapitreService
{
    public function dataCalcul($chapitre)
    {
        // En Cas d'édit
        if(isset($chapitre->id)){
          
        }
      
        return $chapitre;
    }
   
}
