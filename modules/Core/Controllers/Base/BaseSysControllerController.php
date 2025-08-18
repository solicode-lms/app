<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysControllerService;
use Modules\Core\Services\SysModuleService;
use Modules\PkgAutorisation\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\App\Requests\SysControllerRequest;
use Modules\Core\Models\SysController;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\App\Exports\SysControllerExport;
use Modules\Core\App\Imports\SysControllerImport;
use Modules\Core\Services\ContextState;

class BaseSysControllerController extends AdminController
{
    protected $sysControllerService;
    protected $sysModuleService;

    public function __construct(SysControllerService $sysControllerService, SysModuleService $sysModuleService) {
        parent::__construct();
        $this->service  =  $sysControllerService;
        $this->sysControllerService = $sysControllerService;
        $this->sysModuleService = $sysModuleService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('sysController.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('sysController');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $sysControllers_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'sysControllers_search',
                $this->viewState->get("filter.sysController.sysControllers_search")
            )],
            $request->except(['sysControllers_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->sysControllerService->prepareDataForIndexView($sysControllers_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('Core::sysController._index', $sysController_compact_value)->render();
            }else{
                return view($sysController_partialViewName, $sysController_compact_value)->render();
            }
        }

        return view('Core::sysController.index', $sysController_compact_value);
    }
    /**
     */
    public function create() {


        $itemSysController = $this->sysControllerService->createInstance();
        

        $sysModules = $this->sysModuleService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('Core::sysController._fields', compact('bulkEdit' ,'itemSysController', 'sysModules'));
        }
        return view('Core::sysController.create', compact('bulkEdit' ,'itemSysController', 'sysModules'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $sysController_ids = $request->input('ids', []);

        if (!is_array($sysController_ids) || count($sysController_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemSysController = $this->sysControllerService->find($sysController_ids[0]);
         
 
        $sysModules = $this->sysModuleService->getAllForSelect($itemSysController->sysModule);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemSysController = $this->sysControllerService->createInstance();
        
        if (request()->ajax()) {
            return view('Core::sysController._fields', compact('bulkEdit', 'sysController_ids', 'itemSysController', 'sysModules'));
        }
        return view('Core::sysController.bulk-edit', compact('bulkEdit', 'sysController_ids', 'itemSysController', 'sysModules'));
    }
    /**
     */
    public function store(SysControllerRequest $request) {
        $validatedData = $request->validated();
        $sysController = $this->sysControllerService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sysController,
                'modelName' => __('Core::sysController.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sysController->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('sysControllers.edit', ['sysController' => $sysController->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysController,
                'modelName' => __('Core::sysController.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('sysController.show_' . $id);

        $itemSysController = $this->sysControllerService->edit($id);


        $this->viewState->set('scope.permission.controller_id', $id);
        

        $permissionService =  new PermissionService();
        $permissions_view_data = $permissionService->prepareDataForIndexView();
        extract($permissions_view_data);

        if (request()->ajax()) {
            return view('Core::sysController._show', array_merge(compact('itemSysController'),$permission_compact_value));
        }

        return view('Core::sysController.show', array_merge(compact('itemSysController'),$permission_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('sysController.edit_' . $id);


        $itemSysController = $this->sysControllerService->edit($id);


        $sysModules = $this->sysModuleService->getAllForSelect($itemSysController->sysModule);


        $this->viewState->set('scope.permission.controller_id', $id);
        

        $permissionService =  new PermissionService();
        $permissions_view_data = $permissionService->prepareDataForIndexView();
        extract($permissions_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('Core::sysController._edit', array_merge(compact('bulkEdit' , 'itemSysController','sysModules'),$permission_compact_value));
        }

        return view('Core::sysController.edit', array_merge(compact('bulkEdit' ,'itemSysController','sysModules'),$permission_compact_value));


    }
    /**
     */
    public function update(SysControllerRequest $request, string $id) {

        $validatedData = $request->validated();
        $sysController = $this->sysControllerService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sysController->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('sysControllers.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $sysController_ids = $request->input('sysController_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($sysController_ids) || count($sysController_ids) === 0) {
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
            $sysController_ids,
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

        $sysController = $this->sysControllerService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('sysControllers.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $sysController_ids = $request->input('ids', []);
        if (!is_array($sysController_ids) || count($sysController_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($sysController_ids as $id) {
            $entity = $this->sysControllerService->find($id);
            $this->sysControllerService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($sysController_ids) . ' éléments',
            'modelName' => __('Core::sysController.plural')
        ]));
    }

    public function export($format)
    {
        $sysControllers_data = $this->sysControllerService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SysControllerExport($sysControllers_data,'csv'), 'sysController_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SysControllerExport($sysControllers_data,'xlsx'), 'sysController_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SysControllerImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysControllers.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysControllers.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysController.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysControllers()
    {
        $sysControllers = $this->sysControllerService->all();
        return response()->json($sysControllers);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (SysController) par ID, en format JSON.
     */
    public function getSysController(Request $request, $id)
    {
        try {
            $sysController = $this->sysControllerService->find($id);
            return response()->json($sysController);
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
        $updatedSysController = $this->sysControllerService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedSysController],
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
        $sysControllerRequest = new SysControllerRequest();
        $fullRules = $sysControllerRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:sys_controllers,id'];
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