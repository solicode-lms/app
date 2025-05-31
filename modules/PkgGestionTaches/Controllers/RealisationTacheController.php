<?php
 

namespace Modules\PkgGestionTaches\Controllers;


use Modules\PkgGestionTaches\Controllers\Base\BaseRealisationTacheController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\Models\RealisationTache;

class RealisationTacheController extends BaseRealisationTacheController
{
    public function index(Request $request){

        // Le formateur peut être un évaluateur mais il n'a pas de rôle évaluateur
        // car l'évaluateur voi seulement les réalisation des tâches à évaluer

        $this->viewState->setContextKeyIfEmpty('realisationTache.index');
        
        if(Auth::user()->hasRole('evaluateur')){
            $this->viewState->init('realisationTache_view_type', "table-evaluation");

            // Il faut charger les realisationTache à evaluser si l'utilisateur est un formateur
        }
        return parent::index($request);
    }
    
}
