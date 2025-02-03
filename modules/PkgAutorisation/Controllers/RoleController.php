<?php

namespace Modules\PkgAutorisation\Controllers;

use Modules\Core\Models\SysModule;
use Modules\PkgAutorisation\App\Requests\RoleRequest;
use Modules\PkgAutorisation\Controllers\Base\BaseRoleController;
use Modules\PkgAutorisation\Models\Permission;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Services\RoleService;

class RoleController extends BaseRoleController
{
    protected $featureService;

    public function create()
    {
        return view('PkgAutorisation::role.create', [
            'itemRole' => $this->roleService->createInstance(),
            'sysModules' => SysModule::with(['featureDomains.features'])->get(),
            'users' => $this->userService->all(),
        ]);
    }

    public function store(RoleRequest $request)  
    {
        $validatedData = $request->validated();
        $role = $this->roleService->create($validatedData);
        $this->syncFeaturesAndUsers($request, $role);

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


    public function edit(string $id)
    {
        $itemRole = $this->roleService->find($id);
        $permissions = $this->permissionService->all();
        $users = $this->userService->all();

        $sysModules = SysModule::with(['featureDomains.features'])->get();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('role_id', $id);


        if (request()->ajax()) {
            return view('PkgAutorisation::role._fields', compact(
                'itemRole', 'permissions',
                'users','sysModules'));
        }

        return view('PkgAutorisation::role.edit', compact(
            'itemRole', 'permissions', 
            'users','sysModules'));
    }

    public function update(RoleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $role =   $this->roleService->update($id, $validatedData);

        // TODO : if permissions exist
        // if ($request->has('permissions'))
        $role->permissions()->sync($request->input('permissions'));
        $role->users()->sync($request->input('users'));

        $this->syncFeaturesAndUsers($request, $role);


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
     * Synchronise les fonctionnalités et les utilisateurs avec un rôle.
     */
    protected function syncFeaturesAndUsers(RoleRequest $request, Role $role)
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
