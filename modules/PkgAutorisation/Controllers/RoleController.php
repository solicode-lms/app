<?php


namespace Modules\PkgAutorisation\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\App\Requests\RoleRequest;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\Services\FeatureService;
use Modules\PkgAutorisation\App\Exports\RoleExport;
use Modules\PkgAutorisation\App\Imports\RoleImport;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;

class RoleController extends AdminController
{
    protected $roleService;
    protected $permissionService;
    protected $userService;
    protected $featureService;

    public function __construct(RoleService $roleService,FeatureService $featureService,  PermissionService $permissionService, UserService $userService)
    {
        parent::__construct();
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
        $this->userService = $userService;
        $this->featureService = $featureService;
    }



    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->roleService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgAutorisation::role._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgAutorisation::role.index', compact('data'));
    }

    public function create()
    {
        $item = $this->roleService->createInstance();
        $users = $this->userService->all();
        $features = $this->featureService->all();
        return view('PkgAutorisation::role.create', compact('item', 'features', 'users'));
    }

    public function store(RoleRequest $request)
    {

        $role = $this->roleService->create($request->validated());

     
        // Add features and Permissions
        $selectedFeatureIds = $request->input('features', []);
        $permissionsToAdd = Permission::whereIn('id', function ($query) use ($selectedFeatureIds) {
            $query->select('permission_id')
                    ->from('feature_permission')
                    ->whereIn('feature_id', $selectedFeatureIds);
        })->get();
        $role->givePermissionTo($permissionsToAdd);
    
        if ($request->has('users')) {
            $role->users()->sync($request->input('users'));
        }

        return redirect()->route('roles.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $role,
            'modelName' => __('PkgAutorisation::role.singular')
        ]));
    }

    public function show(string $id)
    {
        $item = $this->roleService->find($id);
        return view('PkgAutorisation::role.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->roleService->find($id);
        $features = $this->featureService->all();
        $users = $this->userService->all();
        return view('PkgAutorisation::role.edit', compact('item', 'features', 'users'));
    }

    public function update(RoleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $role = $this->roleService->update($id, $validatedData);


        // Edition des permission par fonctionnalité

        $request->validate([
            'features' => 'array',
            'features.*' => 'exists:features,id',
        ]);
    
       
            $selectedFeatureIds = $request->input('features', []);
    
            $newPermissions = Permission::whereIn('id', function ($query) use ($selectedFeatureIds) {
                $query->select('permission_id')
                      ->from('feature_permission')
                      ->whereIn('feature_id', $selectedFeatureIds);
            })->get();
    
            $currentPermissions = $role->permissions;
    
            $permissionsToRemove = $currentPermissions->diff($newPermissions);
            $role->revokePermissionTo($permissionsToRemove);
    
            $permissionsToAdd = $newPermissions->diff($currentPermissions);
            $role->givePermissionTo($permissionsToAdd);
    
    

        if ($request->has('users')) {
            $role->users()->sync($request->input('users'));
        }

        return redirect()->route('roles.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $role,
                'modelName' =>  __('PkgAutorisation::role.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $role = $this->roleService->destroy($id);
        return redirect()->route('roles.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $role,
                'modelName' =>  __('PkgAutorisation::role.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->roleService->all();
        return Excel::download(new RoleExport($data), 'role_export.xlsx');
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
}
