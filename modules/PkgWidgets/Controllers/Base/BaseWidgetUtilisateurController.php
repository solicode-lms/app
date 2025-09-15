<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\WidgetUtilisateurService;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgWidgets\Services\WidgetService;
use Modules\Core\Services\SysModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgWidgets\App\Requests\WidgetUtilisateurRequest;
use Modules\PkgWidgets\Models\WidgetUtilisateur;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgWidgets\App\Exports\WidgetUtilisateurExport;
use Modules\PkgWidgets\App\Imports\WidgetUtilisateurImport;
use Modules\Core\Services\ContextState;

class BaseWidgetUtilisateurController extends AdminController
{
    protected $widgetUtilisateurService;
    protected $userService;
    protected $widgetService;
    protected $sysModuleService;

    public function __construct(WidgetUtilisateurService $widgetUtilisateurService, UserService $userService, WidgetService $widgetService, SysModuleService $sysModuleService) {
        parent::__construct();
        $this->service  =  $widgetUtilisateurService;
        $this->widgetUtilisateurService = $widgetUtilisateurService;
        $this->userService = $userService;
        $this->widgetService = $widgetService;
        $this->sysModuleService = $sysModuleService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('widgetUtilisateur.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('widgetUtilisateur');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.widgetUtilisateur.user_id') == null){
           $this->viewState->init('scope.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('scope.widgetUtilisateur.user_id') == null){
           $this->viewState->init('scope.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('admin') && $this->viewState->get('scope.widgetUtilisateur.user_id') == null){
           $this->viewState->init('scope.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('evaluateur') && $this->viewState->get('scope.widgetUtilisateur.user_id') == null){
           $this->viewState->init('scope.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('gapp') && $this->viewState->get('scope.widgetUtilisateur.user_id') == null){
           $this->viewState->init('scope.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $widgetUtilisateurs_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'widgetUtilisateurs_search',
                $this->viewState->get("filter.widgetUtilisateur.widgetUtilisateurs_search")
            )],
            $request->except(['widgetUtilisateurs_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->widgetUtilisateurService->prepareDataForIndexView($widgetUtilisateurs_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgWidgets::widgetUtilisateur._index', $widgetUtilisateur_compact_value)->render();
            }else{
                return view($widgetUtilisateur_partialViewName, $widgetUtilisateur_compact_value)->render();
            }
        }

        return view('PkgWidgets::widgetUtilisateur.index', $widgetUtilisateur_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('admin')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('evaluateur')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('gapp')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }


        $itemWidgetUtilisateur = $this->widgetUtilisateurService->createInstance();
        

        $users = $this->userService->all();
        $widgets = $this->widgetService->all();
        $sysModules = $this->sysModuleService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgWidgets::widgetUtilisateur._fields', compact('bulkEdit' ,'itemWidgetUtilisateur', 'users', 'widgets', 'sysModules'));
        }
        return view('PkgWidgets::widgetUtilisateur.create', compact('bulkEdit' ,'itemWidgetUtilisateur', 'users', 'widgets', 'sysModules'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $widgetUtilisateur_ids = $request->input('ids', []);

        if (!is_array($widgetUtilisateur_ids) || count($widgetUtilisateur_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('admin')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('evaluateur')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('gapp')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
 
         $itemWidgetUtilisateur = $this->widgetUtilisateurService->find($widgetUtilisateur_ids[0]);
         
 
        $users = $this->userService->getAllForSelect($itemWidgetUtilisateur->user);
        $widgets = $this->widgetService->getAllForSelect($itemWidgetUtilisateur->widget);
        $sysModules = $this->sysModuleService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemWidgetUtilisateur = $this->widgetUtilisateurService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgWidgets::widgetUtilisateur._fields', compact('bulkEdit', 'widgetUtilisateur_ids', 'itemWidgetUtilisateur', 'users', 'widgets', 'sysModules'));
        }
        return view('PkgWidgets::widgetUtilisateur.bulk-edit', compact('bulkEdit', 'widgetUtilisateur_ids', 'itemWidgetUtilisateur', 'users', 'widgets', 'sysModules'));
    }
    /**
     */
    public function store(WidgetUtilisateurRequest $request) {
        $validatedData = $request->validated();
        $widgetUtilisateur = $this->widgetUtilisateurService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' => __('PkgWidgets::widgetUtilisateur.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $widgetUtilisateur->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('widgetUtilisateurs.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' => __('PkgWidgets::widgetUtilisateur.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('widgetUtilisateur.show_' . $id);

        $itemWidgetUtilisateur = $this->widgetUtilisateurService->edit($id);
        $this->authorize('view', $itemWidgetUtilisateur);


        if (request()->ajax()) {
            return view('PkgWidgets::widgetUtilisateur._show', array_merge(compact('itemWidgetUtilisateur'),));
        }

        return view('PkgWidgets::widgetUtilisateur.show', array_merge(compact('itemWidgetUtilisateur'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('widgetUtilisateur.edit_' . $id);


        $itemWidgetUtilisateur = $this->widgetUtilisateurService->edit($id);
        $this->authorize('edit', $itemWidgetUtilisateur);


        $users = $this->userService->getAllForSelect($itemWidgetUtilisateur->user);
        $widgets = $this->widgetService->getAllForSelect($itemWidgetUtilisateur->widget);
        $sysModules = $this->sysModuleService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgWidgets::widgetUtilisateur._fields', array_merge(compact('bulkEdit' , 'itemWidgetUtilisateur','users', 'widgets', 'sysModules'),));
        }

        return view('PkgWidgets::widgetUtilisateur.edit', array_merge(compact('bulkEdit' ,'itemWidgetUtilisateur','users', 'widgets', 'sysModules'),));


    }
    /**
     */
    public function update(WidgetUtilisateurRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $widgetUtilisateur = $this->widgetUtilisateurService->find($id);
        $this->authorize('update', $widgetUtilisateur);

        $validatedData = $request->validated();
        $widgetUtilisateur = $this->widgetUtilisateurService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' =>  __('PkgWidgets::widgetUtilisateur.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $widgetUtilisateur->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('widgetUtilisateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' =>  __('PkgWidgets::widgetUtilisateur.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');

        // 1) Structure de la requête (ids + champs cochés)
        $request->validate([
            'widgetUtilisateur_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('widgetUtilisateur_ids', []);
        $champsCoches = $request->input('fields_modifiables', []);

        // 2) Restreindre aux champs réellement éditables (côté service/UI)
        $updatableFields = $this->service->getFieldsEditable();
        $requestedFields = array_values(array_intersect($champsCoches, $updatableFields));
        if (empty($requestedFields)) {
            return JsonResponseHelper::error("Aucun champ sélectionné valide.");
        }

        // 3) Valeurs “bulk” proposées par l'utilisateur (payload uniforme)
        $valeursChamps = [];
        foreach ($requestedFields as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        // 4) Charger rules/messages du FormRequest sans dépendre de la current request
        $form         = new \Modules\PkgWidgets\App\Requests\WidgetUtilisateurRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->widgetUtilisateurService->find($id);
            $this->authorize('update', $model);

            // sanitizePayloadByRoles complète les champs non autorisés avec la valeur du modèle
            // et nous retourne la liste des champs "kept" donc effectivement modifiables par cet utilisateur
            [, $kept /* $removed */] = $this->service->sanitizePayloadByRoles(
                $valeursChamps,
                $model,
                $request->user()
            );

            $allowedAcrossAll = array_values(array_intersect($allowedAcrossAll, $kept));
            if (empty($allowedAcrossAll)) {
                break;
            }
        }

        if (empty($allowedAcrossAll)) {
            return JsonResponseHelper::error("Aucun des champs sélectionnés n’est autorisé à être modifié pour les éléments choisis.");
        }

        // 6) Payload & Rules finaux (uniquement champs autorisés pour TOUS les IDs)
        $finalPayload = [];
        foreach ($allowedAcrossAll as $f) {
            $finalPayload[$f] = $valeursChamps[$f] ?? null;
        }

        // Normaliser '' -> null pour les champs "nullable" en se basant sur les valeurs bulk
        foreach ($allowedAcrossAll as $f) {
            $rule = $fullRules[$f] ?? null;
            if (is_string($rule) && str_contains($rule, 'nullable')) {
                if (array_key_exists($f, $valeursChamps) && $valeursChamps[$f] === '') {
                    $finalPayload[$f] = null;
                }
            }
        }

        $finalRules = array_intersect_key($fullRules, array_flip($allowedAcrossAll));

        // 7) Validation finale avec les rules/messages du FormRequest
        \Illuminate\Support\Facades\Validator::make($finalPayload, $finalRules, $fullMessages)->validate();

        // 8) Dispatch du job avec uniquement les champs autorisés
        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob", $this->service->modelName, $this->service->moduleName);

        $ignored = array_values(array_diff($requestedFields, $allowedAcrossAll));

        dispatch(new BulkEditJob(
            Auth::id(),
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $ids,
            $allowedAcrossAll,
            $finalPayload
        ));

        $msg = 'Mise à jour en masse effectuée avec succès.';
        if (!empty($ignored)) {
            $msg .= ' Champs ignorés (non autorisés) : ' . implode(', ', $ignored) . '.';
        }

        return JsonResponseHelper::success($msg, [
            'traitement_token' => $jobManager->getToken()
        ]);
    
    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $widgetUtilisateur = $this->widgetUtilisateurService->find($id);
        $this->authorize('delete', $widgetUtilisateur);

        $widgetUtilisateur = $this->widgetUtilisateurService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' =>  __('PkgWidgets::widgetUtilisateur.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('widgetUtilisateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' =>  __('PkgWidgets::widgetUtilisateur.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $widgetUtilisateur_ids = $request->input('ids', []);
        if (!is_array($widgetUtilisateur_ids) || count($widgetUtilisateur_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($widgetUtilisateur_ids as $id) {
            $entity = $this->widgetUtilisateurService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $widgetUtilisateur = $this->widgetUtilisateurService->find($id);
            $this->authorize('delete', $widgetUtilisateur);
            $this->widgetUtilisateurService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($widgetUtilisateur_ids) . ' éléments',
            'modelName' => __('PkgWidgets::widgetUtilisateur.plural')
        ]));
    }

    public function export($format)
    {
        $widgetUtilisateurs_data = $this->widgetUtilisateurService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WidgetUtilisateurExport($widgetUtilisateurs_data,'csv'), 'widgetUtilisateur_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WidgetUtilisateurExport($widgetUtilisateurs_data,'xlsx'), 'widgetUtilisateur_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WidgetUtilisateurImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('widgetUtilisateurs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('widgetUtilisateurs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::widgetUtilisateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgetUtilisateurs()
    {
        $widgetUtilisateurs = $this->widgetUtilisateurService->all();
        return response()->json($widgetUtilisateurs);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (WidgetUtilisateur) par ID, en format JSON.
     */
    public function getWidgetUtilisateur(Request $request, $id)
    {
        try {
            $widgetUtilisateur = $this->widgetUtilisateurService->find($id);
            return response()->json($widgetUtilisateur);
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
        $updatedWidgetUtilisateur = $this->widgetUtilisateurService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedWidgetUtilisateur],
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
        $widgetUtilisateurRequest = new WidgetUtilisateurRequest();
        $fullRules = $widgetUtilisateurRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:widget_utilisateurs,id'];
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

    /**
     * Retourne les métadonnées d’un champ (type, options, validation, etag…)
     *  @DynamicPermissionIgnore
     */
    public function fieldMeta(int $id, string $field)
    {
        // $this->authorizeAction('update');
        $itemWidgetUtilisateur = WidgetUtilisateur::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemWidgetUtilisateur, $field);
        return response()->json(
            $data
        );
    }

    /**
     * PATCH inline d’une cellule avec gestion de l’ETag
     * @DynamicPermissionIgnore
     */
    public function patchInline(Request $request, int $id)
    {

        $this->authorizeAction('update');
        $itemWidgetUtilisateur = WidgetUtilisateur::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemWidgetUtilisateur);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemWidgetUtilisateur, $changes);

        return response()->json(
            array_merge(
                [
                    "ok"        => true,
                    "entity_id" => $updated->id,
                    "display"   => $this->service->formatDisplayValues($updated, array_keys($changes)),
                    "etag"      => $this->service->etag($updated),
                ],
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            )
        );
    }

   
}