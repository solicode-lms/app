<?php


namespace Modules\PkgRealisationProjets\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\AffectationProjetExport;
use Modules\PkgRealisationProjets\App\Exports\RealisationProjetsPV;
use Modules\PkgRealisationProjets\App\Exports\RealisationProjetExport;
use Modules\PkgRealisationProjets\App\Requests\AffectationProjetRequest;
use Modules\PkgRealisationProjets\Controllers\Base\BaseAffectationProjetController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AffectationProjetController extends BaseAffectationProjetController
{


    public function store(AffectationProjetRequest $request) {
        
        // Augment le temps d'execution : à 2min, pardéfaut : 30 s
        // il faut de temps pour la création des RealisationChapitre
        ini_set('max_execution_time', 120); // en secondes
        parent::store($request);
         
    }
   
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
        $affectationProjet = $this->service->find($id);

        $realisationProjets_data = $affectationProjet->realisationProjets;


        // Nettoyer le titre pour le nom de fichier
        $titreProjet = $affectationProjet->projet->titre;
        $titreProjetClean = Str::slug($titreProjet, '_'); // transforme en "Nom_du_projet"

        $fileName = 'Realisation_Projet_' . $titreProjetClean . '_PV';

       
        // Sélection du format de téléchargement
        if ($format === 'csv') {
            return Excel::download(
                new RealisationProjetsPV ($realisationProjets_data, 'csv'),
                $fileName . '.csv',
                \Maatwebsite\Excel\Excel::CSV,
                ['Content-Type' => 'text/csv']
            );
        } elseif ($format === 'xlsx') {
            return Excel::download(
                new RealisationProjetsPV($realisationProjets_data, 'xlsx'),
                $fileName . '.xlsx',
                \Maatwebsite\Excel\Excel::XLSX
            );
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
    }
}
