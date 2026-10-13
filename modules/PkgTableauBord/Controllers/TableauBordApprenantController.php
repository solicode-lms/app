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

        // Résolution du Projet actif (session de projet du formateur)
        $requestedProjetId = $request->get('projet_id') ?? $request->get('session_formation_id');
        $projets = $this->tableauBordService->getSessionsProjetDisponibles($user);
        $projetActif = $this->tableauBordService->resolveActiveProjet($requestedProjetId, $user);

        // Récupérer le projet de l'apprenant pour le projet actif
        $realisationProjet = null;
        if ($apprenant) {
            $query = RealisationProjet::with([
                'affectationProjet.projet.formateur',
                'affectationProjet.groupe',
                'apprenant'
            ])->where('apprenant_id', $apprenant->id);

            if ($projetActif) {
                $query->whereHas('affectationProjet', function ($q) use ($projetActif) {
                    $q->where('projet_id', $projetActif->id);
                });
            }

            $realisationProjet = $query->latest('date_debut')->first();
        }

        // Fallback contextuel sur le projet sélectionné
        if (!$realisationProjet && $projetActif) {
            $realisationProjet = RealisationProjet::with([
                'affectationProjet.projet.formateur',
                'affectationProjet.groupe',
                'apprenant'
            ])
            ->whereHas('affectationProjet', function ($q) use ($projetActif) {
                $q->where('projet_id', $projetActif->id);
            })
            ->latest('date_debut')
            ->first();
        }

        // Fallback global de prévisualisation
        if (!$realisationProjet) {
            $realisationProjet = RealisationProjet::with([
                'affectationProjet.projet.formateur',
                'affectationProjet.groupe',
                'apprenant'
            ])->latest('date_debut')->first();
        }

        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageApprenant($realisationProjet);
        $kpiGating = $this->tableauBordService->getIndicateurGatingApprenant($realisationProjet);
        $kpiRevision = $this->tableauBordService->getIndicateurRevisionApprenant($realisationProjet);
        $kpiFileAttente = $this->tableauBordService->getIndicateurFileAttenteApprenant($realisationProjet, $user);

        return view('PkgTableauBord::apprenant.index', [
            'user' => $user,
            'apprenant' => $apprenant,
            'realisationProjet' => $realisationProjet,
            'kpiAmorcage' => $kpiAmorcage,
            'kpiGating' => $kpiGating,
            'kpiRevision' => $kpiRevision,
            'kpiFileAttente' => $kpiFileAttente,
            'projets' => $projets,
            'projetActif' => $projetActif,
            'sessions' => $projets,
            'sessionFormationActive' => $projetActif,
        ]);
    }
}
