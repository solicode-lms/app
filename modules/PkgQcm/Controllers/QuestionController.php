<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Controllers;


use Modules\PkgQcm\Controllers\Base\BaseQuestionController;

use Illuminate\Http\Request;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Exception;

class QuestionController extends BaseQuestionController
{
    /**
     * Affiche le formulaire de prompt et d'import JSON pour l'IA
     * @DynamicPermissionIgnore
     */
    public function importIaForm(Request $request)
    {
        // On s'assure que l'utilisateur a le droit d'importer
        $this->authorizeAction('importIaForm');

        // Récupérer toutes les UAs avec leurs chapitres
        $uas = UniteApprentissage::with('chapitres')->get();

        return view('PkgQcm::question.custom.import_ia', compact('uas'));
    }

    /**
     * Traite le JSON soumis et crée les questions en base
     * @DynamicPermissionIgnore
     */
    public function importIaProcess(Request $request)
    {
        $this->authorizeAction('importIaForm');

        $jsonPayload = $request->input('json_payload');

        if (!$jsonPayload) {
            return redirect()->back()->with('error', 'Le code JSON est vide.');
        }

        try {
            $count = $this->questionService->importFromJson($jsonPayload);
            return redirect()->route('questions.index')->with('success', "$count questions importées avec succès !");
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erreur d\'importation : ' . $e->getMessage())->withInput();
        }
    }
}
