<?php

namespace Modules\PkgTableauBord\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\Models\Role;
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
        if (!$user || !$user->hasRole(Role::APPRENANT_ROLE)) {
            abort(403, "Accès refusé. Cette interface est strictement réservée aux apprenants.");
        }

        $apprenant = Apprenant::where('user_id', $user->id)->first();
        if (!$apprenant) {
            abort(403, "Accès refusé. Aucun profil Apprenant n'est associé à votre compte utilisateur.");
        }

        // Résolution du Projet actif (session de projet de l'apprenant connecté)
        $requestedProjetId = $request->get('projet_id') ?? $request->get('session_formation_id');
        $projets = $this->tableauBordService->getSessionsProjetDisponibles($user);
        $projetActif = $this->tableauBordService->resolveActiveProjet($requestedProjetId, $user);

        // Récupérer le projet de l'apprenant pour le projet actif (strictement cloisonné à l'apprenant connecté)
        $realisationProjet = null;

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

        $kpiAmorcage = $this->tableauBordService->getIndicateurAmorcageApprenant($realisationProjet);
        $kpiGating = $this->tableauBordService->getIndicateurGatingApprenant($realisationProjet);
        $kpiRevision = $this->tableauBordService->getIndicateurRevisionApprenant($realisationProjet);
        $kpiFileAttente = $this->tableauBordService->getIndicateurFileAttenteApprenant($realisationProjet, $user);
        $kpiIntervention = $this->tableauBordService->getIndicateurInterventionApprenant($realisationProjet, $user);
        $kpiIntegriteIA = $this->tableauBordService->getIndicateurIntegriteApprenant($realisationProjet);
        $kpiCouverture = $this->tableauBordService->getIndicateurCouvertureApprenant($realisationProjet, $apprenant);
        $kpiDocimologie = $this->tableauBordService->getIndicateurDocimologieApprenant($realisationProjet, $apprenant);

        return view('PkgTableauBord::apprenant.index', [
            'user' => $user,
            'apprenant' => $apprenant,
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
