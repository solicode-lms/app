<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Services;
use Modules\PkgAutoformation\Services\Base\BaseFormationService;

/**
 * Classe FormationService pour gérer la persistance de l'entité Formation.
 */
class FormationService extends BaseFormationService
{
    public function dataCalcul($formation)
    {
        // En Cas d'édit
        if(isset($formation->id)){
          
        }
      
        return $formation;
    }
   
}
