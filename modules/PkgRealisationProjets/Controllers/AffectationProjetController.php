<?php


namespace Modules\PkgRealisationProjets\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\AffectationProjetExport;
use Modules\PkgRealisationProjets\Controllers\Base\BaseAffectationProjetController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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


    public function exportPV(Request $request, string $id, $format = 'xlsx')
    {
        $affectationProjets_data = $this->affectationProjetService->all();
        
        // Sélection du format de téléchargement
        if ($format === 'csv') {
            return Excel::download(
                new AffectationProjetExport($affectationProjets_data, 'csv'),
                'pv_affectation_projet_' . $id . '.csv',
                \Maatwebsite\Excel\Excel::CSV,
                ['Content-Type' => 'text/csv']
            );
        } elseif ($format === 'xlsx') {
            return Excel::download(
                new AffectationProjetExport($affectationProjets_data, 'xlsx'),
                'pv_affectation_projet_' . $id . '.xlsx',
                \Maatwebsite\Excel\Excel::XLSX
            );
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
    }
}
