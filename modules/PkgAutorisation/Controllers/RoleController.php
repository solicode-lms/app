<?php

namespace Modules\PkgAutorisation\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\Models\SysModule;
use Modules\Core\Services\FeatureService;
use Modules\PkgAutorisation\App\Exports\RoleExport;
use Modules\PkgAutorisation\App\Imports\RoleImport;
use Modules\PkgAutorisation\App\Requests\RoleRequest;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\PkgAutorisation\Services\UserService;

class RoleController extends AdminController
{
    protected $roleService;
    protected $permissionService;
    protected $userService;
    protected $featureService;

    public function __construct(
        RoleService $roleService,
        FeatureService $featureService,
        PermissionService $permissionService,
        UserService $userService
    ) {
        parent::__construct();
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
        $this->userService = $userService;
        $this->featureService = $featureService;
    }

    /**
     * Affiche la liste des rôles avec pagination et recherche.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('searchValue', ''));
        $data = $this->roleService->paginate($searchQuery);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgAutorisation::role._table', compact('data'))->render(),
            ]);
        }

        return view('PkgAutorisation::role.index', compact('data'));
    }

    /**
     * Affiche le formulaire de création d'un rôle.
     */
    public function create()
    {
        return view('PkgAutorisation::role.create', [
            'item' => $this->roleService->createInstance(),
            'sysModules' => SysModule::with(['featureDomains.features'])->get(),
            'users' => $this->userService->all(),
        ]);
    }

    /**
     * Enregistre un nouveau rôle avec ses permissions et utilisateurs associés.
     */
    public function store(RoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());
        $this->syncFeaturesAndUsers($request, $role);

        return redirect()->route('roles.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $role,
            'modelName' => __('PkgAutorisation::role.singular'),
        ]));
    }

    /**
     * Affiche les détails d'un rôle.
     */
    public function show(string $id)
    {
        return view('PkgAutorisation::role.show', [
            'item' => $this->roleService->find($id),
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un rôle.
     */
    public function edit(string $id)
    {
        return view('PkgAutorisation::role.edit', [
            'item' => $this->roleService->find($id),
            'sysModules' => SysModule::with(['featureDomains.features'])->get(),
            'users' => $this->userService->all(),
        ]);
    }

    /**
     * Met à jour un rôle avec ses nouvelles permissions et utilisateurs associés.
     */
    public function update(RoleRequest $request, string $id)
    {
        $role = $this->roleService->update($id, $request->validated());
        $this->syncFeaturesAndUsers($request, $role);

        return redirect()->route('roles.index')->with('success', __('Core::msg.updateSuccess', [
            'entityToString' => $role,
            'modelName' => __('PkgAutorisation::role.singular'),
        ]));
    }

    /**
     * Supprime un rôle.
     */
    public function destroy(string $id)
    {
        $role = $this->roleService->destroy($id);

        return redirect()->route('roles.index')->with('success', __('Core::msg.deleteSuccess', [
            'entityToString' => $role,
            'modelName' => __('PkgAutorisation::role.singular'),
        ]));
    }

    /**
     * Exporte la liste des rôles au format Excel.
     */
    public function export()
    {
        return Excel::download(new RoleExport($this->roleService->all()), 'role_export.xlsx');
    }

    /**
     * Importe des rôles depuis un fichier.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        try {
            Excel::import(new RoleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('roles.index')->withError(__('Core::msg.importError'));
        }

        return redirect()->route('roles.index')->with('success', __('Core::msg.importSuccess', [
            'modelNames' => __('PkgAutorisation::role.plural'),
        ]));
    }

    /**
     * Retourne la liste des rôles au format JSON.
     */
    public function getRoles()
    {
        return response()->json($this->roleService->all());
    }

    /**
     * Synchronise les fonctionnalités et les utilisateurs avec un rôle.
     */
    protected function syncFeaturesAndUsers(Request $request, Role $role)
    {
        $request->validate([
            'features' => 'array',
            'features.*' => 'exists:features,id',
        ]);

        $selectedFeatureIds = $request->input('features', []);
        $permissions = Permission::whereIn('id', function ($query) use ($selectedFeatureIds) {
            $query->select('permission_id')
                  ->from('feature_permission')
                  ->whereIn('feature_id', $selectedFeatureIds);
        })->get();

        $role->syncPermissions($permissions);

        if ($request->has('users')) {
            $role->users()->sync($request->input('users'));
        }
    }
}
