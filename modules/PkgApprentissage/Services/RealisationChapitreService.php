<?php


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseRealisationChapitreService;

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
