<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Services\Base\BaseRealisationProjetService;

/**
 * Classe RealisationProjetService pour gérer la persistance de l'entité RealisationProjet.
 */
class RealisationProjetService extends BaseRealisationProjetService
{
    public function dataCalcul($realisationProjet)
    {
        // En Cas d'édit
        if(isset($realisationProjet->id)){
          
        }
      
        return $realisationProjet;
    }


    public function initFieldsFilterable(){
        // Initialiser les filtres configurables dynamiquement
        
        $user = Auth::user();

        if($user->apprenant){
            $value =$user->apprenant->getFormateurId();
            $key = 'scope.etatsRealisationProjet.formateur_id';
            $this->viewState->set($key, $value);
        }
        if($user->formateur){
            $value =$user->formateur->id;
            $key = 'scope.etatsRealisationProjet.formateur_id';
            $this->viewState->set($key, $value);
        }
       
        parent::initFieldsFilterable();
     }
   
}
