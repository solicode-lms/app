<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\App\Requests\UserRequest;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgAutorisation\Services\RoleService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutorisation\App\Exports\UserExport;
use Modules\PkgAutorisation\App\Imports\UserImport;

class UserController extends AdminController
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->userService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgAutorisation::user.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgAutorisation::user.index', compact('data'));
    }

    public function create()
    {
        $item = $this->userService->createInstance();
        $roles = $this->roleService->all();
        return view('PkgAutorisation::user.create', compact('item', 'roles'));
    }

    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->userService->create($validatedData);

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        return redirect()->route('users.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $user,
            'modelName' => __('PkgAutorisation::user.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->userService->find($id);
        return view('PkgAutorisation::user.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->userService->find($id);
        $roles = $this->roleService->all();
        return view('PkgAutorisation::user.edit', compact('item', 'roles'));
    }

    public function update(UserRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = $this->userService->update($id, $validatedData);


        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        return redirect()->route('users.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $user,
                'modelName' =>  __('PkgAutorisation::user.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $user = $this->userService->destroy($id);
        return redirect()->route('users.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $user,
                'modelName' =>  __('PkgAutorisation::user.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->userService->all();
        return Excel::download(new UserExport($data), 'user_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new UserImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('users.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('users.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutorisation::user.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getUsers()
    {
        $users = $this->userService->all();
        return response()->json($users);
    }
}
