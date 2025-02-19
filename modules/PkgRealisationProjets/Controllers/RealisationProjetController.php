<?php

namespace Modules\PkgRealisationProjets\Controllers;

use Illuminate\Http\Request;

use Modules\PkgRealisationProjets\Controllers\Base\BaseRealisationProjetController;

class RealisationProjetController extends BaseRealisationProjetController
{
    public function index(Request $request) {
        $this->viewState->setContextKeyIfEmpty('realisationProjet.index');
        if($this->sessionState->get('apprenant_id')) $this->viewState->init('filter.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        return parent::index($request);
    }

}
