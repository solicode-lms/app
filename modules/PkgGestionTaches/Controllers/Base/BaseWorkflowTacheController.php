<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\WorkflowTacheService;
use Modules\PkgGestionTaches\Services\EtatRealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\WorkflowTacheRequest;
use Modules\PkgGestionTaches\Models\WorkflowTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\WorkflowTacheExport;
use Modules\PkgGestionTaches\App\Imports\WorkflowTacheImport;
use Modules\Core\Services\ContextState;

class BaseWorkflowTacheController extends AdminController
{
    protected $workflowTacheService;

    public function __construct(WorkflowTacheService $workflowTacheService) {
        parent::__construct();
        $this->workflowTacheService = $workflowTacheService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('workflowTache.index');



        // Extraire les paramètres de recherche, page, et filtres
        $workflowTaches_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('workflowTaches_search', $this->viewState->get("filter.workflowTache.workflowTaches_search"))],
            $request->except(['workflowTaches_search', 'page', 'sort'])
        );

        // Paginer les workflowTaches
        $workflowTaches_data = $this->workflowTacheService->paginate($workflowTaches_params);

        // Récupérer les statistiques et les champs filtrables
        $workflowTaches_stats = $this->workflowTacheService->getworkflowTacheStats();
        $this->viewState->set('stats.workflowTache.stats'  , $workflowTaches_stats);
        $workflowTaches_filters = $this->workflowTacheService->getFieldsFilterable();
        $workflowTache_instance =  $this->workflowTacheService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGestionTaches::workflowTache._table', compact('workflowTaches_data', 'workflowTaches_stats', 'workflowTaches_filters','workflowTache_instance'))->render();
        }

        return view('PkgGestionTaches::workflowTache.index', compact('workflowTaches_data', 'workflowTaches_stats', 'workflowTaches_filters','workflowTache_instance'));
    }
    public function create() {


        $itemWorkflowTache = $this->workflowTacheService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgGestionTaches::workflowTache._fields', compact('itemWorkflowTache'));
        }
        return view('PkgGestionTaches::workflowTache.create', compact('itemWorkflowTache'));
    }
    public function store(WorkflowTacheRequest $request) {
        $validatedData = $request->validated();
        $workflowTache = $this->workflowTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $workflowTache,
                'modelName' => __('PkgGestionTaches::workflowTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $workflowTache->id]
            );
        }

        return redirect()->route('workflowTaches.edit',['workflowTache' => $workflowTache->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $workflowTache,
                'modelName' => __('PkgGestionTaches::workflowTache.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('workflowTache.edit_' . $id);


        $itemWorkflowTache = $this->workflowTacheService->find($id);




        $this->viewState->set('scope.etatRealisationTache.workflow_tache_id', $id);


        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_data =  $etatRealisationTacheService->paginate();
        $etatRealisationTaches_stats = $etatRealisationTacheService->getetatRealisationTacheStats();
        $etatRealisationTaches_filters = $etatRealisationTacheService->getFieldsFilterable();
        $etatRealisationTache_instance =  $etatRealisationTacheService->createInstance();

        if (request()->ajax()) {
            return view('PkgGestionTaches::workflowTache._edit', compact('itemWorkflowTache', 'etatRealisationTaches_data', 'etatRealisationTaches_stats', 'etatRealisationTaches_filters', 'etatRealisationTache_instance'));
        }

        return view('PkgGestionTaches::workflowTache.edit', compact('itemWorkflowTache', 'etatRealisationTaches_data', 'etatRealisationTaches_stats', 'etatRealisationTaches_filters', 'etatRealisationTache_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('workflowTache.edit_' . $id);


        $itemWorkflowTache = $this->workflowTacheService->find($id);




        $this->viewState->set('scope.etatRealisationTache.workflow_tache_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_data =  $etatRealisationTacheService->paginate();
        $etatRealisationTaches_stats = $etatRealisationTacheService->getetatRealisationTacheStats();
        $this->viewState->set('stats.etatRealisationTache.stats'  , $etatRealisationTaches_stats);
        $etatRealisationTaches_filters = $etatRealisationTacheService->getFieldsFilterable();
        $etatRealisationTache_instance =  $etatRealisationTacheService->createInstance();

        if (request()->ajax()) {
            return view('PkgGestionTaches::workflowTache._edit', compact('itemWorkflowTache', 'etatRealisationTaches_data', 'etatRealisationTaches_stats', 'etatRealisationTaches_filters', 'etatRealisationTache_instance'));
        }

        return view('PkgGestionTaches::workflowTache.edit', compact('itemWorkflowTache', 'etatRealisationTaches_data', 'etatRealisationTaches_stats', 'etatRealisationTaches_filters', 'etatRealisationTache_instance'));

    }
    public function update(WorkflowTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $workflowTache = $this->workflowTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $workflowTache,
                'modelName' =>  __('PkgGestionTaches::workflowTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $workflowTache->id]
            );
        }

        return redirect()->route('workflowTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $workflowTache,
                'modelName' =>  __('PkgGestionTaches::workflowTache.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $workflowTache = $this->workflowTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowTache,
                'modelName' =>  __('PkgGestionTaches::workflowTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('workflowTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowTache,
                'modelName' =>  __('PkgGestionTaches::workflowTache.singular')
                ])
        );

    }

    public function export($format)
    {
        $workflowTaches_data = $this->workflowTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WorkflowTacheExport($workflowTaches_data,'csv'), 'workflowTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WorkflowTacheExport($workflowTaches_data,'xlsx'), 'workflowTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WorkflowTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('workflowTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('workflowTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::workflowTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWorkflowTaches()
    {
        $workflowTaches = $this->workflowTacheService->all();
        return response()->json($workflowTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $workflowTache = $this->workflowTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWorkflowTache = $this->workflowTacheService->dataCalcul($workflowTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWorkflowTache
        ]);
    }
    

}
