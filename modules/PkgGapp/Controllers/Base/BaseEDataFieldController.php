<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EDataFieldService;
use Modules\PkgGapp\Services\EModelService;
use Modules\PkgGapp\Services\ERelationshipService;
use Modules\PkgGapp\Services\EMetadatumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EDataFieldRequest;
use Modules\PkgGapp\Models\EDataField;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgGapp\App\Exports\EDataFieldExport;
use Modules\PkgGapp\App\Imports\EDataFieldImport;
use Modules\Core\Services\ContextState;

class BaseEDataFieldController extends AdminController
{
    protected $eDataFieldService;
    protected $eModelService;
    protected $eRelationshipService;

    public function __construct(EDataFieldService $eDataFieldService, EModelService $eModelService, ERelationshipService $eRelationshipService) {
        parent::__construct();
        $this->service  =  $eDataFieldService;
        $this->eDataFieldService = $eDataFieldService;
        $this->eModelService = $eModelService;
        $this->eRelationshipService = $eRelationshipService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('eDataField.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('eDataField');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $eDataFields_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'eDataFields_search',
                $this->viewState->get("filter.eDataField.eDataFields_search")
            )],
            $request->except(['eDataFields_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->eDataFieldService->prepareDataForIndexView($eDataFields_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGapp::eDataField._index', $eDataField_compact_value)->render();
            }else{
                return view($eDataField_partialViewName, $eDataField_compact_value)->render();
            }
        }

        return view('PkgGapp::eDataField.index', $eDataField_compact_value);
    }
    /**
     */
    public function create() {


        $itemEDataField = $this->eDataFieldService->createInstance();
        

        $eModels = $this->eModelService->all();
        $eRelationships = $this->eRelationshipService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgGapp::eDataField._fields', compact('bulkEdit' ,'itemEDataField', 'eModels', 'eRelationships'));
        }
        return view('PkgGapp::eDataField.create', compact('bulkEdit' ,'itemEDataField', 'eModels', 'eRelationships'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $eDataField_ids = $request->input('ids', []);

        if (!is_array($eDataField_ids) || count($eDataField_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEDataField = $this->eDataFieldService->find($eDataField_ids[0]);
         
 
        $eModels = $this->eModelService->getAllForSelect($itemEDataField->eModel);
        $eRelationships = $this->eRelationshipService->getAllForSelect($itemEDataField->eRelationship);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEDataField = $this->eDataFieldService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGapp::eDataField._fields', compact('bulkEdit', 'eDataField_ids', 'itemEDataField', 'eModels', 'eRelationships'));
        }
        return view('PkgGapp::eDataField.bulk-edit', compact('bulkEdit', 'eDataField_ids', 'itemEDataField', 'eModels', 'eRelationships'));
    }
    /**
     */
    public function store(EDataFieldRequest $request) {
        $validatedData = $request->validated();
        $eDataField = $this->eDataFieldService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $eDataField,
                'modelName' => __('PkgGapp::eDataField.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $eDataField->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('eDataFields.edit', ['eDataField' => $eDataField->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eDataField,
                'modelName' => __('PkgGapp::eDataField.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('eDataField.show_' . $id);

        $itemEDataField = $this->eDataFieldService->edit($id);


        $this->viewState->set('scope.eMetadatum.e_data_field_id', $id);
        

        $eMetadatumService =  new EMetadatumService();
        $eMetadata_view_data = $eMetadatumService->prepareDataForIndexView();
        extract($eMetadata_view_data);

        if (request()->ajax()) {
            return view('PkgGapp::eDataField._show', array_merge(compact('itemEDataField'),$eMetadatum_compact_value));
        }

        return view('PkgGapp::eDataField.show', array_merge(compact('itemEDataField'),$eMetadatum_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('eDataField.edit_' . $id);


        $itemEDataField = $this->eDataFieldService->edit($id);


        $eModels = $this->eModelService->getAllForSelect($itemEDataField->eModel);
        $eRelationships = $this->eRelationshipService->getAllForSelect($itemEDataField->eRelationship);


        $this->viewState->set('scope.eMetadatum.e_data_field_id', $id);
        

        $eMetadatumService =  new EMetadatumService();
        $eMetadata_view_data = $eMetadatumService->prepareDataForIndexView();
        extract($eMetadata_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgGapp::eDataField._edit', array_merge(compact('bulkEdit' , 'itemEDataField','eModels', 'eRelationships'),$eMetadatum_compact_value));
        }

        return view('PkgGapp::eDataField.edit', array_merge(compact('bulkEdit' ,'itemEDataField','eModels', 'eRelationships'),$eMetadatum_compact_value));


    }
    /**
     */
    public function update(EDataFieldRequest $request, string $id) {

        $validatedData = $request->validated();
        $eDataField = $this->eDataFieldService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $eDataField->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('eDataFields.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');

        // 1) Structure de la requête (ids + champs cochés)
        $request->validate([
            'eDataField_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('eDataField_ids', []);
        $champsCoches = $request->input('fields_modifiables', []);

        // 2) Restreindre aux champs réellement éditables (côté service/UI)
        $updatableFields = $this->service->getFieldsEditable();
        $requestedFields = array_values(array_intersect($champsCoches, $updatableFields));
        if (empty($requestedFields)) {
            return JsonResponseHelper::error("Aucun champ sélectionné valide.");
        }

        // 3) Valeurs “bulk” proposées par l'utilisateur (payload uniforme)
        $valeursChamps = [];
        foreach ($requestedFields as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        // 4) Charger rules/messages du FormRequest sans dépendre de la current request
        $form         = new \Modules\PkgGapp\App\Requests\EDataFieldRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->eDataFieldService->find($id);
            $this->authorize('update', $model);

            // sanitizePayloadByRoles complète les champs non autorisés avec la valeur du modèle
            // et nous retourne la liste des champs "kept" donc effectivement modifiables par cet utilisateur
            [, $kept /* $removed */] = $this->service->sanitizePayloadByRoles(
                $valeursChamps,
                $model,
                $request->user()
            );

            $allowedAcrossAll = array_values(array_intersect($allowedAcrossAll, $kept));
            if (empty($allowedAcrossAll)) {
                break;
            }
        }

        if (empty($allowedAcrossAll)) {
            return JsonResponseHelper::error("Aucun des champs sélectionnés n’est autorisé à être modifié pour les éléments choisis.");
        }

        // 6) Payload & Rules finaux (uniquement champs autorisés pour TOUS les IDs)
        $finalPayload = [];
        foreach ($allowedAcrossAll as $f) {
            $finalPayload[$f] = $valeursChamps[$f] ?? null;
        }

        // Normaliser '' -> null pour les champs "nullable" en se basant sur les valeurs bulk
        foreach ($allowedAcrossAll as $f) {
            $rule = $fullRules[$f] ?? null;
            if (is_string($rule) && str_contains($rule, 'nullable')) {
                if (array_key_exists($f, $valeursChamps) && $valeursChamps[$f] === '') {
                    $finalPayload[$f] = null;
                }
            }
        }

        $finalRules = array_intersect_key($fullRules, array_flip($allowedAcrossAll));

        // 7) Validation finale avec les rules/messages du FormRequest
        \Illuminate\Support\Facades\Validator::make($finalPayload, $finalRules, $fullMessages)->validate();

        // 8) Dispatch du job avec uniquement les champs autorisés
        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob", $this->service->modelName, $this->service->moduleName);

        $ignored = array_values(array_diff($requestedFields, $allowedAcrossAll));

        dispatch(new BulkEditJob(
            Auth::id(),
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $ids,
            $allowedAcrossAll,
            $finalPayload
        ));

        $msg = 'Mise à jour en masse effectuée avec succès.';
        if (!empty($ignored)) {
            $msg .= ' Champs ignorés (non autorisés) : ' . implode(', ', $ignored) . '.';
        }

        return JsonResponseHelper::success($msg, [
            'traitement_token' => $jobManager->getToken()
        ]);
    
    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $eDataField = $this->eDataFieldService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('eDataFields.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $eDataField_ids = $request->input('ids', []);
        if (!is_array($eDataField_ids) || count($eDataField_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($eDataField_ids as $id) {
            $entity = $this->eDataFieldService->find($id);
            $this->eDataFieldService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($eDataField_ids) . ' éléments',
            'modelName' => __('PkgGapp::eDataField.plural')
        ]));
    }

    public function export($format)
    {
        $eDataFields_data = $this->eDataFieldService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EDataFieldExport($eDataFields_data,'csv'), 'eDataField_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EDataFieldExport($eDataFields_data,'xlsx'), 'eDataField_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EDataFieldImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eDataFields.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eDataFields.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eDataField.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEDataFields()
    {
        $eDataFields = $this->eDataFieldService->all();
        return response()->json($eDataFields);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EDataField) par ID, en format JSON.
     */
    public function getEDataField(Request $request, $id)
    {
        try {
            $eDataField = $this->eDataFieldService->find($id);
            return response()->json($eDataField);
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
        $updatedEDataField = $this->eDataFieldService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEDataField],
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
        $eDataFieldRequest = new EDataFieldRequest();
        $fullRules = $eDataFieldRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:e_data_fields,id'];
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
        $itemEDataField = EDataField::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEDataField, $field);
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
        $itemEDataField = EDataField::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEDataField);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEDataField, $changes);

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