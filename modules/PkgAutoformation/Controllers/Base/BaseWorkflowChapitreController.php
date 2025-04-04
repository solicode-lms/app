<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\WorkflowChapitreService;
use Modules\Core\Services\SysColorService;
use Modules\PkgAutoformation\Services\EtatChapitreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\WorkflowChapitreRequest;
use Modules\PkgAutoformation\Models\WorkflowChapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\WorkflowChapitreExport;
use Modules\PkgAutoformation\App\Imports\WorkflowChapitreImport;
use Modules\Core\Services\ContextState;

class BaseWorkflowChapitreController extends AdminController
{
    protected $workflowChapitreService;
    protected $sysColorService;

    public function __construct(WorkflowChapitreService $workflowChapitreService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $workflowChapitreService;
        $this->workflowChapitreService = $workflowChapitreService;
        $this->sysColorService = $sysColorService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('workflowChapitre.index');



        // Extraire les paramètres de recherche, page, et filtres
        $workflowChapitres_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('workflowChapitres_search', $this->viewState->get("filter.workflowChapitre.workflowChapitres_search"))],
            $request->except(['workflowChapitres_search', 'page', 'sort'])
        );

        // Paginer les workflowChapitres
        $workflowChapitres_data = $this->workflowChapitreService->paginate($workflowChapitres_params);

        // Récupérer les statistiques et les champs filtrables
        $workflowChapitres_stats = $this->workflowChapitreService->getworkflowChapitreStats();
        $this->viewState->set('stats.workflowChapitre.stats'  , $workflowChapitres_stats);
        $workflowChapitres_filters = $this->workflowChapitreService->getFieldsFilterable();
        $workflowChapitre_instance =  $this->workflowChapitreService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgAutoformation::workflowChapitre._table', compact('workflowChapitres_data', 'workflowChapitres_stats', 'workflowChapitres_filters','workflowChapitre_instance'))->render();
        }

        return view('PkgAutoformation::workflowChapitre.index', compact('workflowChapitres_data', 'workflowChapitres_stats', 'workflowChapitres_filters','workflowChapitre_instance'));
    }
    public function create() {


        $itemWorkflowChapitre = $this->workflowChapitreService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowChapitre._fields', compact('itemWorkflowChapitre', 'sysColors'));
        }
        return view('PkgAutoformation::workflowChapitre.create', compact('itemWorkflowChapitre', 'sysColors'));
    }
    public function store(WorkflowChapitreRequest $request) {
        $validatedData = $request->validated();
        $workflowChapitre = $this->workflowChapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' => __('PkgAutoformation::workflowChapitre.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $workflowChapitre->id]
            );
        }

        return redirect()->route('workflowChapitres.edit',['workflowChapitre' => $workflowChapitre->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' => __('PkgAutoformation::workflowChapitre.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('workflowChapitre.edit_' . $id);


        $itemWorkflowChapitre = $this->workflowChapitreService->find($id);


        $sysColors = $this->sysColorService->all();
        

        $this->viewState->set('scope.etatChapitre.workflow_chapitre_id', $id);


        $etatChapitreService =  new EtatChapitreService();
        $etatChapitres_data =  $etatChapitreService->paginate();
        $etatChapitres_stats = $etatChapitreService->getetatChapitreStats();
        $etatChapitres_filters = $etatChapitreService->getFieldsFilterable();
        $etatChapitre_instance =  $etatChapitreService->createInstance();

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowChapitre._edit', compact('itemWorkflowChapitre', 'sysColors', 'etatChapitres_data', 'etatChapitres_stats', 'etatChapitres_filters', 'etatChapitre_instance'));
        }

        return view('PkgAutoformation::workflowChapitre.edit', compact('itemWorkflowChapitre', 'sysColors', 'etatChapitres_data', 'etatChapitres_stats', 'etatChapitres_filters', 'etatChapitre_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('workflowChapitre.edit_' . $id);


        $itemWorkflowChapitre = $this->workflowChapitreService->find($id);


        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.etatChapitre.workflow_chapitre_id', $id);
        

        $etatChapitreService =  new EtatChapitreService();
        $etatChapitres_data =  $etatChapitreService->paginate();
        $etatChapitres_stats = $etatChapitreService->getetatChapitreStats();
        $this->viewState->set('stats.etatChapitre.stats'  , $etatChapitres_stats);
        $etatChapitres_filters = $etatChapitreService->getFieldsFilterable();
        $etatChapitre_instance =  $etatChapitreService->createInstance();

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowChapitre._edit', compact('itemWorkflowChapitre', 'sysColors', 'etatChapitres_data', 'etatChapitres_stats', 'etatChapitres_filters', 'etatChapitre_instance'));
        }

        return view('PkgAutoformation::workflowChapitre.edit', compact('itemWorkflowChapitre', 'sysColors', 'etatChapitres_data', 'etatChapitres_stats', 'etatChapitres_filters', 'etatChapitre_instance'));

    }
    public function update(WorkflowChapitreRequest $request, string $id) {

        $validatedData = $request->validated();
        $workflowChapitre = $this->workflowChapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' =>  __('PkgAutoformation::workflowChapitre.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $workflowChapitre->id]
            );
        }

        return redirect()->route('workflowChapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' =>  __('PkgAutoformation::workflowChapitre.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $workflowChapitre = $this->workflowChapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' =>  __('PkgAutoformation::workflowChapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('workflowChapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' =>  __('PkgAutoformation::workflowChapitre.singular')
                ])
        );

    }

    public function export($format)
    {
        $workflowChapitres_data = $this->workflowChapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WorkflowChapitreExport($workflowChapitres_data,'csv'), 'workflowChapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WorkflowChapitreExport($workflowChapitres_data,'xlsx'), 'workflowChapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WorkflowChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('workflowChapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('workflowChapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::workflowChapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWorkflowChapitres()
    {
        $workflowChapitres = $this->workflowChapitreService->all();
        return response()->json($workflowChapitres);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $workflowChapitre = $this->workflowChapitreService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWorkflowChapitre = $this->workflowChapitreService->dataCalcul($workflowChapitre);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWorkflowChapitre
        ]);
    }
    

}
