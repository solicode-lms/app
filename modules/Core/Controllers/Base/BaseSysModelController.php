<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysModelService;
use Modules\Core\Services\SysColorService;
use Modules\Core\Services\SysModuleService;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\App\Requests\SysModelRequest;
use Modules\Core\Models\SysModel;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\App\Exports\SysModelExport;
use Modules\Core\App\Imports\SysModelImport;
use Modules\Core\Services\ContextState;

class BaseSysModelController extends AdminController
{
    protected $sysModelService;
    protected $sysColorService;
    protected $sysModuleService;

    public function __construct(SysModelService $sysModelService, SysColorService $sysColorService, SysModuleService $sysModuleService) {
        parent::__construct();
        $this->service  =  $sysModelService;
        $this->sysModelService = $sysModelService;
        $this->sysColorService = $sysColorService;
        $this->sysModuleService = $sysModuleService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('sysModel.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('sysModel');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $sysModels_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'sysModels_search',
                $this->viewState->get("filter.sysModel.sysModels_search")
            )],
            $request->except(['sysModels_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->sysModelService->prepareDataForIndexView($sysModels_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('Core::sysModel._index', $sysModel_compact_value)->render();
            }else{
                return view($sysModel_partialViewName, $sysModel_compact_value)->render();
            }
        }

        return view('Core::sysModel.index', $sysModel_compact_value);
    }
    /**
     */
    public function create() {


        $itemSysModel = $this->sysModelService->createInstance();
        

        $sysModules = $this->sysModuleService->all();
        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('bulkEdit' ,'itemSysModel', 'sysColors', 'sysModules'));
        }
        return view('Core::sysModel.create', compact('bulkEdit' ,'itemSysModel', 'sysColors', 'sysModules'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $sysModel_ids = $request->input('ids', []);

        if (!is_array($sysModel_ids) || count($sysModel_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemSysModel = $this->sysModelService->find($sysModel_ids[0]);
         
 
        $sysModules = $this->sysModuleService->all();
        $sysColors = $this->sysColorService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemSysModel = $this->sysModelService->createInstance();
        
        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('bulkEdit', 'sysModel_ids', 'itemSysModel', 'sysColors', 'sysModules'));
        }
        return view('Core::sysModel.bulk-edit', compact('bulkEdit', 'sysModel_ids', 'itemSysModel', 'sysColors', 'sysModules'));
    }
    /**
     */
    public function store(SysModelRequest $request) {
        $validatedData = $request->validated();
        $sysModel = $this->sysModelService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sysModel,
                'modelName' => __('Core::sysModel.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sysModel->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('sysModels.edit', ['sysModel' => $sysModel->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysModel,
                'modelName' => __('Core::sysModel.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('sysModel.show_' . $id);

        $itemSysModel = $this->sysModelService->edit($id);


        $this->viewState->set('scope.widget.model_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        if (request()->ajax()) {
            return view('Core::sysModel._show', array_merge(compact('itemSysModel'),$widget_compact_value));
        }

        return view('Core::sysModel.show', array_merge(compact('itemSysModel'),$widget_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('sysModel.edit_' . $id);


        $itemSysModel = $this->sysModelService->edit($id);


        $sysModules = $this->sysModuleService->all();
        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.widget.model_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('Core::sysModel._edit', array_merge(compact('bulkEdit' , 'itemSysModel','sysColors', 'sysModules'),$widget_compact_value));
        }

        return view('Core::sysModel.edit', array_merge(compact('bulkEdit' ,'itemSysModel','sysColors', 'sysModules'),$widget_compact_value));


    }
    /**
     */
    public function update(SysModelRequest $request, string $id) {

        $validatedData = $request->validated();
        $sysModel = $this->sysModelService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sysModel->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $sysModel_ids = $request->input('sysModel_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($sysModel_ids) || count($sysModel_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }

        // 🔹 Récupérer les valeurs de ces champs
        $valeursChamps = [];
        foreach ($champsCoches as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob",$this->service->modelName,$this->service->moduleName);
         
        dispatch(new BulkEditJob(
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $sysModel_ids,
            $champsCoches,
            $valeursChamps
        ));

       
        return JsonResponseHelper::success(
             __('Mise à jour en masse effectuée avec succès.'),
                ['traitement_token' => $jobManager->getToken()]
        );

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $sysModel = $this->sysModelService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $sysModel_ids = $request->input('ids', []);
        if (!is_array($sysModel_ids) || count($sysModel_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($sysModel_ids as $id) {
            $entity = $this->sysModelService->find($id);
            $this->sysModelService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($sysModel_ids) . ' éléments',
            'modelName' => __('Core::sysModel.plural')
        ]));
    }

    public function export($format)
    {
        $sysModels_data = $this->sysModelService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SysModelExport($sysModels_data,'csv'), 'sysModel_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SysModelExport($sysModels_data,'xlsx'), 'sysModel_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SysModelImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysModels.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysModels.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysModel.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysModels()
    {
        $sysModels = $this->sysModelService->all();
        return response()->json($sysModels);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (SysModel) par ID, en format JSON.
     */
    public function getSysModel(Request $request, $id)
    {
        try {
            $sysModel = $this->sysModelService->find($id);
            return response()->json($sysModel);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Entité non trouvée ou erreur.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    public function dataCalcul(Request $request)
    {
        $data = $request->all();

        // Traitement métier personnalisé (ne modifie pas la base)
        $updatedSysModel = $this->sysModelService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedSysModel
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
        $sysModelRequest = new SysModelRequest();
        $fullRules = $sysModelRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:sys_models,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(
             __('Mise à jour réussie.'),
                array_merge(
                    ['entity_id' => $validated['id']],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
        );
    }
}