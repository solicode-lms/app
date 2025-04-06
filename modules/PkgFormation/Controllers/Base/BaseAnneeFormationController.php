<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgApprenants\Services\GroupeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\AnneeFormationRequest;
use Modules\PkgFormation\Models\AnneeFormation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgFormation\App\Exports\AnneeFormationExport;
use Modules\PkgFormation\App\Imports\AnneeFormationImport;
use Modules\Core\Services\ContextState;

class BaseAnneeFormationController extends AdminController
{
    protected $anneeFormationService;

    public function __construct(AnneeFormationService $anneeFormationService) {
        parent::__construct();
        $this->service  =  $anneeFormationService;
        $this->anneeFormationService = $anneeFormationService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('anneeFormation.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $anneeFormations_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'anneeFormations_search',
                $this->viewState->get("filter.anneeFormation.anneeFormations_search")
            )],
            $request->except(['anneeFormations_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->anneeFormationService->prepareDataForIndexView($anneeFormations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($anneeFormation_partialViewName, $anneeFormation_compact_value)->render();
        }

        return view('PkgFormation::anneeFormation.index', $anneeFormation_compact_value);
    }
    public function create() {


        $itemAnneeFormation = $this->anneeFormationService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._fields', compact('itemAnneeFormation'));
        }
        return view('PkgFormation::anneeFormation.create', compact('itemAnneeFormation'));
    }
    public function store(AnneeFormationRequest $request) {
        $validatedData = $request->validated();
        $anneeFormation = $this->anneeFormationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' => __('PkgFormation::anneeFormation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $anneeFormation->id]
            );
        }

        return redirect()->route('anneeFormations.edit',['anneeFormation' => $anneeFormation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' => __('PkgFormation::anneeFormation.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('anneeFormation.edit_' . $id);


        $itemAnneeFormation = $this->anneeFormationService->find($id);


        

        $this->viewState->set('scope.affectationProjet.annee_formation_id', $id);


        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.groupe.annee_formation_id', $id);


        $groupeService =  new GroupeService();
        $groupes_view_data = $groupeService->prepareDataForIndexView();
        extract($groupes_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._edit', array_merge(compact('itemAnneeFormation'),));
        }

        return view('PkgFormation::anneeFormation.edit', array_merge(compact('itemAnneeFormation'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('anneeFormation.edit_' . $id);


        $itemAnneeFormation = $this->anneeFormationService->find($id);




        $this->viewState->set('scope.affectationProjet.annee_formation_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.groupe.annee_formation_id', $id);
        

        $groupeService =  new GroupeService();
        $groupes_view_data = $groupeService->prepareDataForIndexView();
        extract($groupes_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._edit', array_merge(compact('itemAnneeFormation',),$affectationProjet_compact_value, $groupe_compact_value));
        }

        return view('PkgFormation::anneeFormation.edit', array_merge(compact('itemAnneeFormation',),$affectationProjet_compact_value, $groupe_compact_value));

    }
    public function update(AnneeFormationRequest $request, string $id) {

        $validatedData = $request->validated();
        $anneeFormation = $this->anneeFormationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $anneeFormation->id]
            );
        }

        return redirect()->route('anneeFormations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $anneeFormation = $this->anneeFormationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('anneeFormations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')
                ])
        );

    }

    public function export($format)
    {
        $anneeFormations_data = $this->anneeFormationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new AnneeFormationExport($anneeFormations_data,'csv'), 'anneeFormation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new AnneeFormationExport($anneeFormations_data,'xlsx'), 'anneeFormation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new AnneeFormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('anneeFormations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('anneeFormations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::anneeFormation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAnneeFormations()
    {
        $anneeFormations = $this->anneeFormationService->all();
        return response()->json($anneeFormations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $anneeFormation = $this->anneeFormationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedAnneeFormation = $this->anneeFormationService->dataCalcul($anneeFormation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedAnneeFormation
        ]);
    }
    

}