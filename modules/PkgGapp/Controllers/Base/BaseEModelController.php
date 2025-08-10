<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EModelService;
use Modules\PkgGapp\Services\EPackageService;
use Modules\PkgGapp\Services\EDataFieldService;
use Modules\PkgGapp\Services\EMetadatumService;
use Modules\PkgGapp\Services\ERelationshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EModelRequest;
use Modules\PkgGapp\Models\EModel;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgGapp\App\Exports\EModelExport;
use Modules\PkgGapp\App\Imports\EModelImport;
use Modules\Core\Services\ContextState;

class BaseEModelController extends AdminController
{
    protected $eModelService;
    protected $ePackageService;

    public function __construct(EModelService $eModelService, EPackageService $ePackageService) {
        parent::__construct();
        $this->service  =  $eModelService;
        $this->eModelService = $eModelService;
        $this->ePackageService = $ePackageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('eModel.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('eModel');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $eModels_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'eModels_search',
                $this->viewState->get("filter.eModel.eModels_search")
            )],
            $request->except(['eModels_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->eModelService->prepareDataForIndexView($eModels_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGapp::eModel._index', $eModel_compact_value)->render();
            }else{
                return view($eModel_partialViewName, $eModel_compact_value)->render();
            }
        }

        return view('PkgGapp::eModel.index', $eModel_compact_value);
    }
    /**
     */
    public function create() {


        $itemEModel = $this->eModelService->createInstance();
        

        $ePackages = $this->ePackageService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgGapp::eModel._fields', compact('bulkEdit' ,'itemEModel', 'ePackages'));
        }
        return view('PkgGapp::eModel.create', compact('bulkEdit' ,'itemEModel', 'ePackages'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $eModel_ids = $request->input('ids', []);

        if (!is_array($eModel_ids) || count($eModel_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEModel = $this->eModelService->find($eModel_ids[0]);
         
 
        $ePackages = $this->ePackageService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEModel = $this->eModelService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGapp::eModel._fields', compact('bulkEdit', 'eModel_ids', 'itemEModel', 'ePackages'));
        }
        return view('PkgGapp::eModel.bulk-edit', compact('bulkEdit', 'eModel_ids', 'itemEModel', 'ePackages'));
    }
    /**
     */
    public function store(EModelRequest $request) {
        $validatedData = $request->validated();
        $eModel = $this->eModelService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $eModel,
                'modelName' => __('PkgGapp::eModel.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $eModel->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('eModels.edit', ['eModel' => $eModel->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eModel,
                'modelName' => __('PkgGapp::eModel.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('eModel.show_' . $id);

        $itemEModel = $this->eModelService->edit($id);


        $this->viewState->set('scope.eDataField.e_model_id', $id);
        

        $eDataFieldService =  new EDataFieldService();
        $eDataFields_view_data = $eDataFieldService->prepareDataForIndexView();
        extract($eDataFields_view_data);

        $this->viewState->set('scope.eMetadatum.e_model_id', $id);
        

        $eMetadatumService =  new EMetadatumService();
        $eMetadata_view_data = $eMetadatumService->prepareDataForIndexView();
        extract($eMetadata_view_data);

        $this->viewState->set('scope.eRelationship.source_e_model_id', $id);
        

        $eRelationshipService =  new ERelationshipService();
        $eRelationships_view_data = $eRelationshipService->prepareDataForIndexView();
        extract($eRelationships_view_data);

        $this->viewState->set('scope.eRelationship.target_e_model_id', $id);
        

        $eRelationshipService =  new ERelationshipService();
        $eRelationships_view_data = $eRelationshipService->prepareDataForIndexView();
        extract($eRelationships_view_data);

        if (request()->ajax()) {
            return view('PkgGapp::eModel._show', array_merge(compact('itemEModel'),$eDataField_compact_value, $eMetadatum_compact_value, $eRelationship_compact_value, $eRelationship_compact_value));
        }

        return view('PkgGapp::eModel.show', array_merge(compact('itemEModel'),$eDataField_compact_value, $eMetadatum_compact_value, $eRelationship_compact_value, $eRelationship_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('eModel.edit_' . $id);


        $itemEModel = $this->eModelService->edit($id);


        $ePackages = $this->ePackageService->all();


        $this->viewState->set('scope.eDataField.e_model_id', $id);
        

        $eDataFieldService =  new EDataFieldService();
        $eDataFields_view_data = $eDataFieldService->prepareDataForIndexView();
        extract($eDataFields_view_data);

        $this->viewState->set('scope.eMetadatum.e_model_id', $id);
        

        $eMetadatumService =  new EMetadatumService();
        $eMetadata_view_data = $eMetadatumService->prepareDataForIndexView();
        extract($eMetadata_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgGapp::eModel._edit', array_merge(compact('bulkEdit' , 'itemEModel','ePackages'),$eDataField_compact_value, $eMetadatum_compact_value));
        }

        return view('PkgGapp::eModel.edit', array_merge(compact('bulkEdit' ,'itemEModel','ePackages'),$eDataField_compact_value, $eMetadatum_compact_value));


    }
    /**
     */
    public function update(EModelRequest $request, string $id) {

        $validatedData = $request->validated();
        $eModel = $this->eModelService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $eModel->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('eModels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $eModel_ids = $request->input('eModel_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($eModel_ids) || count($eModel_ids) === 0) {
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
            $eModel_ids,
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

        $eModel = $this->eModelService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('eModels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $eModel_ids = $request->input('ids', []);
        if (!is_array($eModel_ids) || count($eModel_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($eModel_ids as $id) {
            $entity = $this->eModelService->find($id);
            $this->eModelService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($eModel_ids) . ' éléments',
            'modelName' => __('PkgGapp::eModel.plural')
        ]));
    }

    public function export($format)
    {
        $eModels_data = $this->eModelService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EModelExport($eModels_data,'csv'), 'eModel_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EModelExport($eModels_data,'xlsx'), 'eModel_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EModelImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eModels.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eModels.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eModel.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEModels()
    {
        $eModels = $this->eModelService->all();
        return response()->json($eModels);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EModel) par ID, en format JSON.
     */
    public function getEModel(Request $request, $id)
    {
        try {
            $eModel = $this->eModelService->find($id);
            return response()->json($eModel);
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
        $updatedEModel = $this->eModelService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedEModel
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
        $eModelRequest = new EModelRequest();
        $fullRules = $eModelRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:e_models,id'];
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