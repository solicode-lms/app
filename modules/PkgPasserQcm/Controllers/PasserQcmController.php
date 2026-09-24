<?php

namespace Modules\PkgPasserQcm\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PkgQcm\Models\RealisationQcm;

class PasserQcmController extends Controller
{
    private function checkAuthorization($realisationQcm)
    {
        $apprenant = \Modules\PkgApprenants\Models\Apprenant::where('user_id', auth()->id())->first();
        if (!$apprenant || $apprenant->id !== $realisationQcm->apprenant_id) {
            abort(403, "Accès non autorisé à ce QCM.");
        }
    }

    private function checkSubmissionState($realisationQcm)
    {
        $etatSoumis = \Modules\PkgQcm\Models\EtatRealisationQcm::where('reference', 'SOUMIS')->first();
        if (($etatSoumis && $realisationQcm->etat_realisation_qcm_id == $etatSoumis->id) || $realisationQcm->date_soumission) {
            abort(403, "Ce QCM a déjà été soumis. Vous ne pouvez pas le repasser.");
        }
    }

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
        $this->checkSubmissionState($realisationQcm);

        // Si le QCM n'a pas encore démarré, on affiche l'écran de démarrage
        if (empty($realisationQcm->date_debut)) {
            $nbQuestions = $realisationQcm->qcm->questions->count();
            return view('PkgPasserQcm::start', compact('realisationQcm', 'nbQuestions'));
        }

        // Calcul du temps restant
        $dureeMax = ($realisationQcm->qcm->duree_minutes ?? 60) * 60;
        $tempsEcoule = now()->diffInSeconds($realisationQcm->date_debut);
        $timeRemaining = max(0, $dureeMax - $tempsEcoule);

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

    public function start(Request $request, $realisation_qcm_id)
    {
        $realisationQcm = RealisationQcm::findOrFail($realisation_qcm_id);
        
        $this->checkAuthorization($realisationQcm);
        $this->checkSubmissionState($realisationQcm);

        if (empty($realisationQcm->date_debut)) {
            $realisationQcm->date_debut = now();
            
            $etatEnCours = \Modules\PkgQcm\Models\EtatRealisationQcm::where('reference', 'EN_COURS')->first();
            if ($etatEnCours) {
                $realisationQcm->etat_realisation_qcm_id = $etatEnCours->id;
            }
            
            $realisationQcm->save();
        }

        return redirect()->route('passerQcm.index', $realisation_qcm_id);
    }

    public function saveIncremental(Request $request, $realisation_qcm_id)
    {
        $realisationQcm = RealisationQcm::findOrFail($realisation_qcm_id);
        
        $this->checkAuthorization($realisationQcm);
        $this->checkSubmissionState($realisationQcm);
        
        $reponses = $request->input('reponses', []);
        
        foreach ($reponses as $questionId => $propositionIds) {
            // Création ou mise à jour de la réponse pour cette question
            $reponseQcm = \Modules\PkgQcm\Models\ReponseQcm::updateOrCreate(
                [
                    'realisation_qcm_id' => $realisationQcm->id,
                    'question_id' => $questionId
                ],
                [
                    'date_reponse' => now()
                ]
            );
            
            // Attacher les propositions (un tableau est attendu par sync)
            $propositionIdsArray = is_array($propositionIds) ? $propositionIds : [$propositionIds];
            $reponseQcm->propositionReponses()->sync($propositionIdsArray);
        }
        
        return response()->json(['success' => true]);
    }
    
    public function submit(Request $request, $realisation_qcm_id)
    {
        $realisationQcm = RealisationQcm::findOrFail($realisation_qcm_id);
        
        // Sauvegarde de la dernière page
        $this->saveIncremental($request, $realisation_qcm_id);
        
        // Trouver l'état "Soumis"
        $etatSoumis = \Modules\PkgQcm\Models\EtatRealisationQcm::where('reference', 'SOUMIS')->first();
        
        if ($etatSoumis) {
            $realisationQcm->etat_realisation_qcm_id = $etatSoumis->id;
        }
        
        $realisationQcm->date_soumission = now();
        $realisationQcm->save();
        
        return response()->json([
            'success' => true,
            'message' => 'QCM soumis avec succès.'
        ]);
    }
}
