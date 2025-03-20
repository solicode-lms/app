<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\WorkflowFormationService;
use Modules\PkgAutoformation\Services\EtatFormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\WorkflowFormationRequest;
use Modules\PkgAutoformation\Models\WorkflowFormation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\WorkflowFormationExport;
use Modules\PkgAutoformation\App\Imports\WorkflowFormationImport;
use Modules\Core\Services\ContextState;

class BaseWorkflowFormationController extends AdminController
{
    protected $workflowFormationService;

    public function __construct(WorkflowFormationService $workflowFormationService) {
        parent::__construct();
        $this->service  =  $workflowFormationService;
        $this->workflowFormationService = $workflowFormationService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('workflowFormation.index');



        // Extraire les paramètres de recherche, page, et filtres
        $workflowFormations_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('workflowFormations_search', $this->viewState->get("filter.workflowFormation.workflowFormations_search"))],
            $request->except(['workflowFormations_search', 'page', 'sort'])
        );

        // Paginer les workflowFormations
        $workflowFormations_data = $this->workflowFormationService->paginate($workflowFormations_params);

        // Récupérer les statistiques et les champs filtrables
        $workflowFormations_stats = $this->workflowFormationService->getworkflowFormationStats();
        $this->viewState->set('stats.workflowFormation.stats'  , $workflowFormations_stats);
        $workflowFormations_filters = $this->workflowFormationService->getFieldsFilterable();
        $workflowFormation_instance =  $this->workflowFormationService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgAutoformation::workflowFormation._table', compact('workflowFormations_data', 'workflowFormations_stats', 'workflowFormations_filters','workflowFormation_instance'))->render();
        }

        return view('PkgAutoformation::workflowFormation.index', compact('workflowFormations_data', 'workflowFormations_stats', 'workflowFormations_filters','workflowFormation_instance'));
    }
    public function create() {


        $itemWorkflowFormation = $this->workflowFormationService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgAutoformation::workflowFormation._fields', compact('itemWorkflowFormation'));
        }
        return view('PkgAutoformation::workflowFormation.create', compact('itemWorkflowFormation'));
    }
    public function store(WorkflowFormationRequest $request) {
        $validatedData = $request->validated();
        $workflowFormation = $this->workflowFormationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $workflowFormation,
                'modelName' => __('PkgAutoformation::workflowFormation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $workflowFormation->id]
            );
        }

        return redirect()->route('workflowFormations.edit',['workflowFormation' => $workflowFormation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $workflowFormation,
                'modelName' => __('PkgAutoformation::workflowFormation.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('workflowFormation.edit_' . $id);


        $itemWorkflowFormation = $this->workflowFormationService->find($id);




        $this->viewState->set('scope.etatFormation.workflow_formation_id', $id);


        $etatFormationService =  new EtatFormationService();
        $etatFormations_data =  $etatFormationService->paginate();
        $etatFormations_stats = $etatFormationService->getetatFormationStats();
        $etatFormations_filters = $etatFormationService->getFieldsFilterable();
        $etatFormation_instance =  $etatFormationService->createInstance();

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowFormation._edit', compact('itemWorkflowFormation', 'etatFormations_data', 'etatFormations_stats', 'etatFormations_filters', 'etatFormation_instance'));
        }

        return view('PkgAutoformation::workflowFormation.edit', compact('itemWorkflowFormation', 'etatFormations_data', 'etatFormations_stats', 'etatFormations_filters', 'etatFormation_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('workflowFormation.edit_' . $id);


        $itemWorkflowFormation = $this->workflowFormationService->find($id);




        $this->viewState->set('scope.etatFormation.workflow_formation_id', $id);
        

        $etatFormationService =  new EtatFormationService();
        $etatFormations_data =  $etatFormationService->paginate();
        $etatFormations_stats = $etatFormationService->getetatFormationStats();
        $this->viewState->set('stats.etatFormation.stats'  , $etatFormations_stats);
        $etatFormations_filters = $etatFormationService->getFieldsFilterable();
        $etatFormation_instance =  $etatFormationService->createInstance();

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowFormation._edit', compact('itemWorkflowFormation', 'etatFormations_data', 'etatFormations_stats', 'etatFormations_filters', 'etatFormation_instance'));
        }

        return view('PkgAutoformation::workflowFormation.edit', compact('itemWorkflowFormation', 'etatFormations_data', 'etatFormations_stats', 'etatFormations_filters', 'etatFormation_instance'));

    }
    public function update(WorkflowFormationRequest $request, string $id) {

        $validatedData = $request->validated();
        $workflowFormation = $this->workflowFormationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $workflowFormation,
                'modelName' =>  __('PkgAutoformation::workflowFormation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $workflowFormation->id]
            );
        }

        return redirect()->route('workflowFormations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $workflowFormation,
                'modelName' =>  __('PkgAutoformation::workflowFormation.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $workflowFormation = $this->workflowFormationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowFormation,
                'modelName' =>  __('PkgAutoformation::workflowFormation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('workflowFormations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowFormation,
                'modelName' =>  __('PkgAutoformation::workflowFormation.singular')
                ])
        );

    }

    public function export($format)
    {
        $workflowFormations_data = $this->workflowFormationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WorkflowFormationExport($workflowFormations_data,'csv'), 'workflowFormation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WorkflowFormationExport($workflowFormations_data,'xlsx'), 'workflowFormation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WorkflowFormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('workflowFormations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('workflowFormations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::workflowFormation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWorkflowFormations()
    {
        $workflowFormations = $this->workflowFormationService->all();
        return response()->json($workflowFormations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $workflowFormation = $this->workflowFormationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWorkflowFormation = $this->workflowFormationService->dataCalcul($workflowFormation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWorkflowFormation
        ]);
    }
    

}
