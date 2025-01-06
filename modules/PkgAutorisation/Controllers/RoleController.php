<?php

namespace Modules\PkgAutorisation\Controllers;

use Modules\Core\Models\SysModule;
use Modules\PkgAutorisation\Controllers\Base\BaseRoleController;
use Modules\PkgAutorisation\Services\RoleService;

class RoleController extends BaseRoleController
{
    protected $featureService;

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
