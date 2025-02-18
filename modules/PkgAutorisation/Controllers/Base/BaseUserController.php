<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers\Base;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgAutorisation\Services\ProfileService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutorisation\App\Requests\UserRequest;
use Modules\PkgAutorisation\Models\User;
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
        
        $this->viewState->setContextKeyIfEmpty('user.index');

        // Extraire les paramètres de recherche, page, et filtres
        $users_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('users_search', $this->viewState->get("filter.user.users_search"))],
            $request->except(['users_search', 'page', 'sort'])
        );

        // Paginer les users
        $users_data = $this->userService->paginate($users_params);

        // Récupérer les statistiques et les champs filtrables
        $users_stats = $this->userService->getuserStats();
        $users_filters = $this->userService->getFieldsFilterable();
        $user_instance =  $this->userService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgAutorisation::user._table', compact('users_data', 'users_stats', 'users_filters','user_instance'))->render();
        }

        return view('PkgAutorisation::user.index', compact('users_data', 'users_stats', 'users_filters','user_instance'));
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

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $user,
                'modelName' => __('PkgAutorisation::user.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $user->id]
            );
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

        $this->viewState->setContextKey('user.edit_' . $id);
     
        $itemUser = $this->userService->find($id);
  
        $roles = $this->roleService->all();

        $this->viewState->set('scope.apprenant.user_id', $id);
        $apprenantService =  new ApprenantService();
        $apprenants_data =  $itemUser->apprenants()->paginate(10);
        $apprenants_stats = $apprenantService->getapprenantStats();
        $apprenants_filters = $apprenantService->getFieldsFilterable();
        $apprenant_instance =  $apprenantService->createInstance();
        $this->viewState->set('scope.formateur.user_id', $id);
        $formateurService =  new FormateurService();
        $formateurs_data =  $itemUser->formateurs()->paginate(10);
        $formateurs_stats = $formateurService->getformateurStats();
        $formateurs_filters = $formateurService->getFieldsFilterable();
        $formateur_instance =  $formateurService->createInstance();
        $this->viewState->set('scope.profile.user_id', $id);
        $profileService =  new ProfileService();
        $profiles_data =  $itemUser->profiles()->paginate(10);
        $profiles_stats = $profileService->getprofileStats();
        $profiles_filters = $profileService->getFieldsFilterable();
        $profile_instance =  $profileService->createInstance();

        if (request()->ajax()) {
            return view('PkgAutorisation::user._edit', compact('itemUser', 'roles', 'apprenants_data', 'formateurs_data', 'profiles_data', 'apprenants_stats', 'formateurs_stats', 'profiles_stats', 'apprenants_filters', 'formateurs_filters', 'profiles_filters', 'apprenant_instance', 'formateur_instance', 'profile_instance'));
        }

        return view('PkgAutorisation::user.edit', compact('itemUser', 'roles', 'apprenants_data', 'formateurs_data', 'profiles_data', 'apprenants_stats', 'formateurs_stats', 'profiles_stats', 'apprenants_filters', 'formateurs_filters', 'profiles_filters', 'apprenant_instance', 'formateur_instance', 'profile_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('user.edit_' . $id);

        $itemUser = $this->userService->find($id);

        $roles = $this->roleService->all();

        $this->viewState->set('scope.apprenant.user_id', $id);
        $apprenantService =  new ApprenantService();
        $apprenants_data =  $itemUser->apprenants()->paginate(10);
        $apprenants_stats = $apprenantService->getapprenantStats();
        $apprenants_filters = $apprenantService->getFieldsFilterable();
        $apprenant_instance =  $apprenantService->createInstance();
        $this->viewState->set('scope.formateur.user_id', $id);
        $formateurService =  new FormateurService();
        $formateurs_data =  $itemUser->formateurs()->paginate(10);
        $formateurs_stats = $formateurService->getformateurStats();
        $formateurs_filters = $formateurService->getFieldsFilterable();
        $formateur_instance =  $formateurService->createInstance();
        $this->viewState->set('scope.profile.user_id', $id);
        $profileService =  new ProfileService();
        $profiles_data =  $itemUser->profiles()->paginate(10);
        $profiles_stats = $profileService->getprofileStats();
        $profiles_filters = $profileService->getFieldsFilterable();
        $profile_instance =  $profileService->createInstance();

        if (request()->ajax()) {
            return view('PkgAutorisation::user._edit', compact('itemUser', 'roles', 'apprenants_data', 'formateurs_data', 'profiles_data', 'apprenants_stats', 'formateurs_stats', 'profiles_stats', 'apprenants_filters', 'formateurs_filters', 'profiles_filters', 'apprenant_instance', 'formateur_instance', 'profile_instance'));
        }

        return view('PkgAutorisation::user.edit', compact('itemUser', 'roles', 'apprenants_data', 'formateurs_data', 'profiles_data', 'apprenants_stats', 'formateurs_stats', 'profiles_stats', 'apprenants_filters', 'formateurs_filters', 'profiles_filters', 'apprenant_instance', 'formateur_instance', 'profile_instance'));

    }
    public function update(UserRequest $request, string $id) {

        $validatedData = $request->validated();
        $user = $this->userService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $user,
                'modelName' =>  __('PkgAutorisation::user.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $user->id]
            );
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
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $user,
                'modelName' =>  __('PkgAutorisation::user.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('users.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $user,
                'modelName' =>  __('PkgAutorisation::user.singular')
                ])
        );

    }

    public function export($format)
    {
        $users_data = $this->userService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new UserExport($users_data,'csv'), 'user_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new UserExport($users_data,'xlsx'), 'user_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $user = $this->userService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedUser = $this->userService->dataCalcul($user);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedUser
        ]);
    }
    
    public function initPassword(Request $request, string $id) {
        $user = $this->userService->initPassword($id);
        if ($request->ajax()) {
            $message = "Le mot de passe a été modifier avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('User.index')->with(
            'success',
            "Le mot de passe a été modifier avec succès"
        );
    }
    
}
