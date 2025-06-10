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

        if (request()->ajax()) {
            return view('Core::feature._fields', compact('itemFeature', 'permissions', 'featureDomains'));
        }
        return view('Core::feature.create', compact('itemFeature', 'permissions', 'featureDomains'));
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
             ['entity_id' => $feature->id]
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

        return view('Core::feature.edit', array_merge(compact('itemFeature','permissions', 'featureDomains'),));


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
                ['entity_id' => $feature->id]
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
    
        foreach ($feature_ids as $id) {
            $entity = $this->featureService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->featureService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->featureService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $feature = $this->featureService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedFeature = $this->featureService->dataCalcul($feature);
    
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
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}