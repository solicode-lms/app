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

        // Résolution du Projet actif (session de formation du formateur)
        $requestedProjetId = $request->get('projet_id') ?? $request->get('session_formation_id');
        $projets = $this->tableauBordService->getSessionsProjetDisponibles($user);
        $projetActif = $this->tableauBordService->resolveActiveProjet($requestedProjetId, $user);

        // Récupérer l'affectation active (soit par filtre ID, soit par projet actif, soit la plus récente)
        $affectationProjetId = $request->get('affectation_projet_id');
        $affectationProjet = null;

        if ($affectationProjetId) {
            $affectationProjet = AffectationProjet::with(['projet.formateur', 'groupe'])->find($affectationProjetId);
        }

        if (!$affectationProjet) {
            $query = AffectationProjet::with(['projet.formateur', 'groupe']);
            if ($projetActif) {
                $query->where('projet_id', $projetActif->id);
            }
            if ($formateur) {
                $query->whereHas('projet', function ($q) use ($formateur) {
                    $q->where('formateur_id', $formateur->id);
                });
            }
            $affectationProjet = $query->latest('date_debut')->first();
        }

        // Fallback contextuel sur le projet sélectionné
        if (!$affectationProjet && $projetActif) {
            $affectationProjet = AffectationProjet::with(['projet.formateur', 'groupe'])
                ->where('projet_id', $projetActif->id)
                ->latest('date_debut')
                ->first();
        }

        // Fallback global de prévisualisation
        if (!$affectationProjet) {
            $affectationProjet = AffectationProjet::with(['projet.formateur', 'groupe'])->latest('date_debut')->first();
        }

        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageFormateur($affectationProjet);
        $kpiGating = $this->tableauBordService->getIndicateurGatingFormateur($affectationProjet);
        $kpiRevision = $this->tableauBordService->getIndicateurRevisionFormateur($affectationProjet);
        $kpiChargeLogistique = $this->tableauBordService->getIndicateurChargeLogistiqueFormateur($formateur);
        $kpiFileAttente = $this->tableauBordService->getIndicateurFileAttenteFormateur($affectationProjet);
        $kpiIntervention = $this->tableauBordService->getIndicateurInterventionFormateur($affectationProjet, $formateur);
        $kpiIntegriteIA = $this->tableauBordService->getIndicateurIntegriteFormateur($affectationProjet);
        $kpiCouverture = $this->tableauBordService->getIndicateurCouvertureFormateur($affectationProjet, $affectationProjet?->groupe);

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
            'projets' => $projets,
            'projetActif' => $projetActif,
            'sessions' => $projets,
            'sessionFormationActive' => $projetActif,
        ]);
    }
}
