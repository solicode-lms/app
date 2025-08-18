<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysModuleService;
use Modules\Core\Services\SysColorService;
use Modules\Core\Services\FeatureDomainService;
use Modules\Core\Services\SysControllerService;
use Modules\Core\Services\SysModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\App\Requests\SysModuleRequest;
use Modules\Core\Models\SysModule;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\App\Exports\SysModuleExport;
use Modules\Core\App\Imports\SysModuleImport;
use Modules\Core\Services\ContextState;

class BaseSysModuleController extends AdminController
{
    protected $sysModuleService;
    protected $sysColorService;

    public function __construct(SysModuleService $sysModuleService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $sysModuleService;
        $this->sysModuleService = $sysModuleService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('sysModule.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('sysModule');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $sysModules_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'sysModules_search',
                $this->viewState->get("filter.sysModule.sysModules_search")
            )],
            $request->except(['sysModules_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->sysModuleService->prepareDataForIndexView($sysModules_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('Core::sysModule._index', $sysModule_compact_value)->render();
            }else{
                return view($sysModule_partialViewName, $sysModule_compact_value)->render();
            }
        }

        return view('Core::sysModule.index', $sysModule_compact_value);
    }
    /**
     */
    public function create() {


        $itemSysModule = $this->sysModuleService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('Core::sysModule._fields', compact('bulkEdit' ,'itemSysModule', 'sysColors'));
        }
        return view('Core::sysModule.create', compact('bulkEdit' ,'itemSysModule', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $sysModule_ids = $request->input('ids', []);

        if (!is_array($sysModule_ids) || count($sysModule_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemSysModule = $this->sysModuleService->find($sysModule_ids[0]);
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemSysModule->sysColor);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemSysModule = $this->sysModuleService->createInstance();
        
        if (request()->ajax()) {
            return view('Core::sysModule._fields', compact('bulkEdit', 'sysModule_ids', 'itemSysModule', 'sysColors'));
        }
        return view('Core::sysModule.bulk-edit', compact('bulkEdit', 'sysModule_ids', 'itemSysModule', 'sysColors'));
    }
    /**
     */
    public function store(SysModuleRequest $request) {
        $validatedData = $request->validated();
        $sysModule = $this->sysModuleService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sysModule,
                'modelName' => __('Core::sysModule.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sysModule->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('sysModules.edit', ['sysModule' => $sysModule->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysModule,
                'modelName' => __('Core::sysModule.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('sysModule.show_' . $id);

        $itemSysModule = $this->sysModuleService->edit($id);


        $this->viewState->set('scope.featureDomain.sys_module_id', $id);
        

        $featureDomainService =  new FeatureDomainService();
        $featureDomains_view_data = $featureDomainService->prepareDataForIndexView();
        extract($featureDomains_view_data);

        $this->viewState->set('scope.sysController.sys_module_id', $id);
        

        $sysControllerService =  new SysControllerService();
        $sysControllers_view_data = $sysControllerService->prepareDataForIndexView();
        extract($sysControllers_view_data);

        $this->viewState->set('scope.sysModel.sys_module_id', $id);
        

        $sysModelService =  new SysModelService();
        $sysModels_view_data = $sysModelService->prepareDataForIndexView();
        extract($sysModels_view_data);

        if (request()->ajax()) {
            return view('Core::sysModule._show', array_merge(compact('itemSysModule'),$featureDomain_compact_value, $sysController_compact_value, $sysModel_compact_value));
        }

        return view('Core::sysModule.show', array_merge(compact('itemSysModule'),$featureDomain_compact_value, $sysController_compact_value, $sysModel_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('sysModule.edit_' . $id);


        $itemSysModule = $this->sysModuleService->edit($id);


        $sysColors = $this->sysColorService->getAllForSelect($itemSysModule->sysColor);


        $this->viewState->set('scope.featureDomain.sys_module_id', $id);
        

        $featureDomainService =  new FeatureDomainService();
        $featureDomains_view_data = $featureDomainService->prepareDataForIndexView();
        extract($featureDomains_view_data);

        $this->viewState->set('scope.sysController.sys_module_id', $id);
        

        $sysControllerService =  new SysControllerService();
        $sysControllers_view_data = $sysControllerService->prepareDataForIndexView();
        extract($sysControllers_view_data);

        $this->viewState->set('scope.sysModel.sys_module_id', $id);
        

        $sysModelService =  new SysModelService();
        $sysModels_view_data = $sysModelService->prepareDataForIndexView();
        extract($sysModels_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('Core::sysModule._edit', array_merge(compact('bulkEdit' , 'itemSysModule','sysColors'),$featureDomain_compact_value, $sysController_compact_value, $sysModel_compact_value));
        }

        return view('Core::sysModule.edit', array_merge(compact('bulkEdit' ,'itemSysModule','sysColors'),$featureDomain_compact_value, $sysController_compact_value, $sysModel_compact_value));


    }
    /**
     */
    public function update(SysModuleRequest $request, string $id) {

        $validatedData = $request->validated();
        $sysModule = $this->sysModuleService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sysModule,
                'modelName' =>  __('Core::sysModule.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sysModule->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('sysModules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysModule,
                'modelName' =>  __('Core::sysModule.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $sysModule_ids = $request->input('sysModule_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($sysModule_ids) || count($sysModule_ids) === 0) {
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
            Auth::id(),
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $sysModule_ids,
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

        $sysModule = $this->sysModuleService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModule,
                'modelName' =>  __('Core::sysModule.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('sysModules.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModule,
                'modelName' =>  __('Core::sysModule.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $sysModule_ids = $request->input('ids', []);
        if (!is_array($sysModule_ids) || count($sysModule_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($sysModule_ids as $id) {
            $entity = $this->sysModuleService->find($id);
            $this->sysModuleService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($sysModule_ids) . ' éléments',
            'modelName' => __('Core::sysModule.plural')
        ]));
    }

    public function export($format)
    {
        $sysModules_data = $this->sysModuleService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SysModuleExport($sysModules_data,'csv'), 'sysModule_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SysModuleExport($sysModules_data,'xlsx'), 'sysModule_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SysModuleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysModules.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysModules.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysModule.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysModules()
    {
        $sysModules = $this->sysModuleService->all();
        return response()->json($sysModules);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (SysModule) par ID, en format JSON.
     */
    public function getSysModule(Request $request, $id)
    {
        try {
            $sysModule = $this->sysModuleService->find($id);
            return response()->json($sysModule);
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
        $updatedSysModule = $this->sysModuleService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedSysModule],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
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
        $sysModuleRequest = new SysModuleRequest();
        $fullRules = $sysModuleRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:sys_modules,id'];
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