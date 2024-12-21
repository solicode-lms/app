<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\App\Requests\PermissionRequest;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\PkgAutorisation\Services\RoleService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutorisation\App\Exports\PermissionExport;
use Modules\PkgAutorisation\App\Imports\PermissionImport;

class PermissionController extends AdminController
{
    protected $permissionService;
    protected $permissionService;
    protected $permissionService;
    protected $roleService;

    public function __construct(PermissionService $permissionService, PermissionService $permissionService, PermissionService $permissionService, RoleService $roleService)
    {
        parent::__construct();
        $this->permissionService = $permissionService;
        $this->permissionService = $permissionService;
        $this->permissionService = $permissionService;
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->permissionService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgAutorisation::permission._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgAutorisation::permission.index', compact('data'));
    }

    public function create()
    {
        $item = $this->permissionService->createInstance();
        $permissions = $this->permissionService->all();
        $permissions = $this->permissionService->all();
        $roles = $this->roleService->all();
        return view('PkgAutorisation::permission.create', compact('item', 'permissions', 'permissions', 'roles'));
    }

    public function store(PermissionRequest $request)
    {
        $validatedData = $request->validated();
        $permission = $this->permissionService->create($validatedData);

        if ($request->has('permissions')) {
            $permission->permissions()->sync($request->input('permissions'));
        }
        if ($request->has('permissions')) {
            $permission->permissions()->sync($request->input('permissions'));
        }
        if ($request->has('roles')) {
            $permission->roles()->sync($request->input('roles'));
        }

        return redirect()->route('permissions.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $permission,
            'modelName' => __('PkgAutorisation::permission.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->permissionService->find($id);
        return view('PkgAutorisation::permission.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->permissionService->find($id);
        $permissions = $this->permissionService->all();
        $permissions = $this->permissionService->all();
        $roles = $this->roleService->all();
        return view('PkgAutorisation::permission.edit', compact('item', 'permissions', 'permissions', 'roles'));
    }

    public function update(PermissionRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $permission = $this->permissionService->update($id, $validatedData);


        if ($request->has('permissions')) {
            $permission->permissions()->sync($request->input('permissions'));
        }
        if ($request->has('permissions')) {
            $permission->permissions()->sync($request->input('permissions'));
        }
        if ($request->has('roles')) {
            $permission->roles()->sync($request->input('roles'));
        }

        return redirect()->route('permissions.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $permission = $this->permissionService->destroy($id);
        return redirect()->route('permissions.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->permissionService->all();
        return Excel::download(new PermissionExport($data), 'permission_export.xlsx');
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
}
