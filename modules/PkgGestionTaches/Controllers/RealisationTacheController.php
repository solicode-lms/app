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

        $this->viewState->setContextKeyIfEmpty('realisationTache.index');
        
        if(Auth::user()->hasRole('evaluateur') && !Auth::user()->hasRole('formateur')){
            $this->viewState->init('realisationTache_view_type', "table-evaluation");
        }
        return parent::index($request);
    }
    
}
