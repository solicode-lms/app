<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Services;

use Modules\PkgCompetences\Models\Technology;
use Modules\PkgCompetences\Services\Base\BaseCompetenceService;
use Modules\PkgFormation\Services\ModuleService;

/**
 * Classe CompetenceService pour gérer la persistance de l'entité Competence.
 */
class CompetenceService extends BaseCompetenceService
{
    public function dataCalcul($competence)
    {

        if(isset($competence->id)){
            $competence->code = $competence->code  . "+";
        }
      
        
        return $competence;
    }
}
