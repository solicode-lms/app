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
        $this->service  =  $profileService;
        $this->profileService = $profileService;
        $this->userService = $userService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('profile.index');
        
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
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'profiles_search',
                $this->viewState->get("filter.profile.profiles_search")
            )],
            $request->except(['profiles_search', 'page', 'sort'])
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

        if (request()->ajax()) {
            return view('PkgAutorisation::profile._fields', compact('itemProfile', 'users'));
        }
        return view('PkgAutorisation::profile.create', compact('itemProfile', 'users'));
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
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('profile.edit_' . $id);

        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.user.formateur.id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
            $this->viewState->set('scope.user.apprenant.id'  , $this->sessionState->get('apprenant_id'));
        }

        $itemProfile = $this->profileService->edit($id);
        $this->authorize('view', $itemProfile);


        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::profile._fields', array_merge(compact('itemProfile','users'),));
        }

        return view('PkgAutorisation::profile.edit', array_merge(compact('itemProfile','users'),));

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


        if (request()->ajax()) {
            return view('PkgAutorisation::profile._fields', array_merge(compact('itemProfile','users'),));
        }

        return view('PkgAutorisation::profile.edit', array_merge(compact('itemProfile','users'),));


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
    
        foreach ($profile_ids as $id) {
            $entity = $this->profileService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->profileService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->profileService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

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