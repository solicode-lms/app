<?php


namespace Modules\PkgCompetences\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Services\ContextState;
use Modules\PkgCompetences\App\Requests\AppreciationRequest;
use Modules\PkgCompetences\Controllers\Base\BaseAppreciationController;
use Modules\PkgCompetences\Models\Appreciation;

class AppreciationController extends BaseAppreciationController
{
    
    public function index(Request $request)
    {
        $user = Auth::user(); // Récupérer l'utilisateur connecté

        // Vérifiez si le rôle est formateur
        if ($user->roles->contains('name', 'formateur')) {
            $formateur = $user->formateur; // Récupérer le formateur associé
            if ($formateur) {
                app(ContextState::class)->set('formateur_id', $formateur->id);
            } else {
                return response()->json(['error' => 'Aucun formateur associé à cet utilisateur.'], 403);
            }
        } 
        return parent::index($request);
    }

    public function update(AppreciationRequest $request, string $id)
    {
        $appreciation = Appreciation::findOrFail($id);
        $this->authorize('update', $appreciation);
        return parent::update($request,$id);
    }

    public function destroy(Request $request, string $id)
    {
        $appreciation = Appreciation::findOrFail($id);
        $this->authorize('delete', $appreciation);
        return parent::destroy($request,$id);
    }
}
