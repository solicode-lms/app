<?php


namespace Modules\PkgRealisationProjets\Controllers;


use Modules\PkgRealisationProjets\Controllers\Base\BaseAffectationProjetController;

class AffectationProjetController extends BaseAffectationProjetController
{

    // public function create() {
    //     // TODO: add metaData : SelectByAffectedUser : { modelUserName : "formateur" }
    //     $this->viewState->set('scope.groupe.formateur_id', auth()->user()->formateur->id);
    //     return parent::create();
    // }

    // public function edit(string $id) {

    //     // TODO: add metaData : SelectByAffectedUser : { modelUserName : "formateur" }
    //     $this->viewState->setContextKey('affectationProjet.edit_' . $id);
        
    //     $this->viewState->set('scope.groupe.formateur_id', auth()->user()->formateur->id);
    //     return parent::edit($id);
    // }
}
