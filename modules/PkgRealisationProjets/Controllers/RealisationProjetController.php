<?php

namespace Modules\PkgRealisationProjets\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\PkgRealisationProjets\Controllers\Base\BaseRealisationProjetController;

class RealisationProjetController extends BaseRealisationProjetController
{
    public function index(Request $request) {

        // 
        $this->viewState->setContextKeyIfEmpty('realisationProjet.index');
        if($this->sessionState->get('apprenant_id')) $this->viewState->init('filter.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        

        // Scope etatRéalistion de formateur 
        if(Auth::user()->hasRole("formateur")){

            $value = $this->sessionState->get('formateur_id');
            $key = 'scope.etatsRealisationProjet.formateur_id';
            $this->viewState->set($key, $value);

            // $value = $this->sessionState->get('formateur_id');
            // $key = 'scope.apprenant.groupes.formateurs.id';
            // $this->viewState->set($key, $value);
        }
       

        return parent::index($request);
    }

}
