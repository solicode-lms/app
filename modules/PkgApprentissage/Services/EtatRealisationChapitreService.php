<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseEtatRealisationChapitreService;

/**
 * Classe EtatRealisationChapitreService pour gérer la persistance de l'entité EtatRealisationChapitre.
 */
class EtatRealisationChapitreService extends BaseEtatRealisationChapitreService
{
    public function dataCalcul($etatRealisationChapitre)
    {
        // En Cas d'édit
        if(isset($etatRealisationChapitre->id)){
          
        }
      
        return $etatRealisationChapitre;
    }
   
}
