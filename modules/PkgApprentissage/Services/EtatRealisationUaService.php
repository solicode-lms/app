<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Services;
use Modules\PkgApprentissage\Services\Base\BaseEtatRealisationUaService;

/**
 * Classe EtatRealisationUaService pour gérer la persistance de l'entité EtatRealisationUa.
 */
class EtatRealisationUaService extends BaseEtatRealisationUaService
{
    public function dataCalcul($etatRealisationUa)
    {
        // En Cas d'édit
        if(isset($etatRealisationUa->id)){
          
        }
      
        return $etatRealisationUa;
    }
   
}
