<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgNotification\Controllers\Base;
use Modules\PkgNotification\Services\NotificationService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgNotification\App\Requests\NotificationRequest;
use Modules\PkgNotification\Models\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgNotification\App\Exports\NotificationExport;
use Modules\PkgNotification\App\Imports\NotificationImport;
use Modules\Core\Services\ContextState;

class BaseNotificationController extends AdminController
{
    protected $notificationService;
    protected $userService;

    public function __construct(NotificationService $notificationService, UserService $userService) {
        parent::__construct();
        $this->service  =  $notificationService;
        $this->notificationService = $notificationService;
        $this->userService = $userService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('notification.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('notification');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.notification.user_id') == null){
           $this->viewState->init('scope.notification.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('scope.notification.user_id') == null){
           $this->viewState->init('scope.notification.user_id'  , $this->sessionState->get('user_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $notifications_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'notifications_search',
                $this->viewState->get("filter.notification.notifications_search")
            )],
            $request->except(['notifications_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->notificationService->prepareDataForIndexView($notifications_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgNotification::notification._index', $notification_compact_value)->render();
            }else{
                return view($notification_partialViewName, $notification_compact_value)->render();
            }
        }

        return view('PkgNotification::notification.index', $notification_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.notification.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.notification.user_id'  , $this->sessionState->get('user_id'));
        }


        $itemNotification = $this->notificationService->createInstance();
        

        $users = $this->userService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgNotification::notification._fields', compact('bulkEdit' ,'itemNotification', 'users'));
        }
        return view('PkgNotification::notification.create', compact('bulkEdit' ,'itemNotification', 'users'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $notification_ids = $request->input('ids', []);

        if (!is_array($notification_ids) || count($notification_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.notification.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.notification.user_id'  , $this->sessionState->get('user_id'));
        }
 
         $itemNotification = $this->notificationService->find($notification_ids[0]);
         
 
        $users = $this->userService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemNotification = $this->notificationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgNotification::notification._fields', compact('bulkEdit', 'notification_ids', 'itemNotification', 'users'));
        }
        return view('PkgNotification::notification.bulk-edit', compact('bulkEdit', 'notification_ids', 'itemNotification', 'users'));
    }
    /**
     */
    public function store(NotificationRequest $request) {
        $validatedData = $request->validated();
        $notification = $this->notificationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $notification,
                'modelName' => __('PkgNotification::notification.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $notification->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('notifications.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $notification,
                'modelName' => __('PkgNotification::notification.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('notification.show_' . $id);

        $itemNotification = $this->notificationService->edit($id);
        $this->authorize('view', $itemNotification);


        if (request()->ajax()) {
            return view('PkgNotification::notification._show', array_merge(compact('itemNotification'),));
        }

        return view('PkgNotification::notification.show', array_merge(compact('itemNotification'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('notification.edit_' . $id);


        $itemNotification = $this->notificationService->edit($id);
        $this->authorize('edit', $itemNotification);


        $users = $this->userService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgNotification::notification._fields', array_merge(compact('bulkEdit' , 'itemNotification','users'),));
        }

        return view('PkgNotification::notification.edit', array_merge(compact('bulkEdit' ,'itemNotification','users'),));


    }
    /**
     */
    public function update(NotificationRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $notification = $this->notificationService->find($id);
        $this->authorize('update', $notification);

        $validatedData = $request->validated();
        $notification = $this->notificationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $notification,
                'modelName' =>  __('PkgNotification::notification.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $notification->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('notifications.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $notification,
                'modelName' =>  __('PkgNotification::notification.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $notification_ids = $request->input('notification_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($notification_ids) || count($notification_ids) === 0) {
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
            $notification_ids,
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
        $notification = $this->notificationService->find($id);
        $this->authorize('delete', $notification);

        $notification = $this->notificationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $notification,
                'modelName' =>  __('PkgNotification::notification.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('notifications.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $notification,
                'modelName' =>  __('PkgNotification::notification.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $notification_ids = $request->input('ids', []);
        if (!is_array($notification_ids) || count($notification_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($notification_ids as $id) {
            $entity = $this->notificationService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $notification = $this->notificationService->find($id);
            $this->authorize('delete', $notification);
            $this->notificationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($notification_ids) . ' éléments',
            'modelName' => __('PkgNotification::notification.plural')
        ]));
    }

    public function export($format)
    {
        $notifications_data = $this->notificationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NotificationExport($notifications_data,'csv'), 'notification_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NotificationExport($notifications_data,'xlsx'), 'notification_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NotificationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('notifications.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('notifications.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgNotification::notification.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNotifications()
    {
        $notifications = $this->notificationService->all();
        return response()->json($notifications);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Notification) par ID, en format JSON.
     */
    public function getNotification(Request $request, $id)
    {
        try {
            $notification = $this->notificationService->find($id);
            return response()->json($notification);
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
        $updatedNotification = $this->notificationService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedNotification],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
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
        $notificationRequest = new NotificationRequest();
        $fullRules = $notificationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:notifications,id'];
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