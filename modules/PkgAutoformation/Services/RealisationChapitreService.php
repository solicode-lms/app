<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Services;
use Modules\PkgAutoformation\Services\Base\BaseRealisationChapitreService;

/**
 * Classe RealisationChapitreService pour gérer la persistance de l'entité RealisationChapitre.
 */
class RealisationChapitreService extends BaseRealisationChapitreService
{
    public function dataCalcul($realisationChapitre)
    {
        // En Cas d'édit
        if(isset($realisationChapitre->id)){
          
        }
      
        return $realisationChapitre;
    }
   
}
