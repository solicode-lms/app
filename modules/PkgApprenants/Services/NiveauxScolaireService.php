<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Services;
use Modules\PkgApprenants\Services\Base\BaseNiveauxScolaireService;

/**
 * Classe NiveauxScolaireService pour gérer la persistance de l'entité NiveauxScolaire.
 */
class NiveauxScolaireService extends BaseNiveauxScolaireService
{
    public function dataCalcul($niveauxScolaire)
    {
        // En Cas d'édit
        if(isset($niveauxScolaire->id)){
          
        }
      
        return $niveauxScolaire;
    }
   
}
