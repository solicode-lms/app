<?php


namespace Modules\PkgRealisationTache\Controllers;

use Illuminate\Http\Request;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationTache\Controllers\Base\BaseWorkflowTacheController;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;

class WorkflowTacheController extends BaseWorkflowTacheController
{
    
    /**
     * @DynamicPermissionIgnore
     * Marque toutes les notifications de l'utilisateur courant comme lues.
    */
    public function resyncEtatsFormateurs(Request $request)
    {
         $this->authorizeAction('create');

        $total = $this->service->resyncEtatsFormateurs();

        $message =  "Synchronisation terminée : $total état(s) mis à jour.";

       

        if ($request->ajax()) {
            return JsonResponseHelper::success($message);
        }

        return redirect()->route('workflowTaches.index')->with(
            'success',
            $message
        );


    }

}
