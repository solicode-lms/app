<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseTechnologyService;

/**
 * Classe TechnologyService pour gérer la persistance de l'entité Technology.
 */
class TechnologyService extends BaseTechnologyService
{
    public function dataCalcul($technology)
    {
        // En Cas d'édit
        if(isset($technology->id)){
          
        }
      
        return $technology;
    }
}
