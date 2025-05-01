<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers\Base;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgAutorisation\Services\ProfileService;
use Modules\PkgGestionTaches\Services\HistoriqueRealisationTacheService;
use Modules\PkgNotification\Services\NotificationService;
use Modules\Core\Services\UserModelFilterService;
use Modules\PkgWidgets\Services\WidgetUtilisateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->service  =  $userService;
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('user.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('user');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $users_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'users_search',
                $this->viewState->get("filter.user.users_search")
            )],
            $request->except(['users_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->userService->prepareDataForIndexView($users_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutorisation::user._index', $user_compact_value)->render();
            }else{
                return view($user_partialViewName, $user_compact_value)->render();
            }
        }

        return view('PkgAutorisation::user.index', $user_compact_value);
    }
    /**
     */
    public function create() {


        $itemUser = $this->userService->createInstance();
        

        $roles = $this->roleService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::user._fields', compact('itemUser', 'roles'));
        }
        return view('PkgAutorisation::user.create', compact('itemUser', 'roles'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $user_ids = $request->input('ids', []);

        if (!is_array($user_ids) || count($user_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemUser = $this->userService->find($user_ids[0]);
         
 
        $roles = $this->roleService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemUser = $this->userService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgAutorisation::user._fields', compact('bulkEdit', 'user_ids', 'itemUser', 'roles'));
        }
        return view('PkgAutorisation::user.bulk-edit', compact('bulkEdit', 'user_ids', 'itemUser', 'roles'));
    }
    /**
     */
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
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('user.edit_' . $id);


        $itemUser = $this->userService->edit($id);


        $roles = $this->roleService->all();


        $this->viewState->set('scope.apprenant.user_id', $id);
        

        $apprenantService =  new ApprenantService();
        $apprenants_view_data = $apprenantService->prepareDataForIndexView();
        extract($apprenants_view_data);

        $this->viewState->set('scope.formateur.user_id', $id);
        

        $formateurService =  new FormateurService();
        $formateurs_view_data = $formateurService->prepareDataForIndexView();
        extract($formateurs_view_data);

        $this->viewState->set('scope.profile.user_id', $id);
        

        $profileService =  new ProfileService();
        $profiles_view_data = $profileService->prepareDataForIndexView();
        extract($profiles_view_data);

        $this->viewState->set('scope.historiqueRealisationTache.user_id', $id);
        

        $historiqueRealisationTacheService =  new HistoriqueRealisationTacheService();
        $historiqueRealisationTaches_view_data = $historiqueRealisationTacheService->prepareDataForIndexView();
        extract($historiqueRealisationTaches_view_data);

        $this->viewState->set('scope.notification.user_id', $id);
        

        $notificationService =  new NotificationService();
        $notifications_view_data = $notificationService->prepareDataForIndexView();
        extract($notifications_view_data);

        $this->viewState->set('scope.userModelFilter.user_id', $id);
        

        $userModelFilterService =  new UserModelFilterService();
        $userModelFilters_view_data = $userModelFilterService->prepareDataForIndexView();
        extract($userModelFilters_view_data);

        $this->viewState->set('scope.widgetUtilisateur.user_id', $id);
        

        $widgetUtilisateurService =  new WidgetUtilisateurService();
        $widgetUtilisateurs_view_data = $widgetUtilisateurService->prepareDataForIndexView();
        extract($widgetUtilisateurs_view_data);

        if (request()->ajax()) {
            return view('PkgAutorisation::user._edit', array_merge(compact('itemUser','roles'),$apprenant_compact_value, $formateur_compact_value, $profile_compact_value, $historiqueRealisationTache_compact_value, $notification_compact_value, $userModelFilter_compact_value, $widgetUtilisateur_compact_value));
        }

        return view('PkgAutorisation::user.edit', array_merge(compact('itemUser','roles'),$apprenant_compact_value, $formateur_compact_value, $profile_compact_value, $historiqueRealisationTache_compact_value, $notification_compact_value, $userModelFilter_compact_value, $widgetUtilisateur_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('user.edit_' . $id);


        $itemUser = $this->userService->edit($id);


        $roles = $this->roleService->all();


        $this->viewState->set('scope.apprenant.user_id', $id);
        

        $apprenantService =  new ApprenantService();
        $apprenants_view_data = $apprenantService->prepareDataForIndexView();
        extract($apprenants_view_data);

        $this->viewState->set('scope.formateur.user_id', $id);
        

        $formateurService =  new FormateurService();
        $formateurs_view_data = $formateurService->prepareDataForIndexView();
        extract($formateurs_view_data);

        $this->viewState->set('scope.profile.user_id', $id);
        

        $profileService =  new ProfileService();
        $profiles_view_data = $profileService->prepareDataForIndexView();
        extract($profiles_view_data);

        $this->viewState->set('scope.historiqueRealisationTache.user_id', $id);
        

        $historiqueRealisationTacheService =  new HistoriqueRealisationTacheService();
        $historiqueRealisationTaches_view_data = $historiqueRealisationTacheService->prepareDataForIndexView();
        extract($historiqueRealisationTaches_view_data);

        $this->viewState->set('scope.notification.user_id', $id);
        

        $notificationService =  new NotificationService();
        $notifications_view_data = $notificationService->prepareDataForIndexView();
        extract($notifications_view_data);

        $this->viewState->set('scope.userModelFilter.user_id', $id);
        

        $userModelFilterService =  new UserModelFilterService();
        $userModelFilters_view_data = $userModelFilterService->prepareDataForIndexView();
        extract($userModelFilters_view_data);

        $this->viewState->set('scope.widgetUtilisateur.user_id', $id);
        

        $widgetUtilisateurService =  new WidgetUtilisateurService();
        $widgetUtilisateurs_view_data = $widgetUtilisateurService->prepareDataForIndexView();
        extract($widgetUtilisateurs_view_data);

        if (request()->ajax()) {
            return view('PkgAutorisation::user._edit', array_merge(compact('itemUser','roles'),$apprenant_compact_value, $formateur_compact_value, $profile_compact_value, $historiqueRealisationTache_compact_value, $notification_compact_value, $userModelFilter_compact_value, $widgetUtilisateur_compact_value));
        }

        return view('PkgAutorisation::user.edit', array_merge(compact('itemUser','roles'),$apprenant_compact_value, $formateur_compact_value, $profile_compact_value, $historiqueRealisationTache_compact_value, $notification_compact_value, $userModelFilter_compact_value, $widgetUtilisateur_compact_value));


    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $user_ids = $request->input('user_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($user_ids) || count($user_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($user_ids as $id) {
            $entity = $this->userService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->userService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->userService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $user_ids = $request->input('ids', []);
        if (!is_array($user_ids) || count($user_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($user_ids as $id) {
            $entity = $this->userService->find($id);
            $this->userService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($user_ids) . ' éléments',
            'modelName' => __('PkgAutorisation::user.plural')
        ]));
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
    

    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $userRequest = new UserRequest();
        $fullRules = $userRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:users,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}