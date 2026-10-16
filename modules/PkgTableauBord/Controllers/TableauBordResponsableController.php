<?php

namespace Modules\PkgTableauBord\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgFormation\Models\Filiere;
use Modules\PkgCreationProjet\Models\Projet;
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
        if (!$user || !$user->hasAnyRole([Role::ADMIN_ROLE, 'root', 'responsable'])) {
            abort(403, "Accès refusé. Cette interface de gouvernance est réservée aux responsables et administrateurs.");
        }

        // 1. Récupération des filtres de la requête
        $selectedFormateurId = $request->filled('formateur_id') ? (int) $request->get('formateur_id') : null;
        $selectedFiliereId = $request->filled('filiere_id') ? (int) $request->get('filiere_id') : null;
        $selectedGroupeId = $request->filled('groupe_id') ? (int) $request->get('groupe_id') : null;
        $selectedProjetId = $request->filled('projet_id') 
            ? (int) $request->get('projet_id') 
            : ($request->filled('session_formation_id') ? (int) $request->get('session_formation_id') : null);
        $filterType = $request->get('type_projet', $request->get('filter_type', 'sessions')); // 'sessions' (défaut) ou 'all'

        // 2. Collections de référence pour les sélecteurs
        $formateurs = Formateur::orderBy('nom')->get();
        $filieres = Filiere::orderBy('nom')->get();

        // Groupes contextuels selon filière et/ou formateur
        $groupesQuery = Groupe::query();
        if ($selectedFiliereId) {
            $groupesQuery->where('filiere_id', $selectedFiliereId);
        }
        if ($selectedFormateurId) {
            $groupesQuery->whereHas('formateurs', fn($q) => $q->where('formateurs.id', $selectedFormateurId));
        }
        $groupes = $groupesQuery->orderBy('nom')->get();

        if ($selectedGroupeId && !$groupes->contains('id', $selectedGroupeId)) {
            $selectedGroupeId = null;
        }

        // Projets contextuels
        $projetsQuery = Projet::query()
            ->with(['formateur', 'filiere'])
            ->whereHas('affectationProjets')
            ->withExists(['taches as has_live_coding' => function ($sub) {
                $sub->whereHas('phaseEvaluation', fn($pe) => $pe->where('code', 'N2'));
            }])
            ->orderBy('titre');

        if ($filterType === 'sessions') {
            $projetsQuery->sessionsFormation();
        }
        if ($selectedFiliereId) {
            $projetsQuery->where('filiere_id', $selectedFiliereId);
        }
        if ($selectedFormateurId) {
            $projetsQuery->where('formateur_id', $selectedFormateurId);
        }
        if ($selectedGroupeId) {
            $projetsQuery->whereHas('affectationProjets', fn($q) => $q->where('groupe_id', $selectedGroupeId));
        }
        $projets = $projetsQuery->get();

        $projetActif = null;
        if ($selectedProjetId) {
            $projetActif = $projets->firstWhere('id', $selectedProjetId);
            if (!$projetActif) {
                $projetActif = Projet::with(['formateur', 'filiere'])->find($selectedProjetId);
            }
        }

        // 3. Détermination de la nature du projet sélectionné (Session de formation N2 vs Projet standard)
        $isProjetSessionFormation = $projetActif ? $projetActif->isSessionFormation() : true;
        $isVisionGlobale = empty($selectedFormateurId) && empty($selectedFiliereId) && empty($selectedGroupeId) && empty($selectedProjetId);

        // 4. Détermination des groupes cibles pour le calcul
        if ($selectedGroupeId) {
            $targetGroupeIds = [$selectedGroupeId];
        } elseif ($selectedFiliereId || $selectedFormateurId) {
            $targetGroupeIds = $groupes->pluck('id')->toArray();
            if (empty($targetGroupeIds)) {
                $targetGroupeIds = [-1]; // aucun groupe ne correspond
            }
        } else {
            $targetGroupeIds = []; // vision globale : tous les groupes
        }

        $targetProjetId = $projetActif?->id;

        // 5. Calcul des indicateurs
        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageResponsable($targetGroupeIds, $targetProjetId);
        $kpiRevision = $this->tableauBordService->getIndicateurRevisionResponsable($targetGroupeIds, $targetProjetId);
        $kpiChargeLogistique = $this->tableauBordService->getIndicateurChargeLogistiqueResponsable($selectedFormateurId, $targetProjetId);
        $kpiIntervention = $this->tableauBordService->getIndicateurInterventionResponsable($targetGroupeIds, $targetProjetId);

        // Indicateurs conditionnés par la présence d'une Session de Formation (Live Coding N2 & UA)
        if ($isProjetSessionFormation) {
            $kpiGating = $this->tableauBordService->getIndicateurGatingResponsable($targetGroupeIds, $targetProjetId);
            $kpiFileAttente = $this->tableauBordService->getIndicateurFileAttenteResponsable($targetGroupeIds, $targetProjetId);
            $kpiIntegriteIA = $this->tableauBordService->getIndicateurIntegriteResponsable($targetGroupeIds, $targetProjetId);
            $kpiCouverture = $this->tableauBordService->getIndicateurCouvertureResponsable($targetGroupeIds, $targetProjetId);
            $kpiDocimologie = $this->tableauBordService->getIndicateurDocimologieResponsable($targetGroupeIds, $targetProjetId);
        } else {
            // Projet standard hors session : indicateurs Live Coding / EFM désactivés
            $kpiGating = ['is_applicable' => false, 'message' => "Non applicable (Projet sans épreuve Live Coding N2)"];
            $kpiFileAttente = ['is_applicable' => false, 'message' => "Non applicable (Pas d'épreuve Live Coding)"];
            $kpiIntegriteIA = ['is_applicable' => false, 'message' => "Non applicable (Pas d'épreuve Live Coding N2)"];
            $kpiCouverture = ['is_applicable' => false, 'message' => "Non applicable (Pas d'Unités d'Apprentissage mobilisées)"];
            $kpiDocimologie = ['is_applicable' => false, 'message' => "Non applicable (Pas de conversion EFM officielle)"];
        }
        $kpis = [
            'kpiAmorcage' => $kpiAmorcage,
            'kpiGating' => $kpiGating,
            'kpiRevision' => $kpiRevision,
            'kpiChargeLogistique' => $kpiChargeLogistique,
            'kpiFileAttente' => $kpiFileAttente,
            'kpiIntervention' => $kpiIntervention,
            'kpiIntegriteIA' => $kpiIntegriteIA,
            'kpiCouverture' => $kpiCouverture,
            'kpiDocimologie' => $kpiDocimologie,
        ];

        // Résolution dynamique des Cards et Panels pour admin/root
        $resolved = $this->tableauBordService->resolveDashboardComponents($user, $kpis, $isProjetSessionFormation, 'admin');
        $dashboardCards = $resolved['cards'];
        $dashboardPanels = $resolved['panels'];

        return view('PkgTableauBord::responsable.index', [
            'user' => $user,
            'isVisionGlobale' => $isVisionGlobale,
            'formateurs' => $formateurs,
            'selectedFormateurId' => $selectedFormateurId,
            'filieres' => $filieres,
            'selectedFiliereId' => $selectedFiliereId,
            'groupes' => $groupes,
            'selectedGroupeId' => $selectedGroupeId,
            'projets' => $projets,
            'selectedProjetId' => $selectedProjetId,
            'projetActif' => $projetActif,
            'filterType' => $filterType,
            'isProjetSessionFormation' => $isProjetSessionFormation,
            'kpiAmorcage' => $kpiAmorcage,
            'kpiGating' => $kpiGating,
            'kpiRevision' => $kpiRevision,
            'kpiChargeLogistique' => $kpiChargeLogistique,
            'kpiFileAttente' => $kpiFileAttente,
            'kpiIntervention' => $kpiIntervention,
            'kpiIntegriteIA' => $kpiIntegriteIA,
            'kpiCouverture' => $kpiCouverture,
            'kpiDocimologie' => $kpiDocimologie,
            'dashboardCards' => $dashboardCards,
            'dashboardPanels' => $dashboardPanels,
        ]);
    }
}

