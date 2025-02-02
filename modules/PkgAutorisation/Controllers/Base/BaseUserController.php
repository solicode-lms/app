<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers\Base;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgAutorisation\App\Requests\UserRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutorisation\App\Exports\UserExport;
use Modules\PkgAutorisation\App\Imports\UserImport;
use Modules\Core\Services\ContextState;

class BaseUserController extends AdminController
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService) {
        parent::__construct();
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $users_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('users_search', '')],
            $request->except(['users_search', 'page', 'sort'])
        );

        // Paginer les users
        $users_data = $this->userService->paginate($users_params);

        // Récupérer les statistiques et les champs filtrables
        $users_stats = $this->userService->getuserStats();
        $users_filters = $this->userService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgAutorisation::user._table', compact('users_data', 'users_stats', 'users_filters'))->render();
        }

        return view('PkgAutorisation::user.index', compact('users_data', 'users_stats', 'users_filters'));
    }
    public function create() {
        $itemUser = $this->userService->createInstance();
        $roles = $this->roleService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::user._fields', compact('itemUser', 'roles'));
        }
        return view('PkgAutorisation::user.create', compact('itemUser', 'roles'));
    }
    public function store(UserRequest $request) {
        $validatedData = $request->validated();
        $user = $this->userService->create($validatedData);


        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'user_id' => $user->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $user,
                'modelName' => __('PkgAutorisation::user.singular')])
            ]);
        }

        return redirect()->route('users.edit',['user' => $user->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $user,
                'modelName' => __('PkgAutorisation::user.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('user_id', $id);
        
        $itemUser = $this->userService->find($id);
        $roles = $this->roleService->all();
        $apprenantService =  new ApprenantService();
        $apprenants_data =  $itemUser->apprenants()->paginate(10);
        $apprenants_stats = $apprenantService->getapprenantStats();
        $apprenants_filters = $apprenantService->getFieldsFilterable();
        
        $formateurService =  new FormateurService();
        $formateurs_data =  $itemUser->formateurs()->paginate(10);
        $formateurs_stats = $formateurService->getformateurStats();
        $formateurs_filters = $formateurService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgAutorisation::user._fields', compact('itemUser', 'roles', 'apprenants_data', 'formateurs_data', 'apprenants_stats', 'formateurs_stats', 'apprenants_filters', 'formateurs_filters'));
        }

        return view('PkgAutorisation::user.edit', compact('itemUser', 'roles', 'apprenants_data', 'formateurs_data', 'apprenants_stats', 'formateurs_stats', 'apprenants_filters', 'formateurs_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('user_id', $id);
        
        $itemUser = $this->userService->find($id);
        $roles = $this->roleService->all();
        $apprenantService =  new ApprenantService();
        $apprenants_data =  $itemUser->apprenants()->paginate(10);
        $apprenants_stats = $apprenantService->getapprenantStats();
        $apprenants_filters = $apprenantService->getFieldsFilterable();
        
        $formateurService =  new FormateurService();
        $formateurs_data =  $itemUser->formateurs()->paginate(10);
        $formateurs_stats = $formateurService->getformateurStats();
        $formateurs_filters = $formateurService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgAutorisation::user._fields', compact('itemUser', 'roles', 'apprenants_data', 'formateurs_data', 'apprenants_stats', 'formateurs_stats', 'apprenants_filters', 'formateurs_filters'));
        }

        return view('PkgAutorisation::user.edit', compact('itemUser', 'roles', 'apprenants_data', 'formateurs_data', 'apprenants_stats', 'formateurs_stats', 'apprenants_filters', 'formateurs_filters'));

    }
    public function update(UserRequest $request, string $id) {

        $validatedData = $request->validated();
        $user = $this->userService->update($id, $validatedData);

        $user->roles()->sync($request->input('roles'));

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
    public function destroy(Request $request, string $id) {

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
        $users_data = $this->userService->all();
        return Excel::download(new UserExport($users_data), 'user_export.xlsx');
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
