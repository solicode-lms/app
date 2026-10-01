<?php

namespace Modules\PkgPasserQcm\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Illuminate\Http\Request;
use Modules\PkgQcm\Models\RealisationQcm;

class PasserQcmController extends AdminController
{
    protected $realisationQcmService;

    public function __construct(\Modules\PkgQcm\Services\RealisationQcmService $realisationQcmService)
    {
        parent::__construct();
        $this->realisationQcmService = $realisationQcmService;
    }

    private function checkAuthorization($realisationQcm)
    {
        // Vérification de la permission globale accordée par les rôles
        abort_if(!auth()->user()->can('passer-qcm'), 403, "Vous n'avez pas la permission de passer un QCM.");

        // Vérification que cet apprenant spécifique a bien le droit de passer CE QCM
        $apprenant = \Modules\PkgApprenants\Models\Apprenant::where('user_id', auth()->id())->first();
        if (!$apprenant || $apprenant->id !== $realisationQcm->apprenant_id) {
            abort(403, "Accès non autorisé à ce QCM.");
        }

        // Vérification que le QCM est publié
        if (!$realisationQcm->qcm || !$realisationQcm->qcm->is_publie) {
            abort(403, "Ce QCM n'est pas encore public.");
        }

        // Vérification que la date d'affectation est arrivée
        if ($realisationQcm->affectationQcmProjet && $realisationQcm->affectationQcmProjet->date_affectation) {
            $dateAffectation = \Carbon\Carbon::parse($realisationQcm->affectationQcmProjet->date_affectation)->startOfDay();
            if ($dateAffectation->isFuture()) {
                abort(403, "La date de ce QCM n'est pas encore arrivée.");
            }
        }
    }

    /**
     * @DynamicPermissionIgnore
     */
    public function index($realisation_qcm_id)
    {
        // 1. Chargement de la réalisation et des relations requises pour l'affichage
        $realisationQcm = RealisationQcm::with([
            'apprenant',
            'qcm.questions.propositionReponses',
            'qcm.questions.uniteApprentissage',
            'reponseQcms.propositionReponses' // Pour récupérer les réponses existantes
        ])->findOrFail($realisation_qcm_id);

        $this->checkAuthorization($realisationQcm);
        $this->realisationQcmService->verifierEtatSoumission($realisationQcm);

        // Si le QCM n'a pas encore démarré, on affiche l'écran de démarrage
        if (empty($realisationQcm->date_debut)) {
            $nbQuestions = $realisationQcm->qcm->questions->count();
            return view('PkgPasserQcm::start', compact('realisationQcm', 'nbQuestions'));
        }

        // Calcul du temps restant (délégué au service)
        $timeRemaining = $this->realisationQcmService->calculerTempsRestant($realisationQcm);

        // 2. Groupement des questions par Unité d'Apprentissage (UA)
        $questionsByUa = $realisationQcm->qcm->questions->groupBy('unite_apprentissage_id');
        
        // 3. Transformation en un tableau structuré (idéal pour un passage en JSON vers Alpine)
        $dataUaGrouped = [];
        $etapeIndex = 1;
        
        // Préparation des réponses existantes
        $existingAnswers = [];
        foreach ($realisationQcm->reponseQcms as $reponseQcm) {
            $existingAnswers[$reponseQcm->question_id] = $reponseQcm->propositionReponses->pluck('id')->toArray();
        }
        
        foreach ($questionsByUa as $uaId => $questions) {
            // L'UA peut être null si les questions ne sont pas rattachées à une UA spécifique
            $ua = $questions->first()->uniteApprentissage;
            
            $dataUaGrouped[] = [
                'etape' => $etapeIndex,
                'ua_id' => $uaId,
                'ua_titre' => $ua ? $ua->nom : 'Questions Générales',
                'questions' => $questions->map(function ($q) use ($existingAnswers) {
                    return [
                        'id' => $q->id,
                        'enonce' => $q->enonce,
                        'type' => $q->type, // Ex: radio, checkbox
                        'selected_propositions' => $existingAnswers[$q->id] ?? [],
                        'propositions' => $q->propositionReponses->map(function ($p) {
                            return [
                                'id' => $p->id,
                                'libelle' => $p->libelle
                            ];
                        })->values()->toArray()
                    ];
                })->values()->toArray()
            ];
            
            $etapeIndex++;
        }

        // On passe les variables mises à jour à la vue
        return view('PkgPasserQcm::index', compact('realisationQcm', 'dataUaGrouped', 'timeRemaining'));
    }

    /**
     * @DynamicPermissionIgnore
     */
    public function start(Request $request, $realisation_qcm_id)
    {
        $realisationQcm = RealisationQcm::findOrFail($realisation_qcm_id);
        
        $this->checkAuthorization($realisationQcm);
        $this->realisationQcmService->verifierEtatSoumission($realisationQcm);

        $this->realisationQcmService->demarrerQcm($realisationQcm);

        return redirect()->route('passerQcm.index', $realisation_qcm_id);
    }

    /**
     * @DynamicPermissionIgnore
     */
    public function saveIncremental(Request $request, $realisation_qcm_id)
    {
        $realisationQcm = RealisationQcm::findOrFail($realisation_qcm_id);
        
        $this->checkAuthorization($realisationQcm);
        $this->realisationQcmService->verifierEtatSoumission($realisationQcm);
        
        $reponses = $request->input('reponses', []);
        
        $this->realisationQcmService->sauvegarderReponses($realisationQcm, $reponses);
        
        $response = ['success' => true];
        if ($this->realisationQcmService->getCrudJobToken()) {
            $response['traitement_token'] = $this->realisationQcmService->getCrudJobToken();
        }
        
        return response()->json($response);
    }
    
    /**
     * @DynamicPermissionIgnore
     */
    public function submit(Request $request, $realisation_qcm_id)
    {
        $realisationQcm = RealisationQcm::findOrFail($realisation_qcm_id);
        
        $this->checkAuthorization($realisationQcm);
        $this->realisationQcmService->verifierEtatSoumission($realisationQcm);
        
        // Sauvegarde de la dernière page
        $reponses = $request->input('reponses', []);
        $this->realisationQcmService->sauvegarderReponses($realisationQcm, $reponses);
        
        $this->realisationQcmService->soumettreQcm($realisationQcm);
        
        $response = [
            'success' => true,
            'message' => 'QCM soumis avec succès.'
        ];

        if ($this->realisationQcmService->getCrudJobToken()) {
            $response['traitement_token'] = $this->realisationQcmService->getCrudJobToken();
        }

        return response()->json($response);
    }
}
