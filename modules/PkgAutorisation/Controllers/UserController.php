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
        parent::__construct();
        $this->userService = $userService;
        $this->roleService = $roleService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->userService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgAutorisation::user._table', compact('data'))->render();
        }

        return view('PkgAutorisation::user.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemUser = $this->userService->createInstance();
        $roles = $this->roleService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::user._fields', compact('itemUser', 'roles'));
        }
        return view('PkgAutorisation::user.create', compact('itemUser', 'roles'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->userService->create($validatedData);

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $user,
                'modelName' => __('PkgAutorisation::user.singular')])
            ]);
        }

        return redirect()->route('users.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $user,
                'modelName' => __('PkgAutorisation::user.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemUser = $this->userService->find($id);
        $roles = $this->roleService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::user._fields', compact('itemUser', 'roles'));
        }

        return view('PkgAutorisation::user.show', compact('itemUser'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemUser = $this->userService->find($id);
        $roles = $this->roleService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::user._fields', compact('itemUser', 'roles'));
        }

        return view('PkgAutorisation::user.edit', compact('itemUser', 'roles'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(UserRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = $this->userService->update($id, $validatedData);

        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $user,
                'modelName' =>  __('PkgAutorisation::user.singular')])
            ]);
        }

        return redirect()->route('users.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $user,
                'modelName' =>  __('PkgAutorisation::user.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $user = $this->userService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $user,
                'modelName' =>  __('PkgAutorisation::user.singular')])
            ]);
        }

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
