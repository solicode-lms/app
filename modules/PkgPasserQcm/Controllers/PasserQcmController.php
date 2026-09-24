<?php

namespace Modules\PkgPasserQcm\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PkgQcm\Models\RealisationQcm;

class PasserQcmController extends Controller
{
    public function index($realisation_qcm_id)
    {
        // 1. Chargement de la réalisation et des relations requises pour l'affichage
        $realisationQcm = RealisationQcm::with([
            'apprenant',
            'qcm.questions.propositionReponses',
            'qcm.questions.uniteApprentissage'
        ])->findOrFail($realisation_qcm_id);

        // TODO: Ajouter vérification d'autorisation (ex: auth()->user()->id == $realisationQcm->apprenant_id)

        // 2. Groupement des questions par Unité d'Apprentissage (UA)
        $questionsByUa = $realisationQcm->qcm->questions->groupBy('unite_apprentissage_id');
        
        // 3. Transformation en un tableau structuré (idéal pour un passage en JSON vers Alpine)
        $dataUaGrouped = [];
        $etapeIndex = 1;
        
        foreach ($questionsByUa as $uaId => $questions) {
            // L'UA peut être null si les questions ne sont pas rattachées à une UA spécifique
            $ua = $questions->first()->uniteApprentissage;
            
            $dataUaGrouped[] = [
                'etape' => $etapeIndex,
                'ua_id' => $uaId,
                'ua_titre' => $ua ? $ua->titre : 'Questions Générales',
                'questions' => $questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'enonce' => $q->enonce,
                        'type' => $q->type, // Ex: radio, checkbox
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

        // Pour ce Sprint 3, on affiche juste la vue avec le dump des données
        return view('PkgPasserQcm::index', compact('realisationQcm', 'dataUaGrouped'));
    }

    public function saveIncremental(Request $request, $realisation_qcm_id)
    {
        $realisationQcm = RealisationQcm::findOrFail($realisation_qcm_id);
        
        // TODO: Vérification d'autorisation
        
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
