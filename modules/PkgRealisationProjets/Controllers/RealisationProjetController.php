<?php

namespace Modules\PkgRealisationProjets\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgRealisationProjets\Controllers\Base\BaseRealisationProjetController;

class RealisationProjetController extends BaseRealisationProjetController
{
    public function index(Request $request) {


        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
            $request->merge(['formateur_id' => $this->sessionState->get('formateur_id')]);
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)){
            $request->merge(['scope_groupe_apprenant_id' => $this->sessionState->get('apprenant_id')]);
        } 

       
        $this->viewState->setContextKeyIfEmpty('realisationProjet.index');
        if($this->sessionState->get('apprenant_id')) $this->viewState->init('filter.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));

        return parent::index($request);
    }

}
