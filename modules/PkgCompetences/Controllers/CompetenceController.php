<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Illuminate\Http\Request;
use Modules\PkgCompetences\Controllers\Base\BaseCompetenceController;

class CompetenceController extends BaseCompetenceController
{
    
    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $competence = $this->competenceService->createInstance($data);
    
        if (!$competence) {
            return response()->json(['error' => 'Compétence introuvable.'], 404);
        }
    
        // Mise à jour des attributs via le service
        $updatedCompetence = $this->competenceService->dataCalcul($competence);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedCompetence
        ]);
    }
    
}
