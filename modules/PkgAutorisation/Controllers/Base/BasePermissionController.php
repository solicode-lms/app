<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers\Base;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\Core\Services\FeatureService;
use Modules\Core\Services\SysControllerService;
use Modules\PkgAutorisation\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutorisation\App\Requests\PermissionRequest;
use Modules\PkgAutorisation\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgAutorisation\App\Exports\PermissionExport;
use Modules\PkgAutorisation\App\Imports\PermissionImport;
use Modules\Core\Services\ContextState;

class BasePermissionController extends AdminController
{
    protected $permissionService;
    protected $featureService;
    protected $sysControllerService;
    protected $roleService;

    public function __construct(PermissionService $permissionService, FeatureService $featureService, SysControllerService $sysControllerService, RoleService $roleService) {
        parent::__construct();
        $this->service  =  $permissionService;
        $this->permissionService = $permissionService;
        $this->featureService = $featureService;
        $this->sysControllerService = $sysControllerService;
        $this->roleService = $roleService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('permission.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('permission');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $permissions_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'permissions_search',
                $this->viewState->get("filter.permission.permissions_search")
            )],
            $request->except(['permissions_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->permissionService->prepareDataForIndexView($permissions_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutorisation::permission._index', $permission_compact_value)->render();
            }else{
                return view($permission_partialViewName, $permission_compact_value)->render();
            }
        }

        return view('PkgAutorisation::permission.index', $permission_compact_value);
    }
    /**
     */
    public function create() {


        $itemPermission = $this->permissionService->createInstance();
        

        $sysControllers = $this->sysControllerService->all();
        $features = $this->featureService->all();
        $roles = $this->roleService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgAutorisation::permission._fields', compact('bulkEdit' ,'itemPermission', 'features', 'roles', 'sysControllers'));
        }
        return view('PkgAutorisation::permission.create', compact('bulkEdit' ,'itemPermission', 'features', 'roles', 'sysControllers'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $permission_ids = $request->input('ids', []);

        if (!is_array($permission_ids) || count($permission_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemPermission = $this->permissionService->find($permission_ids[0]);
         
 
        $sysControllers = $this->sysControllerService->getAllForSelect($itemPermission->controller);
        $features = $this->featureService->getAllForSelect($itemPermission->features);
        $roles = $this->roleService->getAllForSelect($itemPermission->roles);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemPermission = $this->permissionService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgAutorisation::permission._fields', compact('bulkEdit', 'permission_ids', 'itemPermission', 'features', 'roles', 'sysControllers'));
        }
        return view('PkgAutorisation::permission.bulk-edit', compact('bulkEdit', 'permission_ids', 'itemPermission', 'features', 'roles', 'sysControllers'));
    }
    /**
     */
    public function store(PermissionRequest $request) {
        $validatedData = $request->validated();
        $permission = $this->permissionService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $permission,
                'modelName' => __('PkgAutorisation::permission.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $permission->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('permissions.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $permission,
                'modelName' => __('PkgAutorisation::permission.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('permission.show_' . $id);

        $itemPermission = $this->permissionService->edit($id);


        if (request()->ajax()) {
            return view('PkgAutorisation::permission._show', array_merge(compact('itemPermission'),));
        }

        return view('PkgAutorisation::permission.show', array_merge(compact('itemPermission'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('permission.edit_' . $id);


        $itemPermission = $this->permissionService->edit($id);


        $sysControllers = $this->sysControllerService->getAllForSelect($itemPermission->controller);
        $features = $this->featureService->getAllForSelect($itemPermission->features);
        $roles = $this->roleService->getAllForSelect($itemPermission->roles);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgAutorisation::permission._fields', array_merge(compact('bulkEdit' , 'itemPermission','features', 'roles', 'sysControllers'),));
        }

        return view('PkgAutorisation::permission.edit', array_merge(compact('bulkEdit' ,'itemPermission','features', 'roles', 'sysControllers'),));


    }
    /**
     */
    public function update(PermissionRequest $request, string $id) {

        $validatedData = $request->validated();
        $permission = $this->permissionService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $permission->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('permissions.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')
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
            'permission_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('permission_ids', []);
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
        $form         = new \Modules\PkgAutorisation\App\Requests\PermissionRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->permissionService->find($id);
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

        $permission = $this->permissionService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('permissions.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $permission_ids = $request->input('ids', []);
        if (!is_array($permission_ids) || count($permission_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($permission_ids as $id) {
            $entity = $this->permissionService->find($id);
            $this->permissionService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($permission_ids) . ' éléments',
            'modelName' => __('PkgAutorisation::permission.plural')
        ]));
    }

    public function export($format)
    {
        $permissions_data = $this->permissionService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new PermissionExport($permissions_data,'csv'), 'permission_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new PermissionExport($permissions_data,'xlsx'), 'permission_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new PermissionImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('permissions.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('permissions.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutorisation::permission.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getPermissions()
    {
        $permissions = $this->permissionService->all();
        return response()->json($permissions);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Permission) par ID, en format JSON.
     */
    public function getPermission(Request $request, $id)
    {
        try {
            $permission = $this->permissionService->find($id);
            return response()->json($permission);
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
        $updatedPermission = $this->permissionService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedPermission],
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
        $permissionRequest = new PermissionRequest();
        $fullRules = $permissionRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:permissions,id'];
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
        $itemPermission = Permission::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemPermission, $field);
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
        $itemPermission = Permission::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemPermission);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemPermission, $changes);

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