<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Services;
use Modules\PkgFormation\Services\Base\BaseFiliereService;

/**
 * Classe FiliereService pour gérer la persistance de l'entité Filiere.
 */
class FiliereService extends BaseFiliereService
{
    public function dataCalcul($filiere)
    {
        // En Cas d'édit
        if(isset($filiere->id)){
          
        }
      
        return $filiere;
    }
   
}
