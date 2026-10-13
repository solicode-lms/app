<?php

namespace Modules\PkgTableauBord\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgTableauBord\Services\TableauBordService;

class TableauBordFormateurController extends AdminController
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
        $formateur = Formateur::where('user_id', $user->id)->first();

        // Récupérer l'affectation active (soit par filtre ID, soit la plus récente)
        $affectationProjetId = $request->get('affectation_projet_id');
        $affectationProjet = null;

        if ($affectationProjetId) {
            $affectationProjet = AffectationProjet::with(['projet', 'groupe'])->find($affectationProjetId);
        }

        if (!$affectationProjet) {
            $query = AffectationProjet::with(['projet', 'groupe']);
            if ($formateur) {
                $query->whereHas('projet', function ($q) use ($formateur) {
                    $q->where('formateur_id', $formateur->id);
                });
            }
            $affectationProjet = $query->latest('date_debut')->first();
        }

        if (!$affectationProjet) {
            $affectationProjet = AffectationProjet::with(['projet', 'groupe'])->latest('date_debut')->first();
        }

        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageFormateur($affectationProjet);
        $kpiGating = $this->tableauBordService->getIndicateurGatingFormateur($affectationProjet);

        return view('PkgTableauBord::formateur.index', compact('user', 'formateur', 'affectationProjet', 'kpiAmorcage', 'kpiGating'));
    }
}
