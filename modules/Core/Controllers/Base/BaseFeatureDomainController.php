<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\FeatureDomainService;
use Modules\Core\Services\SysModuleService;
use Modules\Core\Services\FeatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\App\Requests\FeatureDomainRequest;
use Modules\Core\Models\FeatureDomain;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\FeatureDomainExport;
use Modules\Core\App\Imports\FeatureDomainImport;
use Modules\Core\Services\ContextState;

class BaseFeatureDomainController extends AdminController
{
    protected $featureDomainService;
    protected $sysModuleService;

    public function __construct(FeatureDomainService $featureDomainService, SysModuleService $sysModuleService) {
        parent::__construct();
        $this->service  =  $featureDomainService;
        $this->featureDomainService = $featureDomainService;
        $this->sysModuleService = $sysModuleService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('featureDomain.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('featureDomain');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $featureDomains_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'featureDomains_search',
                $this->viewState->get("filter.featureDomain.featureDomains_search")
            )],
            $request->except(['featureDomains_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->featureDomainService->prepareDataForIndexView($featureDomains_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('Core::featureDomain._index', $featureDomain_compact_value)->render();
            }else{
                return view($featureDomain_partialViewName, $featureDomain_compact_value)->render();
            }
        }

        return view('Core::featureDomain.index', $featureDomain_compact_value);
    }
    /**
     */
    public function create() {


        $itemFeatureDomain = $this->featureDomainService->createInstance();
        

        $sysModules = $this->sysModuleService->all();

        if (request()->ajax()) {
            return view('Core::featureDomain._fields', compact('itemFeatureDomain', 'sysModules'));
        }
        return view('Core::featureDomain.create', compact('itemFeatureDomain', 'sysModules'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $featureDomain_ids = $request->input('ids', []);

        if (!is_array($featureDomain_ids) || count($featureDomain_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemFeatureDomain = $this->featureDomainService->find($featureDomain_ids[0]);
         
 
        $sysModules = $this->sysModuleService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemFeatureDomain = $this->featureDomainService->createInstance();
        
        if (request()->ajax()) {
            return view('Core::featureDomain._fields', compact('bulkEdit', 'featureDomain_ids', 'itemFeatureDomain', 'sysModules'));
        }
        return view('Core::featureDomain.bulk-edit', compact('bulkEdit', 'featureDomain_ids', 'itemFeatureDomain', 'sysModules'));
    }
    /**
     */
    public function store(FeatureDomainRequest $request) {
        $validatedData = $request->validated();
        $featureDomain = $this->featureDomainService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $featureDomain,
                'modelName' => __('Core::featureDomain.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $featureDomain->id]
            );
        }

        return redirect()->route('featureDomains.edit',['featureDomain' => $featureDomain->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $featureDomain,
                'modelName' => __('Core::featureDomain.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('featureDomain.show_' . $id);

        $itemFeatureDomain = $this->featureDomainService->edit($id);


        $this->viewState->set('scope.feature.feature_domain_id', $id);
        

        $featureService =  new FeatureService();
        $features_view_data = $featureService->prepareDataForIndexView();
        extract($features_view_data);

        if (request()->ajax()) {
            return view('Core::featureDomain._show', array_merge(compact('itemFeatureDomain'),$feature_compact_value));
        }

        return view('Core::featureDomain.show', array_merge(compact('itemFeatureDomain'),$feature_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('featureDomain.edit_' . $id);


        $itemFeatureDomain = $this->featureDomainService->edit($id);


        $sysModules = $this->sysModuleService->all();


        $this->viewState->set('scope.feature.feature_domain_id', $id);
        

        $featureService =  new FeatureService();
        $features_view_data = $featureService->prepareDataForIndexView();
        extract($features_view_data);

        if (request()->ajax()) {
            return view('Core::featureDomain._edit', array_merge(compact('itemFeatureDomain','sysModules'),$feature_compact_value));
        }

        return view('Core::featureDomain.edit', array_merge(compact('itemFeatureDomain','sysModules'),$feature_compact_value));


    }
    /**
     */
    public function update(FeatureDomainRequest $request, string $id) {

        $validatedData = $request->validated();
        $featureDomain = $this->featureDomainService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $featureDomain,
                'modelName' =>  __('Core::featureDomain.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $featureDomain->id]
            );
        }

        return redirect()->route('featureDomains.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $featureDomain,
                'modelName' =>  __('Core::featureDomain.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $featureDomain_ids = $request->input('featureDomain_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($featureDomain_ids) || count($featureDomain_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($featureDomain_ids as $id) {
            $entity = $this->featureDomainService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->featureDomainService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->featureDomainService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $featureDomain = $this->featureDomainService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $featureDomain,
                'modelName' =>  __('Core::featureDomain.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('featureDomains.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $featureDomain,
                'modelName' =>  __('Core::featureDomain.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $featureDomain_ids = $request->input('ids', []);
        if (!is_array($featureDomain_ids) || count($featureDomain_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($featureDomain_ids as $id) {
            $entity = $this->featureDomainService->find($id);
            $this->featureDomainService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($featureDomain_ids) . ' éléments',
            'modelName' => __('Core::featureDomain.plural')
        ]));
    }

    public function export($format)
    {
        $featureDomains_data = $this->featureDomainService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new FeatureDomainExport($featureDomains_data,'csv'), 'featureDomain_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new FeatureDomainExport($featureDomains_data,'xlsx'), 'featureDomain_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new FeatureDomainImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('featureDomains.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('featureDomains.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::featureDomain.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFeatureDomains()
    {
        $featureDomains = $this->featureDomainService->all();
        return response()->json($featureDomains);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $featureDomain = $this->featureDomainService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedFeatureDomain = $this->featureDomainService->dataCalcul($featureDomain);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedFeatureDomain
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
        $featureDomainRequest = new FeatureDomainRequest();
        $fullRules = $featureDomainRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:feature_domains,id'];
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