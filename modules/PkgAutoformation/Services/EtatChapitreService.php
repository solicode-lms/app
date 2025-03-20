<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Services;
use Modules\PkgAutoformation\Services\Base\BaseEtatChapitreService;

/**
 * Classe EtatChapitreService pour gérer la persistance de l'entité EtatChapitre.
 */
class EtatChapitreService extends BaseEtatChapitreService
{
    public function dataCalcul($etatChapitre)
    {
        // En Cas d'édit
        if(isset($etatChapitre->id)){
          
        }
      
        return $etatChapitre;
    }
   
}
