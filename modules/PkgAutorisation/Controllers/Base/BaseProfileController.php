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
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgAutorisation\App\Exports\ProfileExport;
use Modules\PkgAutorisation\App\Imports\ProfileImport;
use Modules\Core\Services\ContextState;

class BaseProfileController extends AdminController
{
    protected $profileService;
    protected $userService;

    public function __construct(ProfileService $profileService, UserService $userService) {
        parent::__construct();
        $this->service  =  $profileService;
        $this->profileService = $profileService;
        $this->userService = $userService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('profile.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('profile');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.profile.user_id') == null){
           $this->viewState->init('scope.profile.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('scope.profile.user_id') == null){
           $this->viewState->init('scope.profile.user_id'  , $this->sessionState->get('user_id'));
        }


        // scopeDataByRole
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.user.formateur.id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
            $this->viewState->init('scope.user.apprenant.id'  , $this->sessionState->get('apprenant_id'));
        }

         // Extraire les paramètres de recherche, pagination, filtres
        $profiles_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'profiles_search',
                $this->viewState->get("filter.profile.profiles_search")
            )],
            $request->except(['profiles_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->profileService->prepareDataForIndexView($profiles_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutorisation::profile._index', $profile_compact_value)->render();
            }else{
                return view($profile_partialViewName, $profile_compact_value)->render();
            }
        }

        return view('PkgAutorisation::profile.index', $profile_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.profile.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.profile.user_id'  , $this->sessionState->get('user_id'));
        }


        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.user.formateur.id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
            $this->viewState->init('scope.user.apprenant.id'  , $this->sessionState->get('apprenant_id'));
        }
        $itemProfile = $this->profileService->createInstance();
        

        $users = $this->userService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgAutorisation::profile._fields', compact('bulkEdit' ,'itemProfile', 'users'));
        }
        return view('PkgAutorisation::profile.create', compact('bulkEdit' ,'itemProfile', 'users'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $profile_ids = $request->input('ids', []);

        if (!is_array($profile_ids) || count($profile_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.profile.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.profile.user_id'  , $this->sessionState->get('user_id'));
        }
 
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.user.formateur.id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
            $this->viewState->init('scope.user.apprenant.id'  , $this->sessionState->get('apprenant_id'));
        }
         $itemProfile = $this->profileService->find($profile_ids[0]);
         
 
        $users = $this->userService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemProfile = $this->profileService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgAutorisation::profile._fields', compact('bulkEdit', 'profile_ids', 'itemProfile', 'users'));
        }
        return view('PkgAutorisation::profile.bulk-edit', compact('bulkEdit', 'profile_ids', 'itemProfile', 'users'));
    }
    /**
     */
    public function store(ProfileRequest $request) {
        $validatedData = $request->validated();
        $profile = $this->profileService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $profile,
                'modelName' => __('PkgAutorisation::profile.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $profile->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
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
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('profile.show_' . $id);

        $itemProfile = $this->profileService->edit($id);
        $this->authorize('view', $itemProfile);


        if (request()->ajax()) {
            return view('PkgAutorisation::profile._show', array_merge(compact('itemProfile'),));
        }

        return view('PkgAutorisation::profile.show', array_merge(compact('itemProfile'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('profile.edit_' . $id);

        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.user.formateur.id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
            $this->viewState->set('scope.user.apprenant.id'  , $this->sessionState->get('apprenant_id'));
        }

        $itemProfile = $this->profileService->edit($id);
        $this->authorize('edit', $itemProfile);


        $users = $this->userService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgAutorisation::profile._fields', array_merge(compact('bulkEdit' , 'itemProfile','users'),));
        }

        return view('PkgAutorisation::profile.edit', array_merge(compact('bulkEdit' ,'itemProfile','users'),));


    }
    /**
     */
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
                array_merge(
                    ['entity_id' => $profile->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $profile_ids = $request->input('profile_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($profile_ids) || count($profile_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }

        // 🔹 Récupérer les valeurs de ces champs
        $valeursChamps = [];
        foreach ($champsCoches as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob",$this->service->modelName,$this->service->moduleName);
         
        dispatch(new BulkEditJob(
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $profile_ids,
            $champsCoches,
            $valeursChamps
        ));

       
        return JsonResponseHelper::success(
             __('Mise à jour en masse effectuée avec succès.'),
                ['traitement_token' => $jobManager->getToken()]
        );

    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $profile_ids = $request->input('ids', []);
        if (!is_array($profile_ids) || count($profile_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($profile_ids as $id) {
            $entity = $this->profileService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $profile = $this->profileService->find($id);
            $this->authorize('delete', $profile);
            $this->profileService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($profile_ids) . ' éléments',
            'modelName' => __('PkgAutorisation::profile.plural')
        ]));
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

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Profile) par ID, en format JSON.
     */
    public function getProfile(Request $request, $id)
    {
        try {
            $profile = $this->profileService->find($id);
            return response()->json($profile);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Entité non trouvée ou erreur.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    public function dataCalcul(Request $request)
    {
        $data = $request->all();

        // Traitement métier personnalisé (ne modifie pas la base)
        $updatedProfile = $this->profileService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedProfile
        ]);
    }
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $profileRequest = new ProfileRequest();
        $fullRules = $profileRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:profiles,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(
             __('Mise à jour réussie.'),
                array_merge(
                    ['entity_id' => $validated['id']],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
        );
    }
}