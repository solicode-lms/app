<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\RealisationModuleService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprentissage\Services\EtatRealisationModuleService;
use Modules\PkgFormation\Services\ModuleService;
use Modules\PkgApprentissage\Services\RealisationCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\RealisationModuleRequest;
use Modules\PkgApprentissage\Models\RealisationModule;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\RealisationModuleExport;
use Modules\PkgApprentissage\App\Imports\RealisationModuleImport;
use Modules\Core\Services\ContextState;

class BaseRealisationModuleController extends AdminController
{
    protected $realisationModuleService;
    protected $apprenantService;
    protected $etatRealisationModuleService;
    protected $moduleService;

    public function __construct(RealisationModuleService $realisationModuleService, ApprenantService $apprenantService, EtatRealisationModuleService $etatRealisationModuleService, ModuleService $moduleService) {
        parent::__construct();
        $this->service  =  $realisationModuleService;
        $this->realisationModuleService = $realisationModuleService;
        $this->apprenantService = $apprenantService;
        $this->etatRealisationModuleService = $etatRealisationModuleService;
        $this->moduleService = $moduleService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationModule.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationModule');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $realisationModules_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationModules_search',
                $this->viewState->get("filter.realisationModule.realisationModules_search")
            )],
            $request->except(['realisationModules_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationModuleService->prepareDataForIndexView($realisationModules_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::realisationModule._index', $realisationModule_compact_value)->render();
            }else{
                return view($realisationModule_partialViewName, $realisationModule_compact_value)->render();
            }
        }

        return view('PkgApprentissage::realisationModule.index', $realisationModule_compact_value);
    }
    /**
     */
    public function create() {


        $itemRealisationModule = $this->realisationModuleService->createInstance();
        

        $apprenants = $this->apprenantService->all();
        $modules = $this->moduleService->all();
        $etatRealisationModules = $this->etatRealisationModuleService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationModule._fields', compact('bulkEdit' ,'itemRealisationModule', 'apprenants', 'etatRealisationModules', 'modules'));
        }
        return view('PkgApprentissage::realisationModule.create', compact('bulkEdit' ,'itemRealisationModule', 'apprenants', 'etatRealisationModules', 'modules'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationModule_ids = $request->input('ids', []);

        if (!is_array($realisationModule_ids) || count($realisationModule_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRealisationModule = $this->realisationModuleService->find($realisationModule_ids[0]);
         
 
        $apprenants = $this->apprenantService->all();
        $modules = $this->moduleService->all();
        $etatRealisationModules = $this->etatRealisationModuleService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationModule = $this->realisationModuleService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationModule._fields', compact('bulkEdit', 'realisationModule_ids', 'itemRealisationModule', 'apprenants', 'etatRealisationModules', 'modules'));
        }
        return view('PkgApprentissage::realisationModule.bulk-edit', compact('bulkEdit', 'realisationModule_ids', 'itemRealisationModule', 'apprenants', 'etatRealisationModules', 'modules'));
    }
    /**
     */
    public function store(RealisationModuleRequest $request) {
        $validatedData = $request->validated();
        $realisationModule = $this->realisationModuleService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationModule,
                'modelName' => __('PkgApprentissage::realisationModule.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationModule->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('realisationModules.edit', ['realisationModule' => $realisationModule->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationModule,
                'modelName' => __('PkgApprentissage::realisationModule.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationModule.show_' . $id);

        $itemRealisationModule = $this->realisationModuleService->edit($id);


        $this->viewState->set('scope.realisationCompetence.realisation_module_id', $id);
        

        $realisationCompetenceService =  new RealisationCompetenceService();
        $realisationCompetences_view_data = $realisationCompetenceService->prepareDataForIndexView();
        extract($realisationCompetences_view_data);

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationModule._show', array_merge(compact('itemRealisationModule'),$realisationCompetence_compact_value));
        }

        return view('PkgApprentissage::realisationModule.show', array_merge(compact('itemRealisationModule'),$realisationCompetence_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationModule.edit_' . $id);


        $itemRealisationModule = $this->realisationModuleService->edit($id);


        $apprenants = $this->apprenantService->all();
        $modules = $this->moduleService->all();
        $etatRealisationModules = $this->etatRealisationModuleService->all();


        $this->viewState->set('scope.realisationCompetence.realisation_module_id', $id);
        

        $realisationCompetenceService =  new RealisationCompetenceService();
        $realisationCompetences_view_data = $realisationCompetenceService->prepareDataForIndexView();
        extract($realisationCompetences_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationModule._edit', array_merge(compact('bulkEdit' , 'itemRealisationModule','apprenants', 'etatRealisationModules', 'modules'),$realisationCompetence_compact_value));
        }

        return view('PkgApprentissage::realisationModule.edit', array_merge(compact('bulkEdit' ,'itemRealisationModule','apprenants', 'etatRealisationModules', 'modules'),$realisationCompetence_compact_value));


    }
    /**
     */
    public function update(RealisationModuleRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationModule = $this->realisationModuleService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationModule,
                'modelName' =>  __('PkgApprentissage::realisationModule.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationModule->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('realisationModules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationModule,
                'modelName' =>  __('PkgApprentissage::realisationModule.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $realisationModule_ids = $request->input('realisationModule_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($realisationModule_ids) || count($realisationModule_ids) === 0) {
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
            $realisationModule_ids,
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

        $realisationModule = $this->realisationModuleService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationModule,
                'modelName' =>  __('PkgApprentissage::realisationModule.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationModules.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationModule,
                'modelName' =>  __('PkgApprentissage::realisationModule.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationModule_ids = $request->input('ids', []);
        if (!is_array($realisationModule_ids) || count($realisationModule_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationModule_ids as $id) {
            $entity = $this->realisationModuleService->find($id);
            $this->realisationModuleService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationModule_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::realisationModule.plural')
        ]));
    }

    public function export($format)
    {
        $realisationModules_data = $this->realisationModuleService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationModuleExport($realisationModules_data,'csv'), 'realisationModule_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationModuleExport($realisationModules_data,'xlsx'), 'realisationModule_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationModuleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationModules.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationModules.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::realisationModule.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationModules()
    {
        $realisationModules = $this->realisationModuleService->all();
        return response()->json($realisationModules);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (RealisationModule) par ID, en format JSON.
     */
    public function getRealisationModule(Request $request, $id)
    {
        try {
            $realisationModule = $this->realisationModuleService->find($id);
            return response()->json($realisationModule);
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
        $updatedRealisationModule = $this->realisationModuleService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationModule
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
        $realisationModuleRequest = new RealisationModuleRequest();
        $fullRules = $realisationModuleRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_modules,id'];
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