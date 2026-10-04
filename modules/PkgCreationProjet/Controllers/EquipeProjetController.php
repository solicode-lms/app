<?php


namespace Modules\PkgCreationProjet\Controllers;


use Modules\PkgCreationProjet\Controllers\Base\BaseEquipeProjetController;

class EquipeProjetController extends BaseEquipeProjetController
{
    protected function dataForCreateView()
    {
        $viewData = parent::dataForCreateView();
        
        $projet_id = $this->viewState->get('scope.equipeProjet.projet_id');
        
        if ($projet_id) {
            $projet = $this->projetService->find($projet_id);
            if ($projet && $projet->groupe_id) {
                // Le groupe vient directement du projet
                $groupe_id = $projet->groupe_id;
                
                // Filtrer les apprenants appartenant à ce groupe
                $apprenants = $this->apprenantService->all()->filter(function($apprenant) use ($groupe_id) {
                    return $apprenant->groupes->where('id', $groupe_id)->isNotEmpty();
                });
                
                $viewData['apprenants'] = $apprenants;
            }
        }
        
        return $viewData;
    }

    protected function dataForEditView(string $id)
    {
        $viewData = parent::dataForEditView($id);
        
        $equipeProjet = $viewData['itemEquipeProjet'];
        $projet_id = $equipeProjet->projet_id;
        
        if ($projet_id) {
            $projet = $this->projetService->find($projet_id);
            if ($projet && $projet->groupe_id) {
                // Le groupe vient directement du projet
                $groupe_id = $projet->groupe_id;
                
                $apprenants = $this->apprenantService->all()->filter(function($apprenant) use ($groupe_id) {
                    return $apprenant->groupes->where('id', $groupe_id)->isNotEmpty();
                });
                
                $viewData['apprenants'] = $apprenants;
            }
        }
        
        return $viewData;
    }

}
