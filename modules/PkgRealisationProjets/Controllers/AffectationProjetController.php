<?php


namespace Modules\PkgRealisationProjets\Controllers;

use Illuminate\Http\Request;
use Modules\PkgRealisationProjets\Controllers\Base\BaseAffectationProjetController;

class AffectationProjetController extends BaseAffectationProjetController
{
   
    /**
     * @DynamicPermissionIgnore
     */
    public function getDataHasEvaluateurs(Request $request)
    {

        $this->authorizeAction('getData');


        $filter = $request->query('filter');
        $value = $request->query('value');
    
        if (!$filter || !$value) {
            return response()->json(['errors' => 'getData : Les paramètres "filter" et "value" sont requis'], 400);
        }
    
        // Récupération des tâches filtrées
        $taches = $this->service->getDataHasEvaluateurs($filter, $value);
    
        // Retourner tous les champs avec un champ `toString`
        return response()->json($taches->map(fn($tache) => array_merge(
            $tache->toArray(), // Convertir l'objet en tableau avec tous les champs
            ['toString' => $tache->__toString()] // Ajouter le champ `toString`
        )));
    }
}
