<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\App\Requests\RoleRequest;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutorisation\App\Exports\RoleExport;
use Modules\PkgAutorisation\App\Imports\RoleImport;

class RoleController extends AdminController
{
    protected $roleService;
    protected $permissionService;
    protected $userService;

    public function __construct(RoleService $roleService, PermissionService $permissionService, UserService $userService)
    {
        parent::__construct();
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
        $this->userService = $userService;
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
        $permissions = $this->permissionService->all();
        $users = $this->userService->all();
        return view('PkgAutorisation::role.create', compact('item', 'permissions', 'users'));
    }

    public function store(RoleRequest $request)
    {
        $validatedData = $request->validated();
        $role = $this->roleService->create($validatedData);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions'));
        }
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
        $permissions = $this->permissionService->all();
        $users = $this->userService->all();
        return view('PkgAutorisation::role.edit', compact('item', 'permissions', 'users'));
    }

    public function update(RoleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $role = $this->roleService->update($id, $validatedData);


        if ($request->has('permissions')) {
            $role->permissions()->sync($request->input('permissions'));
        }
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
