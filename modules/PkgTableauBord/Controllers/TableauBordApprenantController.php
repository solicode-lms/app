<?php

namespace Modules\PkgTableauBord\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgTableauBord\Services\TableauBordService;

class TableauBordApprenantController extends AdminController
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
        $apprenant = Apprenant::where('user_id', $user->id)->first();

        // Récupérer le projet en cours de l'apprenant
        $realisationProjet = null;
        if ($apprenant) {
            $realisationProjet = RealisationProjet::with([
                'affectationProjet.projet',
                'affectationProjet.groupe',
                'apprenant'
            ])
            ->where('apprenant_id', $apprenant->id)
            ->latest('date_debut')
            ->first();
        }

        // Fallback pour la prévisualisation si aucun projet direct
        if (!$realisationProjet) {
            $realisationProjet = RealisationProjet::with([
                'affectationProjet.projet',
                'affectationProjet.groupe',
                'apprenant'
            ])->latest('date_debut')->first();
        }

        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageApprenant($realisationProjet);
        $kpiGating = $this->tableauBordService->getIndicateurGatingApprenant($realisationProjet);

        return view('PkgTableauBord::apprenant.index', compact('user', 'apprenant', 'realisationProjet', 'kpiAmorcage', 'kpiGating'));
    }
}
