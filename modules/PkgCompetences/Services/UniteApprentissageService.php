<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseUniteApprentissageService;

/**
 * Classe UniteApprentissageService pour gérer la persistance de l'entité UniteApprentissage.
 */
class UniteApprentissageService extends BaseUniteApprentissageService
{
    public function dataCalcul($uniteApprentissage)
    {
        // En Cas d'édit
        if(isset($uniteApprentissage->id)){
          
        }
      
        return $uniteApprentissage;
    }
   
}
