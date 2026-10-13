<?php

namespace Modules\PkgTableauBord\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgTableauBord\Services\TableauBordService;

class TableauBordResponsableController extends AdminController
{
    protected TableauBordService $tableauBordService;

    public function __construct(TableauBordService $tableauBordService)
    {
        parent::__construct();
        $this->tableauBordService = $tableauBordService;
    }

    /**
     * @DynamicPermissionIgnore
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $groupeIds = $request->get('groupe_ids', []);
        if (!is_array($groupeIds) && !empty($groupeIds)) {
            $groupeIds = explode(',', $groupeIds);
        }

        $groupes = Groupe::orderBy('nom')->get();
        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageResponsable($groupeIds);
        $kpiGating = $this->tableauBordService->getIndicateurGatingResponsable($groupeIds);

        return view('PkgTableauBord::responsable.index', compact('user', 'groupes', 'kpiAmorcage', 'kpiGating'));
    }
}
