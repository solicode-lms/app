<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\App\Requests\PermissionRequest;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\Core\Services\FeatureService;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\Core\Services\SysControllerService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutorisation\App\Exports\PermissionExport;
use Modules\PkgAutorisation\App\Imports\PermissionImport;

class PermissionController extends AdminController
{
    protected $permissionService;
    protected $featureService;
    protected $roleService;
    protected $sysControllerService;

    public function __construct(PermissionService $permissionService, FeatureService $featureService, RoleService $roleService, SysControllerService $sysControllerService)
    {
        parent::__construct();
        $this->permissionService = $permissionService;
        $this->featureService = $featureService;
        $this->roleService = $roleService;
        $this->sysControllerService = $sysControllerService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->permissionService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgAutorisation::permission._table', compact('data'))->render();
        }

        return view('PkgAutorisation::permission.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemPermission = $this->permissionService->createInstance();
        $features = $this->featureService->all();
        $roles = $this->roleService->all();
        $sysControllers = $this->sysControllerService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::permission._fields', compact('itemPermission', 'features', 'roles', 'sysControllers'));
        }
        return view('PkgAutorisation::permission.create', compact('itemPermission', 'features', 'roles', 'sysControllers'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(PermissionRequest $request)
    {
        $validatedData = $request->validated();
        $permission = $this->permissionService->create($validatedData);

        if ($request->has('features')) {
            $permission->features()->sync($request->input('features'));
        }
        if ($request->has('roles')) {
            $permission->roles()->sync($request->input('roles'));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $permission,
                'modelName' => __('PkgAutorisation::permission.singular')])
            ]);
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
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemPermission = $this->permissionService->find($id);
        $features = $this->featureService->all();
        $roles = $this->roleService->all();
        $sysControllers = $this->sysControllerService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::permission._fields', compact('itemPermission', 'features', 'roles', 'sysControllers'));
        }

        return view('PkgAutorisation::permission.show', compact('itemPermission'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemPermission = $this->permissionService->find($id);
        $features = $this->featureService->all();
        $roles = $this->roleService->all();
        $sysControllers = $this->sysControllerService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::permission._fields', compact('itemPermission', 'features', 'roles', 'sysControllers'));
        }

        return view('PkgAutorisation::permission.edit', compact('itemPermission', 'features', 'roles', 'sysControllers'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(PermissionRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $permission = $this->permissionService->update($id, $validatedData);

        $permission->features()->sync($request->input('features'));
        $permission->roles()->sync($request->input('roles'));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')])
            ]);
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
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $permission = $this->permissionService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')])
            ]);
        }

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
