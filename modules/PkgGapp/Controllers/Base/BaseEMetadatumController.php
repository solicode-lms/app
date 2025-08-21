<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EMetadatumService;
use Modules\PkgGapp\Services\EDataFieldService;
use Modules\PkgGapp\Services\EMetadataDefinitionService;
use Modules\PkgGapp\Services\EModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EMetadatumRequest;
use Modules\PkgGapp\Models\EMetadatum;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgGapp\App\Exports\EMetadatumExport;
use Modules\PkgGapp\App\Imports\EMetadatumImport;
use Modules\Core\Services\ContextState;

class BaseEMetadatumController extends AdminController
{
    protected $eMetadatumService;
    protected $eDataFieldService;
    protected $eMetadataDefinitionService;
    protected $eModelService;

    public function __construct(EMetadatumService $eMetadatumService, EDataFieldService $eDataFieldService, EMetadataDefinitionService $eMetadataDefinitionService, EModelService $eModelService) {
        parent::__construct();
        $this->service  =  $eMetadatumService;
        $this->eMetadatumService = $eMetadatumService;
        $this->eDataFieldService = $eDataFieldService;
        $this->eMetadataDefinitionService = $eMetadataDefinitionService;
        $this->eModelService = $eModelService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('eMetadatum.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('eMetadatum');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $eMetadata_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'eMetadata_search',
                $this->viewState->get("filter.eMetadatum.eMetadata_search")
            )],
            $request->except(['eMetadata_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->eMetadatumService->prepareDataForIndexView($eMetadata_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGapp::eMetadatum._index', $eMetadatum_compact_value)->render();
            }else{
                return view($eMetadatum_partialViewName, $eMetadatum_compact_value)->render();
            }
        }

        return view('PkgGapp::eMetadatum.index', $eMetadatum_compact_value);
    }
    /**
     */
    public function create() {


        $itemEMetadatum = $this->eMetadatumService->createInstance();
        

        $eModels = $this->eModelService->all();
        $eDataFields = $this->eDataFieldService->all();
        $eMetadataDefinitions = $this->eMetadataDefinitionService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._fields', compact('bulkEdit' ,'itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
        }
        return view('PkgGapp::eMetadatum.create', compact('bulkEdit' ,'itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $eMetadatum_ids = $request->input('ids', []);

        if (!is_array($eMetadatum_ids) || count($eMetadatum_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEMetadatum = $this->eMetadatumService->find($eMetadatum_ids[0]);
         
 
        $eModels = $this->eModelService->getAllForSelect($itemEMetadatum->eModel);
        $eDataFields = $this->eDataFieldService->getAllForSelect($itemEMetadatum->eDataField);
        $eMetadataDefinitions = $this->eMetadataDefinitionService->getAllForSelect($itemEMetadatum->eMetadataDefinition);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEMetadatum = $this->eMetadatumService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._fields', compact('bulkEdit', 'eMetadatum_ids', 'itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
        }
        return view('PkgGapp::eMetadatum.bulk-edit', compact('bulkEdit', 'eMetadatum_ids', 'itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
    }
    /**
     */
    public function store(EMetadatumRequest $request) {
        $validatedData = $request->validated();
        $eMetadatum = $this->eMetadatumService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' => __('PkgGapp::eMetadatum.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $eMetadatum->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('eMetadata.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' => __('PkgGapp::eMetadatum.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('eMetadatum.show_' . $id);

        $itemEMetadatum = $this->eMetadatumService->edit($id);


        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._show', array_merge(compact('itemEMetadatum'),));
        }

        return view('PkgGapp::eMetadatum.show', array_merge(compact('itemEMetadatum'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('eMetadatum.edit_' . $id);


        $itemEMetadatum = $this->eMetadatumService->edit($id);


        $eModels = $this->eModelService->getAllForSelect($itemEMetadatum->eModel);
        $eDataFields = $this->eDataFieldService->getAllForSelect($itemEMetadatum->eDataField);
        $eMetadataDefinitions = $this->eMetadataDefinitionService->getAllForSelect($itemEMetadatum->eMetadataDefinition);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._fields', array_merge(compact('bulkEdit' , 'itemEMetadatum','eDataFields', 'eMetadataDefinitions', 'eModels'),));
        }

        return view('PkgGapp::eMetadatum.edit', array_merge(compact('bulkEdit' ,'itemEMetadatum','eDataFields', 'eMetadataDefinitions', 'eModels'),));


    }
    /**
     */
    public function update(EMetadatumRequest $request, string $id) {

        $validatedData = $request->validated();
        $eMetadatum = $this->eMetadatumService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $eMetadatum->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('eMetadata.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $eMetadatum_ids = $request->input('eMetadatum_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($eMetadatum_ids) || count($eMetadatum_ids) === 0) {
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
            $eMetadatum_ids,
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

        $eMetadatum = $this->eMetadatumService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('eMetadata.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $eMetadatum_ids = $request->input('ids', []);
        if (!is_array($eMetadatum_ids) || count($eMetadatum_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($eMetadatum_ids as $id) {
            $entity = $this->eMetadatumService->find($id);
            $this->eMetadatumService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($eMetadatum_ids) . ' éléments',
            'modelName' => __('PkgGapp::eMetadatum.plural')
        ]));
    }

    public function export($format)
    {
        $eMetadata_data = $this->eMetadatumService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EMetadatumExport($eMetadata_data,'csv'), 'eMetadatum_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EMetadatumExport($eMetadata_data,'xlsx'), 'eMetadatum_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EMetadatumImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eMetadata.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eMetadata.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eMetadatum.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEMetadata()
    {
        $eMetadata = $this->eMetadatumService->all();
        return response()->json($eMetadata);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EMetadatum) par ID, en format JSON.
     */
    public function getEMetadatum(Request $request, $id)
    {
        try {
            $eMetadatum = $this->eMetadatumService->find($id);
            return response()->json($eMetadatum);
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
        $updatedEMetadatum = $this->eMetadatumService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEMetadatum],
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
        $eMetadatumRequest = new EMetadatumRequest();
        $fullRules = $eMetadatumRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:e_metadata,id'];
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

    /**
     * Retourne les métadonnées d’un champ (type, options, validation, etag…)
     *  @DynamicPermissionIgnore
     */
    public function fieldMeta(int $id, string $field)
    {
        // $this->authorizeAction('update');
        $itemEMetadatum = EMetadatum::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEMetadatum, $field);
        return response()->json(
            $data
        );
    }

    /**
     * PATCH inline d’une cellule avec gestion de l’ETag
     * @DynamicPermissionIgnore
     */
    public function patchInline(Request $request, int $id)
    {

        $this->authorizeAction('update');
        $itemEMetadatum = EMetadatum::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEMetadatum);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEMetadatum, $changes);

        return response()->json(
            array_merge(
                [
                    "ok"        => true,
                    "entity_id" => $updated->id,
                    "display"   => $this->service->formatDisplayValues($updated, array_keys($changes)),
                    "etag"      => $this->service->etag($updated),
                ],
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            )
        );
    }

   
}