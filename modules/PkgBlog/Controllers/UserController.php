<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgBlog\App\Requests\UserRequest;
use Modules\PkgBlog\Services\UserService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgBlog\App\Exports\UserExport;
use Modules\PkgBlog\App\Imports\UserImport;

class UserController extends AdminController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
                'html' => view('PkgBlog::user.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgBlog::user.index', compact('data'));
    }

    public function create()
    {
        $item = $this->userService->createInstance();
        return view('PkgBlog::user.create', compact('item'));
    }

    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();
        $user = $this->userService->create($validatedData);


        return redirect()->route('users.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $user,
            'modelName' => __('PkgBlog::user.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->userService->find($id);
        return view('PkgBlog::user.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->userService->find($id);
        return view('PkgBlog::user.edit', compact('item'));
    }

    public function update(UserRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $user = $this->userService->update($id, $validatedData);



        return redirect()->route('users.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $user,
                'modelName' =>  __('PkgBlog::user.singular')
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
                'modelName' =>  __('PkgBlog::user.singular')
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
            'modelNames' =>  __('PkgBlog::user.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getUsers()
    {
        $users = $this->userService->all();
        return response()->json($users);
    }
}
