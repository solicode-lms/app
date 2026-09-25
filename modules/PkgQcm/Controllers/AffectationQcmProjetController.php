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
    public function prompt(string $id)
    {
        $this->authorizeAction('update');
        // 1. Récupérer l'affectation avec les relations en cascade
        $affectation = AffectationQcmProjet::with([
            'qcm', 
            'affectationProjet.projet.mobilisationUas.uniteApprentissage.chapitres'
        ])->findOrFail($id);
        
        // 2. Extraire toutes les Unités d'Apprentissage (UAs) mobilisées
        $uas = $affectation->affectationProjet->projet->mobilisationUas->pluck('uniteApprentissage')->filter();

        // 3. Renvoyer la vue Blade avec les données
        return view('PkgQcm::affectationQcmProjet.prompt', compact('affectation', 'uas'));
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
            return redirect()->back()->with('error', 'Le code JSON est vide.');
        }

        try {
            $affectation = AffectationQcmProjet::findOrFail($id);
            // On passe l'ID du QCM associé à cette affectation
            $count = $questionService->importFromJson($jsonPayload, $affectation->qcm_id);
            return redirect()->route('qcms.edit', ['qcm' => $affectation->qcm_id])->with('success', "$count questions importées avec succès pour le QCM !");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erreur d\'importation : ' . $e->getMessage())->withInput();
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
