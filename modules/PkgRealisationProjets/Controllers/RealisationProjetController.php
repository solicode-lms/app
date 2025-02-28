<?php

namespace Modules\PkgRealisationProjets\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgRealisationProjets\Controllers\Base\BaseRealisationProjetController;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;

class RealisationProjetController extends BaseRealisationProjetController
{
    public function index(Request $request) {

        $this->viewState->setContextKeyIfEmpty('realisationProjet.index');

        // TODO : ajouter commentaire
        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
            $request->merge(['formateur_id' => $this->sessionState->get('formateur_id')]);
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)){
            $request->merge(['scope_groupe_apprenant_id' => $this->sessionState->get('apprenant_id')]);
        } 

       
         
        // if($this->viewState->get('filter.realisationProjet.affectation_projet_id') == null){
         
        //     $this->viewState->init('filter.realisationProjet.affectation_projet_id'  , $this->sessionState->get('apprenant_id'));
        // }


        // Fix le filtre au projet en cours : pour le formateur et l'apprenant
        if ($this->viewState->get('filter.realisationProjet.affectation_projet_id') === null) {
            $userRole = Auth::user()->getRoleNames()->first(); // Récupérer le rôle principal
        
            $affectationService = new AffectationProjetService();
            $currentAffectationProjet = null;
        
            if ($userRole === 'formateur') {
                $currentAffectationProjet = $affectationService->getCurrentFormateurAffectation($this->sessionState->get('formateur_id'));
            } elseif ($userRole === 'apprenant') {
                $currentAffectationProjet = $affectationService->getCurrentApprenantAffectation($this->sessionState->get('apprenant_id'));
            }
        
            if ($currentAffectationProjet && $this->viewState->get('filter.realisationProjet.affectation_projet_id') == null ) {
                $this->viewState->init('filter.realisationProjet.affectation_projet_id', $currentAffectationProjet->id);
            }
        }
        

         

        return parent::index($request);
    }

}
