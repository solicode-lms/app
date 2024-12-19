<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAuthentification\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAuthentification\App\Requests\RoleRequest;
use Modules\PkgAuthentification\Services\RoleService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAuthentification\App\Exports\RoleExport;
use Modules\PkgAuthentification\App\Imports\RoleImport;

class RoleController extends AdminController
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
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
                'html' => view('PkgAuthentification::role.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgAuthentification::role.index', compact('data'));
    }

    public function create()
    {
        $item = $this->roleService->createInstance();
        return view('PkgAuthentification::role.create', compact('item'));
    }

    public function store(RoleRequest $request)
    {
        $validatedData = $request->validated();
        $role = $this->roleService->create($validatedData);


        return redirect()->route('roles.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $role,
            'modelName' => __('PkgAuthentification::role.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->roleService->find($id);
        return view('PkgAuthentification::role.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->roleService->find($id);
        return view('PkgAuthentification::role.edit', compact('item'));
    }

    public function update(RoleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $role = $this->roleService->update($id, $validatedData);



        return redirect()->route('roles.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $role,
                'modelName' =>  __('PkgAuthentification::role.singular')
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
                'modelName' =>  __('PkgAuthentification::role.singular')
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
            'modelNames' =>  __('PkgAuthentification::role.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRoles()
    {
        $roles = $this->roleService->all();
        return response()->json($roles);
    }
}
