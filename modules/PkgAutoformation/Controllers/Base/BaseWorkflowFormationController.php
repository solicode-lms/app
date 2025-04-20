<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\WorkflowFormationService;
use Modules\Core\Services\SysColorService;
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
    protected $sysColorService;

    public function __construct(WorkflowFormationService $workflowFormationService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $workflowFormationService;
        $this->workflowFormationService = $workflowFormationService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('workflowFormation.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('workflowFormation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $workflowFormations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'workflowFormations_search',
                $this->viewState->get("filter.workflowFormation.workflowFormations_search")
            )],
            $request->except(['workflowFormations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->workflowFormationService->prepareDataForIndexView($workflowFormations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutoformation::workflowFormation._index', $workflowFormation_compact_value)->render();
            }else{
                return view($workflowFormation_partialViewName, $workflowFormation_compact_value)->render();
            }
        }

        return view('PkgAutoformation::workflowFormation.index', $workflowFormation_compact_value);
    }
    /**
     */
    public function create() {


        $itemWorkflowFormation = $this->workflowFormationService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowFormation._fields', compact('itemWorkflowFormation', 'sysColors'));
        }
        return view('PkgAutoformation::workflowFormation.create', compact('itemWorkflowFormation', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $workflowFormation_ids = $request->input('ids', []);

        if (!is_array($workflowFormation_ids) || count($workflowFormation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemWorkflowFormation = $this->workflowFormationService->find($workflowFormation_ids[0]);
         
 
        $sysColors = $this->sysColorService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemWorkflowFormation = $this->workflowFormationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgAutoformation::workflowFormation._fields', compact('bulkEdit', 'workflowFormation_ids', 'itemWorkflowFormation', 'sysColors'));
        }
        return view('PkgAutoformation::workflowFormation.bulk-edit', compact('bulkEdit', 'workflowFormation_ids', 'itemWorkflowFormation', 'sysColors'));
    }
    /**
     */
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
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('workflowFormation.edit_' . $id);


        $itemWorkflowFormation = $this->workflowFormationService->edit($id);


        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.etatFormation.workflow_formation_id', $id);
        

        $etatFormationService =  new EtatFormationService();
        $etatFormations_view_data = $etatFormationService->prepareDataForIndexView();
        extract($etatFormations_view_data);

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowFormation._edit', array_merge(compact('itemWorkflowFormation','sysColors'),$etatFormation_compact_value));
        }

        return view('PkgAutoformation::workflowFormation.edit', array_merge(compact('itemWorkflowFormation','sysColors'),$etatFormation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('workflowFormation.edit_' . $id);


        $itemWorkflowFormation = $this->workflowFormationService->edit($id);


        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.etatFormation.workflow_formation_id', $id);
        

        $etatFormationService =  new EtatFormationService();
        $etatFormations_view_data = $etatFormationService->prepareDataForIndexView();
        extract($etatFormations_view_data);

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowFormation._edit', array_merge(compact('itemWorkflowFormation','sysColors'),$etatFormation_compact_value));
        }

        return view('PkgAutoformation::workflowFormation.edit', array_merge(compact('itemWorkflowFormation','sysColors'),$etatFormation_compact_value));


    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $workflowFormation_ids = $request->input('workflowFormation_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($workflowFormation_ids) || count($workflowFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($workflowFormation_ids as $id) {
            $entity = $this->workflowFormationService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->workflowFormationService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->workflowFormationService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $workflowFormation_ids = $request->input('ids', []);
        if (!is_array($workflowFormation_ids) || count($workflowFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($workflowFormation_ids as $id) {
            $entity = $this->workflowFormationService->find($id);
            $this->workflowFormationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($workflowFormation_ids) . ' éléments',
            'modelName' => __('PkgAutoformation::workflowFormation.plural')
        ]));
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
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $workflowFormationRequest = new WorkflowFormationRequest();
        $fullRules = $workflowFormationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:workflow_formations,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.', 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}