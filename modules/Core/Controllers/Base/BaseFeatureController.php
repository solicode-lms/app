<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\FeatureService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\Core\Services\FeatureDomainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\App\Requests\FeatureRequest;
use Modules\Core\Models\Feature;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\App\Exports\FeatureExport;
use Modules\Core\App\Imports\FeatureImport;
use Modules\Core\Services\ContextState;

class BaseFeatureController extends AdminController
{
    protected $featureService;
    protected $permissionService;
    protected $featureDomainService;

    public function __construct(FeatureService $featureService, PermissionService $permissionService, FeatureDomainService $featureDomainService) {
        parent::__construct();
        $this->service  =  $featureService;
        $this->featureService = $featureService;
        $this->permissionService = $permissionService;
        $this->featureDomainService = $featureDomainService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('feature.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('feature');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $features_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'features_search',
                $this->viewState->get("filter.feature.features_search")
            )],
            $request->except(['features_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->featureService->prepareDataForIndexView($features_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('Core::feature._index', $feature_compact_value)->render();
            }else{
                return view($feature_partialViewName, $feature_compact_value)->render();
            }
        }

        return view('Core::feature.index', $feature_compact_value);
    }
    /**
     */
    public function create() {


        $itemFeature = $this->featureService->createInstance();
        

        $featureDomains = $this->featureDomainService->all();
        $permissions = $this->permissionService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('Core::feature._fields', compact('bulkEdit' ,'itemFeature', 'permissions', 'featureDomains'));
        }
        return view('Core::feature.create', compact('bulkEdit' ,'itemFeature', 'permissions', 'featureDomains'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $feature_ids = $request->input('ids', []);

        if (!is_array($feature_ids) || count($feature_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemFeature = $this->featureService->find($feature_ids[0]);
         
 
        $featureDomains = $this->featureDomainService->all();
        $permissions = $this->permissionService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemFeature = $this->featureService->createInstance();
        
        if (request()->ajax()) {
            return view('Core::feature._fields', compact('bulkEdit', 'feature_ids', 'itemFeature', 'permissions', 'featureDomains'));
        }
        return view('Core::feature.bulk-edit', compact('bulkEdit', 'feature_ids', 'itemFeature', 'permissions', 'featureDomains'));
    }
    /**
     */
    public function store(FeatureRequest $request) {
        $validatedData = $request->validated();
        $feature = $this->featureService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $feature,
                'modelName' => __('Core::feature.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $feature->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('features.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $feature,
                'modelName' => __('Core::feature.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('feature.show_' . $id);

        $itemFeature = $this->featureService->edit($id);


        if (request()->ajax()) {
            return view('Core::feature._show', array_merge(compact('itemFeature'),));
        }

        return view('Core::feature.show', array_merge(compact('itemFeature'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('feature.edit_' . $id);


        $itemFeature = $this->featureService->edit($id);


        $featureDomains = $this->featureDomainService->all();
        $permissions = $this->permissionService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('Core::feature._fields', array_merge(compact('bulkEdit' , 'itemFeature','permissions', 'featureDomains'),));
        }

        return view('Core::feature.edit', array_merge(compact('bulkEdit' ,'itemFeature','permissions', 'featureDomains'),));


    }
    /**
     */
    public function update(FeatureRequest $request, string $id) {

        $validatedData = $request->validated();
        $feature = $this->featureService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $feature,
                'modelName' =>  __('Core::feature.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $feature->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('features.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $feature,
                'modelName' =>  __('Core::feature.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $feature_ids = $request->input('feature_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($feature_ids) || count($feature_ids) === 0) {
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
            $feature_ids,
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

        $feature = $this->featureService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $feature,
                'modelName' =>  __('Core::feature.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('features.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $feature,
                'modelName' =>  __('Core::feature.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $feature_ids = $request->input('ids', []);
        if (!is_array($feature_ids) || count($feature_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($feature_ids as $id) {
            $entity = $this->featureService->find($id);
            $this->featureService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($feature_ids) . ' éléments',
            'modelName' => __('Core::feature.plural')
        ]));
    }

    public function export($format)
    {
        $features_data = $this->featureService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new FeatureExport($features_data,'csv'), 'feature_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new FeatureExport($features_data,'xlsx'), 'feature_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new FeatureImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('features.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('features.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::feature.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFeatures()
    {
        $features = $this->featureService->all();
        return response()->json($features);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Feature) par ID, en format JSON.
     */
    public function getFeature(Request $request, $id)
    {
        try {
            $feature = $this->featureService->find($id);
            return response()->json($feature);
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
        $updatedFeature = $this->featureService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedFeature
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
        $featureRequest = new FeatureRequest();
        $fullRules = $featureRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:features,id'];
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