<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers\Base;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
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
    protected $userService;

    public function __construct(RoleService $roleService, PermissionService $permissionService, UserService $userService) {
        parent::__construct();
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
        $this->userService = $userService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('role.index');

        // Extraire les paramètres de recherche, page, et filtres
        $roles_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('roles_search', $this->viewState->get("filter.role.roles_search"))],
            $request->except(['roles_search', 'page', 'sort'])
        );

        // Paginer les roles
        $roles_data = $this->roleService->paginate($roles_params);

        // Récupérer les statistiques et les champs filtrables
        $roles_stats = $this->roleService->getroleStats();
        $roles_filters = $this->roleService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgAutorisation::role._table', compact('roles_data', 'roles_stats', 'roles_filters'))->render();
        }

        return view('PkgAutorisation::role.index', compact('roles_data', 'roles_stats', 'roles_filters'));
    }
    public function create() {
        $itemRole = $this->roleService->createInstance();
        $permissions = $this->permissionService->all();
        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', compact('itemRole', 'permissions', 'users'));
        }
        return view('PkgAutorisation::role.create', compact('itemRole', 'permissions', 'users'));
    }
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
    public function show(string $id) {
        return $this->edit( $id);
    }
    public function edit(string $id) {

        $this->viewState->setContextKey('role.edit_' . $id);

        $itemRole = $this->roleService->find($id);
        $permissions = $this->permissionService->all();
        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', compact('itemRole', 'permissions', 'users'));
        }

        return view('PkgAutorisation::role.edit', compact('itemRole', 'permissions', 'users'));

    }
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
    


}
