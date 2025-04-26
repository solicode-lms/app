<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EModelService;
use Modules\PkgGapp\Services\EPackageService;
use Modules\PkgGapp\Services\EDataFieldService;
use Modules\PkgGapp\Services\EMetadatumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EModelRequest;
use Modules\PkgGapp\Models\EModel;
use Maatwebsite\Excel\Facades\Excel;
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

        if (request()->ajax()) {
            return view('PkgGapp::eModel._fields', compact('itemEModel', 'ePackages'));
        }
        return view('PkgGapp::eModel.create', compact('itemEModel', 'ePackages'));
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
             ['entity_id' => $eModel->id]
            );
        }

        return redirect()->route('eModels.edit',['eModel' => $eModel->id])->with(
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

        if (request()->ajax()) {
            return view('PkgGapp::eModel._edit', array_merge(compact('itemEModel','ePackages'),$eDataField_compact_value, $eMetadatum_compact_value));
        }

        return view('PkgGapp::eModel.edit', array_merge(compact('itemEModel','ePackages'),$eDataField_compact_value, $eMetadatum_compact_value));

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

        if (request()->ajax()) {
            return view('PkgGapp::eModel._edit', array_merge(compact('itemEModel','ePackages'),$eDataField_compact_value, $eMetadatum_compact_value));
        }

        return view('PkgGapp::eModel.edit', array_merge(compact('itemEModel','ePackages'),$eDataField_compact_value, $eMetadatum_compact_value));


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
                ['entity_id' => $eModel->id]
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
    
        foreach ($eModel_ids as $id) {
            $entity = $this->eModelService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->eModelService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->eModelService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $eModel = $this->eModelService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEModel = $this->eModelService->dataCalcul($eModel);
    
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
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}