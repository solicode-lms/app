<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Services;
use Modules\PkgRealisationProjets\Services\Base\BaseAffectationProjetService;

/**
 * Classe AffectationProjetService pour gérer la persistance de l'entité AffectationProjet.
 */
class AffectationProjetService extends BaseAffectationProjetService
{
    public function dataCalcul($affectationProjet)
    {
        // En Cas d'édit
        if(isset($affectationProjet->id)){
          
        }
      
        return $affectationProjet;
    }
}
