<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers;


use Modules\PkgCreationProjet\Controllers\Base\BaseProjetController;

class ProjetController extends BaseProjetController
{
    /**
     * Surcharge pour filtrer les groupes du formateur dans la création
     */
    protected function dataForCreateView(): array
    {
        $viewData = parent::dataForCreateView();
        
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->hasRole('formateur')) {
            $formateur = \Modules\PkgFormation\Models\Formateur::where('user_id', $user->id)->first();
            if ($formateur) {
                $viewData['groupes'] = $formateur->groupes;
            }
        }
        
        return $viewData;
    }

    /**
     * Surcharge pour filtrer les groupes du formateur dans l'édition
     */
    protected function dataForEditView(string $id): array
    {
        $viewData = parent::dataForEditView($id);
        
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user && $user->hasRole('formateur')) {
            $formateur = \Modules\PkgFormation\Models\Formateur::where('user_id', $user->id)->first();
            if ($formateur) {
                $viewData['groupes'] = $formateur->groupes;
            }
        }
        
        return $viewData;
    }
}
