<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers\Base;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\PkgWidgets\Services\WidgetService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutorisation\App\Requests\RoleRequest;
use Modules\PkgAutorisation\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutorisation\App\Exports\RoleExport;
use Modules\PkgAutorisation\App\Imports\RoleImport;
use Modules\Core\Services\ContextState;

class BaseRoleController extends AdminController
{
    protected $roleService;
    protected $permissionService;
    protected $widgetService;
    protected $userService;

    public function __construct(RoleService $roleService, PermissionService $permissionService, WidgetService $widgetService, UserService $userService) {
        parent::__construct();
        $this->service  =  $roleService;
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
        $this->widgetService = $widgetService;
        $this->userService = $userService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('role.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('role');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $roles_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'roles_search',
                $this->viewState->get("filter.role.roles_search")
            )],
            $request->except(['roles_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->roleService->prepareDataForIndexView($roles_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutorisation::role._index', $role_compact_value)->render();
            }else{
                return view($role_partialViewName, $role_compact_value)->render();
            }
        }

        return view('PkgAutorisation::role.index', $role_compact_value);
    }
    /**
     */
    public function create() {


        $itemRole = $this->roleService->createInstance();
        

        $permissions = $this->permissionService->all();
        $widgets = $this->widgetService->all();
        $users = $this->userService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', compact('itemRole', 'permissions', 'widgets', 'users'));
        }
        return view('PkgAutorisation::role.create', compact('itemRole', 'permissions', 'widgets', 'users'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $role_ids = $request->input('ids', []);

        if (!is_array($role_ids) || count($role_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRole = $this->roleService->find($role_ids[0]);
         
 
        $permissions = $this->permissionService->all();
        $widgets = $this->widgetService->all();
        $users = $this->userService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRole = $this->roleService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', compact('bulkEdit', 'role_ids', 'itemRole', 'permissions', 'widgets', 'users'));
        }
        return view('PkgAutorisation::role.bulk-edit', compact('bulkEdit', 'role_ids', 'itemRole', 'permissions', 'widgets', 'users'));
    }
    /**
     */
    public function store(RoleRequest $request) {
        $validatedData = $request->validated();
        $role = $this->roleService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $role,
                'modelName' => __('PkgAutorisation::role.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $role->id]
            );
        }

        return redirect()->route('roles.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $role,
                'modelName' => __('PkgAutorisation::role.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('role.edit_' . $id);


        $itemRole = $this->roleService->edit($id);


        $permissions = $this->permissionService->all();
        $widgets = $this->widgetService->all();
        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', array_merge(compact('itemRole','permissions', 'widgets', 'users'),));
        }

        return view('PkgAutorisation::role.edit', array_merge(compact('itemRole','permissions', 'widgets', 'users'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('role.edit_' . $id);


        $itemRole = $this->roleService->edit($id);


        $permissions = $this->permissionService->all();
        $widgets = $this->widgetService->all();
        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', array_merge(compact('itemRole','permissions', 'widgets', 'users'),));
        }

        return view('PkgAutorisation::role.edit', array_merge(compact('itemRole','permissions', 'widgets', 'users'),));


    }
    /**
     */
    public function update(RoleRequest $request, string $id) {

        $validatedData = $request->validated();
        $role = $this->roleService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $role,
                'modelName' =>  __('PkgAutorisation::role.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $role->id]
            );
        }

        return redirect()->route('roles.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $role,
                'modelName' =>  __('PkgAutorisation::role.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $role_ids = $request->input('role_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($role_ids) || count($role_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($role_ids as $id) {
            $entity = $this->roleService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->roleService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->roleService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $role = $this->roleService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $role,
                'modelName' =>  __('PkgAutorisation::role.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('roles.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $role,
                'modelName' =>  __('PkgAutorisation::role.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $role_ids = $request->input('ids', []);
        if (!is_array($role_ids) || count($role_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($role_ids as $id) {
            $entity = $this->roleService->find($id);
            $this->roleService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($role_ids) . ' éléments',
            'modelName' => __('PkgAutorisation::role.plural')
        ]));
    }

    public function export($format)
    {
        $roles_data = $this->roleService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RoleExport($roles_data,'csv'), 'role_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RoleExport($roles_data,'xlsx'), 'role_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RoleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('roles.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('roles.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutorisation::role.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRoles()
    {
        $roles = $this->roleService->all();
        return response()->json($roles);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $role = $this->roleService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRole = $this->roleService->dataCalcul($role);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRole
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
        $roleRequest = new RoleRequest();
        $fullRules = $roleRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:roles,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.', 422);
        }
    
        $this->getService()->update($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}