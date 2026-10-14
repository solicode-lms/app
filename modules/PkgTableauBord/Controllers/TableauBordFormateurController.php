<?php

namespace Modules\PkgTableauBord\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgCreationProjet\Models\Projet;
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
        if (!$user) {
            abort(403, "Accès refusé. Veuillez vous connecter.");
        }

        // Seuls les rôles Formateur, Admin et Root ont accès
        $isFormateur = $user->hasRole(Role::FORMATEUR_ROLE);
        $isAdminOrRoot = $user->hasAnyRole(['admin', 'root']);

        if (!$isFormateur && !$isAdminOrRoot) {
            abort(403, "Accès refusé. Cette interface est réservée aux formateurs et aux administrateurs.");
        }

        $formateur = null;
        $formateurs = collect();
        $selectedFormateurId = $request->query('formateur_id');
        $canSelectFormateur = false;

        if ($isFormateur && !$isAdminOrRoot) {
            // Le formateur consulte exclusivement son propre tableau de bord
            $formateur = Formateur::where('user_id', $user->id)->first();
            if (!$formateur) {
                abort(403, "Accès refusé. Aucun profil Formateur n'est associé à votre compte utilisateur.");
            }
            $canSelectFormateur = false;
            $selectedFormateurId = $formateur->id;
        } else {
            // Admin ou Root : sélection possible de tous les formateurs
            $canSelectFormateur = true;
            $formateurs = Formateur::orderBy('nom')->get();

            if (!empty($selectedFormateurId)) {
                $formateur = Formateur::find($selectedFormateurId);
            }

            // Comportement par défaut : charger le premier formateur si non spécifié ou introuvable
            if (!$formateur) {
                $formateur = $formateurs->first();
                $selectedFormateurId = $formateur?->id;
            }
        }

        // Groupes enseignés par ce formateur
        $groupes = $formateur ? $formateur->groupes()->orderBy('nom')->get() : collect();
        $selectedGroupeId = $request->query('groupe_id');

        if (!empty($selectedGroupeId) && $groupes->contains('id', (int)$selectedGroupeId)) {
            $selectedGroupeId = (int)$selectedGroupeId;
        } else {
            $selectedGroupeId = null;
        }

        // Résolution des sessions projets et des affectations
        $projets = collect();
        $projetActif = null;
        $affectationProjet = null;

        if ($formateur) {
            $filterType = $request->get('filter_type', 'sessions');
            $projetsQuery = Projet::query()
                ->where('formateur_id', $formateur->id)
                ->with(['formateur', 'filiere'])
                ->withExists(['taches as has_live_coding' => function ($sub) {
                    $sub->whereHas('phaseEvaluation', fn($pe) => $pe->where('code', 'N2'));
                }]);

            if ($filterType === 'sessions') {
                $projetsQuery->sessionsFormation();
            }

            if ($selectedGroupeId) {
                $projetsQuery->whereHas('affectationProjets', function ($q) use ($selectedGroupeId) {
                    $q->where('groupe_id', $selectedGroupeId);
                });
            }

            $projets = $projetsQuery->orderBy('titre')->get();

            $requestedProjetId = $request->get('projet_id') ?? $request->get('session_formation_id');
            if (!empty($requestedProjetId) && $projets->contains('id', (int)$requestedProjetId)) {
                $projetActif = $projets->firstWhere('id', (int)$requestedProjetId);
            } elseif (!empty($requestedProjetId)) {
                $projetActif = Projet::where('formateur_id', $formateur->id)->with(['formateur', 'filiere'])->find($requestedProjetId);
            } else {
                $projetActif = $projets->first();
            }

            // Résolution de l'affectation active (filtrée par formateur et groupe le cas échéant)
            $affectationQuery = AffectationProjet::whereHas('projet', function ($q) use ($formateur) {
                $q->where('formateur_id', $formateur->id);
            })->with(['projet.formateur', 'groupe']);

            if ($selectedGroupeId) {
                $affectationQuery->where('groupe_id', $selectedGroupeId);
            }

            $affectationProjetId = $request->get('affectation_projet_id');
            if (!empty($affectationProjetId)) {
                $affectationProjet = (clone $affectationQuery)->find($affectationProjetId);
            }

            if (!$affectationProjet && $projetActif) {
                $affectationProjet = (clone $affectationQuery)
                    ->where('projet_id', $projetActif->id)
                    ->latest('date_debut')
                    ->first();
            }

            if (!$affectationProjet) {
                $affectationProjet = (clone $affectationQuery)
                    ->latest('date_debut')
                    ->first();
            }
        }

        // Nature didactique du projet actif
        $isProjetSessionFormation = $projetActif ? $projetActif->isSessionFormation() : true;

        // Calcul des indicateurs KPIs
        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageFormateur($affectationProjet);
        $kpiRevision = $this->tableauBordService->getIndicateurRevisionFormateur($affectationProjet);
        $kpiChargeLogistique = $formateur ? $this->tableauBordService->getIndicateurChargeLogistiqueFormateur($formateur) : [];
        $kpiIntervention = $formateur ? $this->tableauBordService->getIndicateurInterventionFormateur($affectationProjet, $formateur) : [];

        if ($isProjetSessionFormation) {
            $kpiGating = $this->tableauBordService->getIndicateurGatingFormateur($affectationProjet);
            $kpiFileAttente = $this->tableauBordService->getIndicateurFileAttenteFormateur($affectationProjet);
            $kpiIntegriteIA = $this->tableauBordService->getIndicateurIntegriteFormateur($affectationProjet);
            $kpiCouverture = $this->tableauBordService->getIndicateurCouvertureFormateur($affectationProjet, $affectationProjet?->groupe);
            $kpiDocimologie = $this->tableauBordService->getIndicateurDocimologieFormateur($affectationProjet, $affectationProjet?->groupe);
        } else {
            $kpiGating = ['is_applicable' => false, 'message' => "Non applicable (Projet sans épreuve Live Coding N2)"];
            $kpiFileAttente = ['is_applicable' => false, 'message' => "Non applicable (Pas d'épreuve Live Coding)"];
            $kpiIntegriteIA = ['is_applicable' => false, 'message' => "Non applicable (Pas d'épreuve Live Coding N2)"];
            $kpiCouverture = ['is_applicable' => false, 'message' => "Non applicable (Pas d'Unités d'Apprentissage mobilisées)"];
            $kpiDocimologie = ['is_applicable' => false, 'message' => "Non applicable (Pas de conversion EFM officielle)"];
        }

        return view('PkgTableauBord::formateur.index', [
            'user' => $user,
            'formateur' => $formateur,
            'isSuperviseur' => $isAdminOrRoot,
            'canSelectFormateur' => $canSelectFormateur,
            'formateurs' => $formateurs,
            'selectedFormateurId' => $selectedFormateurId,
            'groupes' => $groupes,
            'selectedGroupeId' => $selectedGroupeId,
            'canSelectGroupe' => $groupes->count() > 1,
            'affectationProjet' => $affectationProjet,
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
            'isProjetSessionFormation' => $isProjetSessionFormation,
        ]);
    }
}
