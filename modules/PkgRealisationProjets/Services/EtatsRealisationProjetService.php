<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Services;
use Modules\PkgRealisationProjets\Services\Base\BaseEtatsRealisationProjetService;

/**
 * Classe EtatsRealisationProjetService pour gérer la persistance de l'entité EtatsRealisationProjet.
 */
class EtatsRealisationProjetService extends BaseEtatsRealisationProjetService
{
    public function dataCalcul($etatsRealisationProjet)
    {
        // En Cas d'édit
        if(isset($etatsRealisationProjet->id)){
          
        }
      
        return $etatsRealisationProjet;
    }
   
}
