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

        // Résolution du Projet actif (session de formation du formateur)
        $requestedProjetId = $request->get('projet_id') ?? $request->get('session_formation_id');
        $projets = $this->tableauBordService->getSessionsProjetDisponibles($user);
        $projetActif = $this->tableauBordService->resolveActiveProjet($requestedProjetId, $user);

        $groupes = Groupe::orderBy('nom')->get();
        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageResponsable($groupeIds, $projetActif?->id);
        $kpiGating = $this->tableauBordService->getIndicateurGatingResponsable($groupeIds, $projetActif?->id);
        $kpiRevision = $this->tableauBordService->getIndicateurRevisionResponsable($groupeIds, $projetActif?->id);
        $kpiChargeLogistique = $this->tableauBordService->getIndicateurChargeLogistiqueResponsable();
        $kpiFileAttente = $this->tableauBordService->getIndicateurFileAttenteResponsable($groupeIds, $projetActif?->id);
        $kpiIntervention = $this->tableauBordService->getIndicateurInterventionResponsable($groupeIds, $projetActif?->id);
        $kpiIntegriteIA = $this->tableauBordService->getIndicateurIntegriteResponsable($groupeIds, $projetActif?->id);
        $kpiCouverture = $this->tableauBordService->getIndicateurCouvertureResponsable($groupeIds, $projetActif?->id);
        $kpiDocimologie = $this->tableauBordService->getIndicateurDocimologieResponsable($groupeIds, $projetActif?->id);

        return view('PkgTableauBord::responsable.index', [
            'user' => $user,
            'groupes' => $groupes,
            'kpiAmorcage' => $kpiAmorcage,
            'kpiGating' => $kpiGating,
            'kpiRevision' => $kpiRevision,
            'kpiChargeLogistique' => $kpiChargeLogistique,
            'kpiFileAttente' => $kpiFileAttente,
            'kpiIntervention' => $kpiIntervention,
            'kpiIntegriteIA' => $kpiIntegriteIA,
            'kpiCouverture' => $kpiCouverture,
            'kpiDocimologie' => $kpiDocimologie,
            'projets' => $projets,
            'projetActif' => $projetActif,
            'sessions' => $projets,
            'sessionFormationActive' => $projetActif,
        ]);
    }
}
