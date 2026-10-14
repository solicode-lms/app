<?php

namespace Modules\PkgTableauBord\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\Models\Role;
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
        if (!$user || !$user->hasRole(Role::FORMATEUR_ROLE)) {
            abort(403, "Accès refusé. Cette interface est strictement réservée aux formateurs.");
        }

        $formateur = Formateur::where('user_id', $user->id)->first();
        if (!$formateur) {
            abort(403, "Accès refusé. Aucun profil Formateur n'est associé à votre compte utilisateur.");
        }

        // Résolution du Projet actif (session de formation du formateur connecté)
        $requestedProjetId = $request->get('projet_id') ?? $request->get('session_formation_id');
        $projets = $this->tableauBordService->getSessionsProjetDisponibles($user);
        $projetActif = $this->tableauBordService->resolveActiveProjet($requestedProjetId, $user);

        // Récupérer l'affectation active (strictement cloisonnée au formateur connecté)
        $affectationProjetId = $request->get('affectation_projet_id');
        $affectationProjet = null;

        if ($affectationProjetId) {
            $affectationProjet = AffectationProjet::whereHas('projet', function ($q) use ($formateur) {
                $q->where('formateur_id', $formateur->id);
            })->with(['projet.formateur', 'groupe'])->find($affectationProjetId);
        }

        if (!$affectationProjet && $projetActif) {
            $affectationProjet = AffectationProjet::where('projet_id', $projetActif->id)
                ->whereHas('projet', function ($q) use ($formateur) {
                    $q->where('formateur_id', $formateur->id);
                })
                ->with(['projet.formateur', 'groupe'])
                ->latest('date_debut')
                ->first();
        }

        if (!$affectationProjet) {
            $affectationProjet = AffectationProjet::whereHas('projet', function ($q) use ($formateur) {
                $q->where('formateur_id', $formateur->id);
            })
            ->with(['projet.formateur', 'groupe'])
            ->latest('date_debut')
            ->first();
        }

        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageFormateur($affectationProjet);
        $kpiGating = $this->tableauBordService->getIndicateurGatingFormateur($affectationProjet);
        $kpiRevision = $this->tableauBordService->getIndicateurRevisionFormateur($affectationProjet);
        $kpiChargeLogistique = $this->tableauBordService->getIndicateurChargeLogistiqueFormateur($formateur);
        $kpiFileAttente = $this->tableauBordService->getIndicateurFileAttenteFormateur($affectationProjet);
        $kpiIntervention = $this->tableauBordService->getIndicateurInterventionFormateur($affectationProjet, $formateur);
        $kpiIntegriteIA = $this->tableauBordService->getIndicateurIntegriteFormateur($affectationProjet);
        $kpiCouverture = $this->tableauBordService->getIndicateurCouvertureFormateur($affectationProjet, $affectationProjet?->groupe);
        $kpiDocimologie = $this->tableauBordService->getIndicateurDocimologieFormateur($affectationProjet, $affectationProjet?->groupe);

        return view('PkgTableauBord::formateur.index', [
            'user' => $user,
            'formateur' => $formateur,
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
        ]);
    }
}
