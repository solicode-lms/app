<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\App\Requests\RoleRequest;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\PkgAutorisation\Services\UserService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutorisation\App\Exports\RoleExport;
use Modules\PkgAutorisation\App\Imports\RoleImport;
use Modules\Core\Services\ContextState;

class BaseRoleController extends AdminController
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


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $role_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $roles_data = $this->roleService->paginate($role_searchQuery);

        if ($request->ajax()) {
            return view('PkgAutorisation::role._table', compact('roles_data'))->render();
        }

        return view('PkgAutorisation::role.index', compact('roles_data','role_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemRole = $this->roleService->createInstance();
        $permissions = $this->permissionService->all();
        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', compact('itemRole', 'permissions', 'users'));
        }
        return view('PkgAutorisation::role.create', compact('itemRole', 'permissions', 'users'));
    }

    /**
     * Stocke une nouvelle filière.
     */
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


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $role,
                'modelName' => __('PkgAutorisation::role.singular')])
            ]);
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
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemRole = $this->roleService->find($id);
        $permissions = $this->permissionService->all();
        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', compact('itemRole', 'permissions', 'users'));
        }

        return view('PkgAutorisation::role.show', compact('itemRole'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemRole = $this->roleService->find($id);
        $permissions = $this->permissionService->all();
        $users = $this->userService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('role_id', $id);


        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', compact('itemRole', 'permissions', 'users'));
        }

        return view('PkgAutorisation::role.edit', compact('itemRole', 'permissions', 'users'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(RoleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $role = $this->roleService->update($id, $validatedData);


        $role->permissions()->sync($request->input('permissions'));
        $role->users()->sync($request->input('users'));


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $role,
                'modelName' =>  __('PkgAutorisation::role.singular')])
            ]);
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
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $role = $this->roleService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $role,
                'modelName' =>  __('PkgAutorisation::role.singular')])
            ]);
        }

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
        $roles_data = $this->roleService->all();
        return Excel::download(new RoleExport($roles_data), 'role_export.xlsx');
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