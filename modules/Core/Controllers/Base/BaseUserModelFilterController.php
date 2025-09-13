<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\UserModelFilterService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\App\Requests\UserModelFilterRequest;
use Modules\Core\Models\UserModelFilter;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\App\Exports\UserModelFilterExport;
use Modules\Core\App\Imports\UserModelFilterImport;
use Modules\Core\Services\ContextState;

class BaseUserModelFilterController extends AdminController
{
    protected $userModelFilterService;
    protected $userService;

    public function __construct(UserModelFilterService $userModelFilterService, UserService $userService) {
        parent::__construct();
        $this->service  =  $userModelFilterService;
        $this->userModelFilterService = $userModelFilterService;
        $this->userService = $userService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('userModelFilter.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('userModelFilter');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $userModelFilters_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'userModelFilters_search',
                $this->viewState->get("filter.userModelFilter.userModelFilters_search")
            )],
            $request->except(['userModelFilters_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->userModelFilterService->prepareDataForIndexView($userModelFilters_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('Core::userModelFilter._index', $userModelFilter_compact_value)->render();
            }else{
                return view($userModelFilter_partialViewName, $userModelFilter_compact_value)->render();
            }
        }

        return view('Core::userModelFilter.index', $userModelFilter_compact_value);
    }
    /**
     */
    public function create() {


        $itemUserModelFilter = $this->userModelFilterService->createInstance();
        

        $users = $this->userService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('Core::userModelFilter._fields', compact('bulkEdit' ,'itemUserModelFilter', 'users'));
        }
        return view('Core::userModelFilter.create', compact('bulkEdit' ,'itemUserModelFilter', 'users'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $userModelFilter_ids = $request->input('ids', []);

        if (!is_array($userModelFilter_ids) || count($userModelFilter_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemUserModelFilter = $this->userModelFilterService->find($userModelFilter_ids[0]);
         
 
        $users = $this->userService->getAllForSelect($itemUserModelFilter->user);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemUserModelFilter = $this->userModelFilterService->createInstance();
        
        if (request()->ajax()) {
            return view('Core::userModelFilter._fields', compact('bulkEdit', 'userModelFilter_ids', 'itemUserModelFilter', 'users'));
        }
        return view('Core::userModelFilter.bulk-edit', compact('bulkEdit', 'userModelFilter_ids', 'itemUserModelFilter', 'users'));
    }
    /**
     */
    public function store(UserModelFilterRequest $request) {
        $validatedData = $request->validated();
        $userModelFilter = $this->userModelFilterService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $userModelFilter,
                'modelName' => __('Core::userModelFilter.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $userModelFilter->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('userModelFilters.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $userModelFilter,
                'modelName' => __('Core::userModelFilter.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('userModelFilter.show_' . $id);

        $itemUserModelFilter = $this->userModelFilterService->edit($id);


        if (request()->ajax()) {
            return view('Core::userModelFilter._show', array_merge(compact('itemUserModelFilter'),));
        }

        return view('Core::userModelFilter.show', array_merge(compact('itemUserModelFilter'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('userModelFilter.edit_' . $id);


        $itemUserModelFilter = $this->userModelFilterService->edit($id);


        $users = $this->userService->getAllForSelect($itemUserModelFilter->user);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('Core::userModelFilter._fields', array_merge(compact('bulkEdit' , 'itemUserModelFilter','users'),));
        }

        return view('Core::userModelFilter.edit', array_merge(compact('bulkEdit' ,'itemUserModelFilter','users'),));


    }
    /**
     */
    public function update(UserModelFilterRequest $request, string $id) {

        $validatedData = $request->validated();
        $userModelFilter = $this->userModelFilterService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $userModelFilter,
                'modelName' =>  __('Core::userModelFilter.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $userModelFilter->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('userModelFilters.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $userModelFilter,
                'modelName' =>  __('Core::userModelFilter.singular')
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
            'userModelFilter_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('userModelFilter_ids', []);
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
        $form         = new \Modules\Core\App\Requests\UserModelFilterRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->userModelFilterService->find($id);
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

        $userModelFilter = $this->userModelFilterService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $userModelFilter,
                'modelName' =>  __('Core::userModelFilter.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('userModelFilters.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $userModelFilter,
                'modelName' =>  __('Core::userModelFilter.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $userModelFilter_ids = $request->input('ids', []);
        if (!is_array($userModelFilter_ids) || count($userModelFilter_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($userModelFilter_ids as $id) {
            $entity = $this->userModelFilterService->find($id);
            $this->userModelFilterService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($userModelFilter_ids) . ' éléments',
            'modelName' => __('Core::userModelFilter.plural')
        ]));
    }

    public function export($format)
    {
        $userModelFilters_data = $this->userModelFilterService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new UserModelFilterExport($userModelFilters_data,'csv'), 'userModelFilter_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new UserModelFilterExport($userModelFilters_data,'xlsx'), 'userModelFilter_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new UserModelFilterImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('userModelFilters.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('userModelFilters.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::userModelFilter.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getUserModelFilters()
    {
        $userModelFilters = $this->userModelFilterService->all();
        return response()->json($userModelFilters);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (UserModelFilter) par ID, en format JSON.
     */
    public function getUserModelFilter(Request $request, $id)
    {
        try {
            $userModelFilter = $this->userModelFilterService->find($id);
            return response()->json($userModelFilter);
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
        $updatedUserModelFilter = $this->userModelFilterService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedUserModelFilter],
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
        $userModelFilterRequest = new UserModelFilterRequest();
        $fullRules = $userModelFilterRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:user_model_filters,id'];
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
        $itemUserModelFilter = UserModelFilter::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemUserModelFilter, $field);
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
        $itemUserModelFilter = UserModelFilter::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemUserModelFilter);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemUserModelFilter, $changes);

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