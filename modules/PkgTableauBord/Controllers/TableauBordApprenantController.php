<?php

namespace Modules\PkgTableauBord\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgCreationProjet\Models\Projet;
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
        if (!$user) {
            abort(403, "Accès refusé. Veuillez vous connecter.");
        }

        // Vérification des rôles autorisés : Apprenant, Formateur, Responsable/Admin/Root
        $isApprenant = $user->hasRole(Role::APPRENANT_ROLE);
        $isFormateur = $user->hasRole(Role::FORMATEUR_ROLE);
        $isAdmin = $user->hasAnyRole(['admin', 'root']) || $user->hasRole(Role::ADMIN_ROLE);

        if (!$isApprenant && !$isFormateur && !$isAdmin) {
            abort(403, "Accès refusé. Vous n'avez pas l'autorisation d'accéder à ce tableau de bord.");
        }

        $apprenant = null;
        $groupes = collect();
        $apprenants = collect();
        $selectedGroupeId = $request->query('groupe_id');
        $selectedApprenantId = $request->query('apprenant_id');
        $canSelectApprenant = false;
        $isSuperviseur = $isFormateur || $isAdmin;

        if ($isApprenant) {
            // L'apprenant connecté consulte exclusivement son propre profil
            $apprenant = Apprenant::where('user_id', $user->id)->first();
            if (!$apprenant) {
                abort(403, "Accès refusé. Aucun profil Apprenant n'est associé à votre compte utilisateur.");
            }
            $canSelectApprenant = false;
            $selectedApprenantId = $apprenant->id;
        } elseif ($isFormateur) {
            // Le formateur consulte les apprenants de ses groupes assignés
            $formateur = Formateur::where('user_id', $user->id)->first();
            if (!$formateur) {
                abort(403, "Accès refusé. Aucun profil Formateur n'est associé à votre compte.");
            }

            $canSelectApprenant = true;
            $groupes = $formateur->groupes()->orderBy('nom')->get();

            // Filtrage par groupe si spécifié et valide
            if (!empty($selectedGroupeId) && $groupes->contains('id', (int)$selectedGroupeId)) {
                $selectedGroupe = $groupes->firstWhere('id', (int)$selectedGroupeId);
                $apprenants = $selectedGroupe ? $selectedGroupe->apprenants()->where('actif', true)->orderBy('nom')->get() : collect();
            } else {
                $selectedGroupeId = null;
                $apprenants = Apprenant::whereHas('groupes.formateurs', function ($q) use ($formateur) {
                    $q->where('formateurs.id', $formateur->id);
                })->where('actif', true)->orderBy('nom')->get();
            }

            // Sélection de l'apprenant
            if (!empty($selectedApprenantId)) {
                // Vérification stricte de sécurité : l'apprenant doit appartenir aux groupes du formateur
                $isAllowed = $apprenants->contains('id', (int)$selectedApprenantId) ||
                    $formateur->groupes()->whereHas('apprenants', function ($q) use ($selectedApprenantId) {
                        $q->where('apprenants.id', (int)$selectedApprenantId);
                    })->exists();

                if (!$isAllowed) {
                    abort(403, "Accès refusé. Cet apprenant ne fait pas partie de vos groupes.");
                }

                $apprenant = Apprenant::find($selectedApprenantId);
            } else {
                // Comportement par défaut validé : charger le premier apprenant de la liste
                $apprenant = $apprenants->first();
                $selectedApprenantId = $apprenant?->id;
            }
        } else {
            // Responsable / Admin / Root : accès global à tous les groupes et apprenants
            $canSelectApprenant = true;
            $groupes = Groupe::orderBy('nom')->get();

            if (!empty($selectedGroupeId) && $groupes->contains('id', (int)$selectedGroupeId)) {
                $selectedGroupe = $groupes->firstWhere('id', (int)$selectedGroupeId);
                $apprenants = $selectedGroupe ? $selectedGroupe->apprenants()->where('actif', true)->orderBy('nom')->get() : collect();
            } else {
                $selectedGroupeId = null;
                $apprenants = Apprenant::where('actif', true)->orderBy('nom')->get();
            }

            if (!empty($selectedApprenantId)) {
                $apprenant = Apprenant::find($selectedApprenantId);
            } else {
                $apprenant = $apprenants->first();
                $selectedApprenantId = $apprenant?->id;
            }
        }

        // Résolution du Projet et des Réalisations pour l'apprenant déterminé
        $projets = collect();
        $projetActif = null;
        $realisationProjet = null;

        if ($apprenant) {
            $filterType = $request->get('type_projet', $request->get('filter_type', 'sessions'));
            $projetsQuery = Projet::query()
                ->whereHas('affectationProjets.realisationProjets', function ($q) use ($apprenant) {
                    $q->where('apprenant_id', $apprenant->id);
                })
                ->with(['formateur', 'filiere'])
                ->withExists(['taches as has_live_coding' => function ($sub) {
                    $sub->whereHas('phaseEvaluation', fn($pe) => $pe->where('code', 'N2'));
                }]);

            if ($filterType === 'sessions') {
                $projetsQuery->sessionsFormation();
            }

            $projets = $projetsQuery->orderBy('titre')->get();

            $requestedProjetId = $request->get('projet_id') ?? $request->get('session_formation_id');
            if (!empty($requestedProjetId) && $projets->contains('id', (int)$requestedProjetId)) {
                $projetActif = $projets->firstWhere('id', (int)$requestedProjetId);
            } elseif (!empty($requestedProjetId)) {
                $projetActif = Projet::whereHas('affectationProjets.realisationProjets', fn($q) => $q->where('apprenant_id', $apprenant->id))
                    ->with(['formateur', 'filiere'])
                    ->find($requestedProjetId);
            } else {
                $projetActif = $projets->first();
            }

            if ($projetActif) {
                $realisationProjet = RealisationProjet::with([
                    'affectationProjet.projet.formateur',
                    'affectationProjet.groupe',
                    'apprenant'
                ])
                ->where('apprenant_id', $apprenant->id)
                ->whereHas('affectationProjet', function ($q) use ($projetActif) {
                    $q->where('projet_id', $projetActif->id);
                })
                ->latest('date_debut')
                ->first();
            }

            if (!$realisationProjet) {
                $realisationProjet = RealisationProjet::with([
                    'affectationProjet.projet.formateur',
                    'affectationProjet.groupe',
                    'apprenant'
                ])
                ->where('apprenant_id', $apprenant->id)
                ->latest('date_debut')
                ->first();
            }
        }

        // Nature didactique du projet actif
        $isProjetSessionFormation = $projetActif ? $projetActif->isSessionFormation() : true;

        // Calcul des indicateurs (KPIs)
        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageApprenant($realisationProjet);
        $kpiRevision = $this->tableauBordService->getIndicateurRevisionApprenant($realisationProjet);
        $kpiIntervention = $this->tableauBordService->getIndicateurInterventionApprenant($realisationProjet, $apprenant?->user ?? $user);

        if ($isProjetSessionFormation) {
            $kpiGating = $this->tableauBordService->getIndicateurGatingApprenant($realisationProjet);
            $kpiFileAttente = $this->tableauBordService->getIndicateurFileAttenteApprenant($realisationProjet, $apprenant?->user ?? $user);
            $kpiIntegriteIA = $this->tableauBordService->getIndicateurIntegriteApprenant($realisationProjet);
            $kpiCouverture = $this->tableauBordService->getIndicateurCouvertureApprenant($realisationProjet, $apprenant);
            $kpiDocimologie = $this->tableauBordService->getIndicateurDocimologieApprenant($realisationProjet, $apprenant);
        } else {
            $kpiGating = ['is_applicable' => false, 'message' => "Non applicable (Projet sans épreuve Live Coding N2)"];
            $kpiFileAttente = ['is_applicable' => false, 'message' => "Non applicable (Pas d'épreuve Live Coding)"];
            $kpiIntegriteIA = ['is_applicable' => false, 'message' => "Non applicable (Pas d'épreuve Live Coding N2)"];
            $kpiCouverture = ['is_applicable' => false, 'message' => "Non applicable (Pas d'Unités d'Apprentissage mobilisées)"];
            $kpiDocimologie = ['is_applicable' => false, 'message' => "Non applicable (Pas de conversion EFM officielle)"];
        }

        return view('PkgTableauBord::apprenant.index', [
            'user' => $user,
            'apprenant' => $apprenant,
            'isProjetSessionFormation' => $isProjetSessionFormation,
            'isSuperviseur' => $isSuperviseur,
            'canSelectApprenant' => $canSelectApprenant,
            'groupes' => $groupes,
            'selectedGroupeId' => $selectedGroupeId,
            'apprenants' => $apprenants,
            'selectedApprenantId' => $selectedApprenantId,
            'realisationProjet' => $realisationProjet,
            'kpiAmorcage' => $kpiAmorcage,
            'kpiGating' => $kpiGating,
            'kpiRevision' => $kpiRevision,
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
