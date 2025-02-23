<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers\Base;
use Modules\PkgAutorisation\Services\ProfileService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutorisation\App\Requests\ProfileRequest;
use Modules\PkgAutorisation\Models\Profile;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutorisation\App\Exports\ProfileExport;
use Modules\PkgAutorisation\App\Imports\ProfileImport;
use Modules\Core\Services\ContextState;

class BaseProfileController extends AdminController
{
    protected $profileService;
    protected $userService;

    public function __construct(ProfileService $profileService, UserService $userService) {
        parent::__construct();
        $this->profileService = $profileService;
        $this->userService = $userService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('profile.index');
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->init('scope.profile.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->init('scope.profile.user_id'  , $this->sessionState->get('user_id'));
        }

        // scopeDataByRole
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.user.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
            $this->viewState->init('scope.user.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }

        // Extraire les paramètres de recherche, page, et filtres
        $profiles_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('profiles_search', $this->viewState->get("filter.profile.profiles_search"))],
            $request->except(['profiles_search', 'page', 'sort'])
        );

        // Paginer les profiles
        $profiles_data = $this->profileService->paginate($profiles_params);

        // Récupérer les statistiques et les champs filtrables
        $profiles_stats = $this->profileService->getprofileStats();
        $profiles_filters = $this->profileService->getFieldsFilterable();
        $profile_instance =  $this->profileService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgAutorisation::profile._table', compact('profiles_data', 'profiles_stats', 'profiles_filters','profile_instance'))->render();
        }

        return view('PkgAutorisation::profile.index', compact('profiles_data', 'profiles_stats', 'profiles_filters','profile_instance'));
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.profile.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.profile.user_id'  , $this->sessionState->get('user_id'));
        }


        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.user.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
            $this->viewState->init('scope.user.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
        $itemProfile = $this->profileService->createInstance();
        
        $users = $this->userService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::profile._fields', compact('itemProfile', 'users'));
        }
        return view('PkgAutorisation::profile.create', compact('itemProfile', 'users'));
    }
    public function store(ProfileRequest $request) {
        $validatedData = $request->validated();
        $profile = $this->profileService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $profile,
                'modelName' => __('PkgAutorisation::profile.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $profile->id]
            );
        }

        return redirect()->route('profiles.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $profile,
                'modelName' => __('PkgAutorisation::profile.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('profile.edit_' . $id);

        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.user.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
            $this->viewState->init('scope.user.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
        $itemProfile = $this->profileService->find($id);
        $this->authorize('view', $itemProfile);

        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::profile._fields', compact('itemProfile', 'users'));
        }

        return view('PkgAutorisation::profile.edit', compact('itemProfile', 'users'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('profile.edit_' . $id);

        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.user.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
            $this->viewState->init('scope.user.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
        $itemProfile = $this->profileService->find($id);
        $this->authorize('edit', $itemProfile);

        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::profile._fields', compact('itemProfile', 'users'));
        }

        return view('PkgAutorisation::profile.edit', compact('itemProfile', 'users'));

    }
    public function update(ProfileRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $profile = $this->profileService->find($id);
        $this->authorize('update', $profile);

        $validatedData = $request->validated();
        $profile = $this->profileService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $profile,
                'modelName' =>  __('PkgAutorisation::profile.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $profile->id]
            );
        }

        return redirect()->route('profiles.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $profile,
                'modelName' =>  __('PkgAutorisation::profile.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $profile = $this->profileService->find($id);
        $this->authorize('delete', $profile);

        $profile = $this->profileService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $profile,
                'modelName' =>  __('PkgAutorisation::profile.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('profiles.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $profile,
                'modelName' =>  __('PkgAutorisation::profile.singular')
                ])
        );

    }

    public function export($format)
    {
        $profiles_data = $this->profileService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ProfileExport($profiles_data,'csv'), 'profile_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ProfileExport($profiles_data,'xlsx'), 'profile_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ProfileImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('profiles.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('profiles.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutorisation::profile.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getProfiles()
    {
        $profiles = $this->profileService->all();
        return response()->json($profiles);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $profile = $this->profileService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedProfile = $this->profileService->dataCalcul($profile);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedProfile
        ]);
    }
    

}
