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

        if (request()->ajax()) {
            return view('PkgWidgets::widgetUtilisateur._fields', compact('itemWidgetUtilisateur', 'users', 'widgets', 'sysModules'));
        }
        return view('PkgWidgets::widgetUtilisateur.create', compact('itemWidgetUtilisateur', 'users', 'widgets', 'sysModules'));
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
         
 
        $users = $this->userService->all();
        $widgets = $this->widgetService->all();
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
             ['entity_id' => $widgetUtilisateur->id]
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


        $users = $this->userService->all();
        $widgets = $this->widgetService->all();
        $sysModules = $this->sysModuleService->all();


        if (request()->ajax()) {
            return view('PkgWidgets::widgetUtilisateur._fields', array_merge(compact('itemWidgetUtilisateur','users', 'widgets', 'sysModules'),));
        }

        return view('PkgWidgets::widgetUtilisateur.edit', array_merge(compact('itemWidgetUtilisateur','users', 'widgets', 'sysModules'),));


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
                ['entity_id' => $widgetUtilisateur->id]
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
    
        $widgetUtilisateur_ids = $request->input('widgetUtilisateur_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($widgetUtilisateur_ids) || count($widgetUtilisateur_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($widgetUtilisateur_ids as $id) {
            $entity = $this->widgetUtilisateurService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->widgetUtilisateurService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->widgetUtilisateurService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

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
                $message
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $widgetUtilisateur = $this->widgetUtilisateurService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWidgetUtilisateur = $this->widgetUtilisateurService->dataCalcul($widgetUtilisateur);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWidgetUtilisateur
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
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}