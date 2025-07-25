<?php


namespace Modules\PkgSessions\Controllers;

use Modules\PkgCompetences\Services\UniteApprentissageService;
use Modules\PkgSessions\Controllers\Base\BaseAlignementUaController;

class AlignementUaController extends BaseAlignementUaController
{
    public function create() {


        $itemAlignementUa = $this->alignementUaService->createInstance();
        

        $uniteApprentissages = $this->uniteApprentissageService->all();
        $sessionFormations = $this->sessionFormationService->all();

        $bulkEdit = false;


        $uniteApprentissageService =  new UniteApprentissageService();
        $uniteApprentissages_view_data = $uniteApprentissageService->prepareDataForIndexView();
        extract($uniteApprentissages_view_data);


        if (request()->ajax()) {
            return view('PkgSessions::alignementUa._fields', array_merge( compact('bulkEdit' ,'itemAlignementUa', 'sessionFormations', 'uniteApprentissages'),$uniteApprentissage_compact_value));
        }
        return view('PkgSessions::alignementUa.create', array_merge( compact('bulkEdit' ,'itemAlignementUa', 'sessionFormations', 'uniteApprentissages'),$uniteApprentissage_compact_value));
    }

}
