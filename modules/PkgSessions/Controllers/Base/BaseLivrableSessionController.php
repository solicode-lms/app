<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgSessions\Controllers\Base;
use Modules\PkgSessions\Services\LivrableSessionService;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Modules\PkgSessions\Services\SessionFormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgSessions\App\Requests\LivrableSessionRequest;
use Modules\PkgSessions\Models\LivrableSession;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgSessions\App\Exports\LivrableSessionExport;
use Modules\PkgSessions\App\Imports\LivrableSessionImport;
use Modules\Core\Services\ContextState;

class BaseLivrableSessionController extends AdminController
{
    protected $livrableSessionService;
    protected $natureLivrableService;
    protected $sessionFormationService;

    public function __construct(LivrableSessionService $livrableSessionService, NatureLivrableService $natureLivrableService, SessionFormationService $sessionFormationService) {
        parent::__construct();
        $this->service  =  $livrableSessionService;
        $this->livrableSessionService = $livrableSessionService;
        $this->natureLivrableService = $natureLivrableService;
        $this->sessionFormationService = $sessionFormationService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('livrableSession.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('livrableSession');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $livrableSessions_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'livrableSessions_search',
                $this->viewState->get("filter.livrableSession.livrableSessions_search")
            )],
            $request->except(['livrableSessions_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->livrableSessionService->prepareDataForIndexView($livrableSessions_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgSessions::livrableSession._index', $livrableSession_compact_value)->render();
            }else{
                return view($livrableSession_partialViewName, $livrableSession_compact_value)->render();
            }
        }

        return view('PkgSessions::livrableSession.index', $livrableSession_compact_value);
    }
    /**
     */
    public function create() {


        $itemLivrableSession = $this->livrableSessionService->createInstance();
        

        $sessionFormations = $this->sessionFormationService->all();
        $natureLivrables = $this->natureLivrableService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgSessions::livrableSession._fields', compact('bulkEdit' ,'itemLivrableSession', 'natureLivrables', 'sessionFormations'));
        }
        return view('PkgSessions::livrableSession.create', compact('bulkEdit' ,'itemLivrableSession', 'natureLivrables', 'sessionFormations'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $livrableSession_ids = $request->input('ids', []);

        if (!is_array($livrableSession_ids) || count($livrableSession_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemLivrableSession = $this->livrableSessionService->find($livrableSession_ids[0]);
         
 
        $sessionFormations = $this->sessionFormationService->all();
        $natureLivrables = $this->natureLivrableService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemLivrableSession = $this->livrableSessionService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgSessions::livrableSession._fields', compact('bulkEdit', 'livrableSession_ids', 'itemLivrableSession', 'natureLivrables', 'sessionFormations'));
        }
        return view('PkgSessions::livrableSession.bulk-edit', compact('bulkEdit', 'livrableSession_ids', 'itemLivrableSession', 'natureLivrables', 'sessionFormations'));
    }
    /**
     */
    public function store(LivrableSessionRequest $request) {
        $validatedData = $request->validated();
        $livrableSession = $this->livrableSessionService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $livrableSession,
                'modelName' => __('PkgSessions::livrableSession.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $livrableSession->id]
            );
        }

        return redirect()->route('livrableSessions.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $livrableSession,
                'modelName' => __('PkgSessions::livrableSession.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('livrableSession.show_' . $id);

        $itemLivrableSession = $this->livrableSessionService->edit($id);


        if (request()->ajax()) {
            return view('PkgSessions::livrableSession._show', array_merge(compact('itemLivrableSession'),));
        }

        return view('PkgSessions::livrableSession.show', array_merge(compact('itemLivrableSession'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('livrableSession.edit_' . $id);


        $itemLivrableSession = $this->livrableSessionService->edit($id);


        $sessionFormations = $this->sessionFormationService->all();
        $natureLivrables = $this->natureLivrableService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgSessions::livrableSession._fields', array_merge(compact('bulkEdit' , 'itemLivrableSession','natureLivrables', 'sessionFormations'),));
        }

        return view('PkgSessions::livrableSession.edit', array_merge(compact('bulkEdit' ,'itemLivrableSession','natureLivrables', 'sessionFormations'),));


    }
    /**
     */
    public function update(LivrableSessionRequest $request, string $id) {

        $validatedData = $request->validated();
        $livrableSession = $this->livrableSessionService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $livrableSession,
                'modelName' =>  __('PkgSessions::livrableSession.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $livrableSession->id]
            );
        }

        return redirect()->route('livrableSessions.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrableSession,
                'modelName' =>  __('PkgSessions::livrableSession.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $livrableSession_ids = $request->input('livrableSession_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($livrableSession_ids) || count($livrableSession_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($livrableSession_ids as $id) {
            $entity = $this->livrableSessionService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->livrableSessionService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->livrableSessionService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $livrableSession = $this->livrableSessionService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $livrableSession,
                'modelName' =>  __('PkgSessions::livrableSession.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('livrableSessions.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrableSession,
                'modelName' =>  __('PkgSessions::livrableSession.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $livrableSession_ids = $request->input('ids', []);
        if (!is_array($livrableSession_ids) || count($livrableSession_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($livrableSession_ids as $id) {
            $entity = $this->livrableSessionService->find($id);
            $this->livrableSessionService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($livrableSession_ids) . ' éléments',
            'modelName' => __('PkgSessions::livrableSession.plural')
        ]));
    }

    public function export($format)
    {
        $livrableSessions_data = $this->livrableSessionService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new LivrableSessionExport($livrableSessions_data,'csv'), 'livrableSession_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new LivrableSessionExport($livrableSessions_data,'xlsx'), 'livrableSession_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new LivrableSessionImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('livrableSessions.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('livrableSessions.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgSessions::livrableSession.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLivrableSessions()
    {
        $livrableSessions = $this->livrableSessionService->all();
        return response()->json($livrableSessions);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (LivrableSession) par ID, en format JSON.
     */
    public function getLivrableSession(Request $request, $id)
    {
        try {
            $livrableSession = $this->livrableSessionService->find($id);
            return response()->json($livrableSession);
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
        $updatedLivrableSession = $this->livrableSessionService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedLivrableSession
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
        $livrableSessionRequest = new LivrableSessionRequest();
        $fullRules = $livrableSessionRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:livrable_sessions,id'];
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