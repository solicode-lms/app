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

class IndicateurDetailController extends AdminController
{
    protected TableauBordService $tableauBordService;

    public function __construct(TableauBordService $tableauBordService)
    {
        parent::__construct();
        $this->tableauBordService = $tableauBordService;
    }

    /**
     * Résout le contexte de filtrage commun (formateurs, filières, groupes, projets).
     *
     * @param Request $request
     * @return array
     */
    protected function resolveFilterContext(Request $request): array
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, "Accès refusé. Veuillez vous connecter.");
        }

        $isFormateur = $user->hasRole(Role::FORMATEUR_ROLE);
        $isAdminOrRoot = $user->hasAnyRole([Role::ADMIN_ROLE, 'root', 'responsable']);

        if (!$isFormateur && !$isAdminOrRoot) {
            abort(403, "Accès refusé. Cette interface d'analyse détaillée est réservée aux formateurs et administrateurs.");
        }

        $selectedFormateurId = $request->filled('formateur_id') ? (int) $request->get('formateur_id') : null;
        $selectedFiliereId = $request->filled('filiere_id') ? (int) $request->get('filiere_id') : null;
        $selectedGroupeId = $request->filled('groupe_id') ? (int) $request->get('groupe_id') : null;
        $selectedProjetId = $request->filled('projet_id') 
            ? (int) $request->get('projet_id') 
            : ($request->filled('session_formation_id') ? (int) $request->get('session_formation_id') : null);
        $filterType = $request->get('type_projet', $request->get('filter_type', 'sessions'));

        // Si formateur strict (sans rôle admin/root/responsable), verrouiller sur son profil
        if ($isFormateur && !$isAdminOrRoot) {
            $formateur = Formateur::where('user_id', $user->id)->first();
            if ($formateur) {
                $selectedFormateurId = $formateur->id;
            }
        }

        // Collections de référence
        $formateurs = Formateur::orderBy('nom')->get();
        $filieres = Filiere::orderBy('nom')->get();

        // Groupes selon filière et/ou formateur
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

        // Projets
        $projetsQuery = Projet::query()
            ->with(['formateur', 'filiere'])
            ->whereHas('affectationProjets')
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
            $projetActif = $projets->firstWhere('id', $selectedProjetId) ?? Projet::with(['formateur', 'filiere'])->find($selectedProjetId);
        }

        $isVisionGlobale = empty($selectedFormateurId) && empty($selectedFiliereId) && empty($selectedGroupeId) && empty($selectedProjetId);

        // Détermination des IDs de groupes cibles
        if ($selectedGroupeId) {
            $targetGroupeIds = [$selectedGroupeId];
        } elseif ($selectedFiliereId || $selectedFormateurId) {
            $targetGroupeIds = $groupes->pluck('id')->toArray();
            if (empty($targetGroupeIds)) {
                $targetGroupeIds = [-1];
            }
        } else {
            $targetGroupeIds = [];
        }

        if ($request->get('from') === 'formateur' || ($isFormateur && !$isAdminOrRoot)) {
            $dashboardRoute = 'admin.PkgTableauBord.formateur';
        } elseif ($request->get('from') === 'responsable' || $isAdminOrRoot) {
            $dashboardRoute = 'admin.PkgTableauBord.responsable';
        } else {
            $dashboardRoute = $isFormateur ? 'admin.PkgTableauBord.formateur' : 'admin.PkgTableauBord.responsable';
        }

        return [
            'user' => $user,
            'dashboardRoute' => $dashboardRoute,
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
            'targetGroupeIds' => $targetGroupeIds,
            'targetProjetId' => $projetActif?->id,
        ];
    }

    /**
     * Page de détail pour l'Indicateur 01 : Temps d'Amorçage & Mise au Travail.
     *
     * @DynamicPermissionIgnore
     */
    public function amorcage(Request $request)
    {
        $ctx = $this->resolveFilterContext($request);

        $filters = [
            'groupe_ids' => $ctx['targetGroupeIds'],
            'projet_id' => $ctx['targetProjetId'],
            'filiere_id' => $ctx['selectedFiliereId'],
            'formateur_id' => $ctx['selectedFormateurId'],
            'filter_type' => $ctx['filterType'],
        ];

        $detail = $this->tableauBordService->getIndicateurAmorcageDetail($filters);

        return view('PkgTableauBord::indicateurs.amorcage', [
            'user' => $ctx['user'],
            'dashboardRoute' => $ctx['dashboardRoute'],
            'isVisionGlobale' => $ctx['isVisionGlobale'],
            'formateurs' => $ctx['formateurs'],
            'selectedFormateurId' => $ctx['selectedFormateurId'],
            'filieres' => $ctx['filieres'],
            'selectedFiliereId' => $ctx['selectedFiliereId'],
            'groupes' => $ctx['groupes'],
            'selectedGroupeId' => $ctx['selectedGroupeId'],
            'projets' => $ctx['projets'],
            'selectedProjetId' => $ctx['selectedProjetId'],
            'projetActif' => $ctx['projetActif'],
            'filterType' => $ctx['filterType'],
            'detail' => $detail,
        ]);
    }

    /**
     * Page de détail pour l'Indicateur 02 : Validation du Prototype & Gating (R_gating).
     *
     * @DynamicPermissionIgnore
     */
    public function gating(Request $request)
    {
        $ctx = $this->resolveFilterContext($request);

        $filters = [
            'groupe_ids' => $ctx['targetGroupeIds'],
            'projet_id' => $ctx['targetProjetId'],
            'filiere_id' => $ctx['selectedFiliereId'],
            'formateur_id' => $ctx['selectedFormateurId'],
            'filter_type' => $ctx['filterType'],
        ];

        $detail = $this->tableauBordService->getIndicateurGatingDetail($filters);

        return view('PkgTableauBord::indicateurs.gating', [
            'user' => $ctx['user'],
            'dashboardRoute' => $ctx['dashboardRoute'],
            'isVisionGlobale' => $ctx['isVisionGlobale'],
            'formateurs' => $ctx['formateurs'],
            'selectedFormateurId' => $ctx['selectedFormateurId'],
            'filieres' => $ctx['filieres'],
            'selectedFiliereId' => $ctx['selectedFiliereId'],
            'groupes' => $ctx['groupes'],
            'selectedGroupeId' => $ctx['selectedGroupeId'],
            'projets' => $ctx['projets'],
            'selectedProjetId' => $ctx['selectedProjetId'],
            'projetActif' => $ctx['projetActif'],
            'filterType' => $ctx['filterType'],
            'detail' => $detail,
        ]);
    }

    /**
     * Page de détail pour l'Indicateur 03 : Fréquence de Révision & Stabilité du Premier Jet (F_révision).
     *
     * @DynamicPermissionIgnore
     */
    public function revisions(Request $request)
    {
        $ctx = $this->resolveFilterContext($request);

        $filters = [
            'groupe_ids' => $ctx['targetGroupeIds'],
            'projet_id' => $ctx['targetProjetId'],
            'filiere_id' => $ctx['selectedFiliereId'],
            'formateur_id' => $ctx['selectedFormateurId'],
            'filter_type' => $ctx['filterType'],
        ];

        $detail = $this->tableauBordService->getIndicateurRevisionDetail($filters);

        return view('PkgTableauBord::indicateurs.revisions', [
            'user' => $ctx['user'],
            'dashboardRoute' => $ctx['dashboardRoute'],
            'isVisionGlobale' => $ctx['isVisionGlobale'],
            'formateurs' => $ctx['formateurs'],
            'selectedFormateurId' => $ctx['selectedFormateurId'],
            'filieres' => $ctx['filieres'],
            'selectedFiliereId' => $ctx['selectedFiliereId'],
            'groupes' => $ctx['groupes'],
            'selectedGroupeId' => $ctx['selectedGroupeId'],
            'projets' => $ctx['projets'],
            'selectedProjetId' => $ctx['selectedProjetId'],
            'projetActif' => $ctx['projetActif'],
            'filterType' => $ctx['filterType'],
            'detail' => $detail,
        ]);
    }

    /**
     * Page de détail pour l'Indicateur 04 : Charge Logistique Hebdomadaire du Formateur (C_logistique).
     *
     * @DynamicPermissionIgnore
     */
    public function chargeLogistique(Request $request)
    {
        $ctx = $this->resolveFilterContext($request);

        $filters = [
            'groupe_ids' => $ctx['targetGroupeIds'],
            'projet_id' => $ctx['targetProjetId'],
            'filiere_id' => $ctx['selectedFiliereId'],
            'formateur_id' => $ctx['selectedFormateurId'],
            'filter_type' => $ctx['filterType'],
            'semaine' => $request->get('semaine'),
        ];

        $detail = $this->tableauBordService->getIndicateurChargeLogistiqueDetail($filters);

        return view('PkgTableauBord::indicateurs.charge-logistique', [
            'user' => $ctx['user'],
            'dashboardRoute' => $ctx['dashboardRoute'],
            'isVisionGlobale' => $ctx['isVisionGlobale'],
            'formateurs' => $ctx['formateurs'],
            'selectedFormateurId' => $ctx['selectedFormateurId'],
            'filieres' => $ctx['filieres'],
            'selectedFiliereId' => $ctx['selectedFiliereId'],
            'groupes' => $ctx['groupes'],
            'selectedGroupeId' => $ctx['selectedGroupeId'],
            'projets' => $ctx['projets'],
            'selectedProjetId' => $ctx['selectedProjetId'],
            'projetActif' => $ctx['projetActif'],
            'filterType' => $ctx['filterType'],
            'selectedSemaine' => $detail['semaine'],
            'detail' => $detail,
        ]);
    }
}
