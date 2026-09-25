<?php
namespace Modules\PkgQcm\Controllers;

use Modules\PkgQcm\Controllers\Base\BaseAffectationQcmProjetController;
use Modules\PkgQcm\Models\AffectationQcmProjet;
use Illuminate\Http\Request;
use Modules\PkgQcm\Services\QuestionService;
use Exception;

class AffectationQcmProjetController extends BaseAffectationQcmProjetController
{
    /**
     * Affiche la vue contenant le prompt IA généré pour l'Unité d'Apprentissage.
     * 
     * @DynamicPermissionIgnore
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function prompt(string $id, \Modules\PkgQcm\Services\AffectationQcmProjetService $affectationService)
    {
        $this->authorizeAction('update');
        // 1. Récupérer l'affectation avec les relations en cascade
        $affectation = AffectationQcmProjet::with([
            'qcm', 
            'affectationProjet.projet.mobilisationUas.uniteApprentissage.chapitres'
        ])->findOrFail($id);
        
        // 2. Extraire toutes les Unités d'Apprentissage (UAs) mobilisées
        $uas = $affectation->affectationProjet->projet->mobilisationUas->pluck('uniteApprentissage')->filter();

        // 3. Récupérer le contenu des tutoriels de chaque chapitre
        $tutosContent = [];
        foreach ($uas as $ua) {
            foreach ($ua->chapitres as $chapitre) {
                if ($chapitre->lien) {
                    $tutosContent[$chapitre->id] = $affectationService->getTutoContent($chapitre->lien);
                }
            }
        }

        // 4. Renvoyer la vue Blade avec les données
        return view('PkgQcm::affectationQcmProjet.prompt', compact('affectation', 'uas', 'tutosContent'));
    }
    /**
     * Traite le JSON soumis et crée les questions en base, liées au QCM de l'affectation
     * @DynamicPermissionIgnore
     */
    public function importIaProcess(Request $request, QuestionService $questionService, string $id)
    {
        $this->authorizeAction('update');

        $jsonPayload = $request->input('json_payload');

        if (!$jsonPayload) {
            return response()->json([
                'success' => false,
                'error' => 'Le code JSON est vide.'
            ], 400);
        }

        try {
            $affectation = AffectationQcmProjet::findOrFail($id);
            $count = $questionService->importFromJson($jsonPayload, $affectation->qcm_id);
            
            // On renvoie les questions décodeés pour l'affichage côté frontend
            $questionsData = json_decode($jsonPayload, true) ?? [];
            
            return response()->json([
                'success' => true,
                'message' => 'Les questions ont été importées avec succès.',
                'count' => $count,
                'questions' => $questionsData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur d\'importation : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Retourne le nombre de questions par Unité d'Apprentissage pour le QCM de l'affectation
     * @DynamicPermissionIgnore
     */
    public function getQuestionsCount(string $id)
    {
        $this->authorizeAction('update');
        $affectation = AffectationQcmProjet::findOrFail($id);
        
        $counts = \Modules\PkgQcm\Models\Question::where('qcm_id', $affectation->qcm_id)
            ->groupBy('unite_apprentissage_id')
            ->selectRaw('unite_apprentissage_id, count(*) as count')
            ->pluck('count', 'unite_apprentissage_id');
            
        return response()->json($counts);
    }
}
