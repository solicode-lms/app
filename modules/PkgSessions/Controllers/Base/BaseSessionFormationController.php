<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgSessions\Controllers\Base;
use Modules\PkgSessions\Services\SessionFormationService;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgSessions\Services\AlignementUaService;
use Modules\PkgSessions\Services\LivrableSessionService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgSessions\App\Requests\SessionFormationRequest;
use Modules\PkgSessions\Models\SessionFormation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgSessions\App\Exports\SessionFormationExport;
use Modules\PkgSessions\App\Imports\SessionFormationImport;
use Modules\Core\Services\ContextState;

class BaseSessionFormationController extends AdminController
{
    protected $sessionFormationService;
    protected $anneeFormationService;
    protected $filiereService;

    public function __construct(SessionFormationService $sessionFormationService, AnneeFormationService $anneeFormationService, FiliereService $filiereService) {
        parent::__construct();
        $this->service  =  $sessionFormationService;
        $this->sessionFormationService = $sessionFormationService;
        $this->anneeFormationService = $anneeFormationService;
        $this->filiereService = $filiereService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('sessionFormation.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('sessionFormation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $sessionFormations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'sessionFormations_search',
                $this->viewState->get("filter.sessionFormation.sessionFormations_search")
            )],
            $request->except(['sessionFormations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->sessionFormationService->prepareDataForIndexView($sessionFormations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgSessions::sessionFormation._index', $sessionFormation_compact_value)->render();
            }else{
                return view($sessionFormation_partialViewName, $sessionFormation_compact_value)->render();
            }
        }

        return view('PkgSessions::sessionFormation.index', $sessionFormation_compact_value);
    }
    /**
     */
    public function create() {


        $itemSessionFormation = $this->sessionFormationService->createInstance();
        

        $filieres = $this->filiereService->all();
        $anneeFormations = $this->anneeFormationService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgSessions::sessionFormation._fields', compact('bulkEdit' ,'itemSessionFormation', 'anneeFormations', 'filieres'));
        }
        return view('PkgSessions::sessionFormation.create', compact('bulkEdit' ,'itemSessionFormation', 'anneeFormations', 'filieres'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $sessionFormation_ids = $request->input('ids', []);

        if (!is_array($sessionFormation_ids) || count($sessionFormation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemSessionFormation = $this->sessionFormationService->find($sessionFormation_ids[0]);
         
 
        $filieres = $this->filiereService->all();
        $anneeFormations = $this->anneeFormationService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemSessionFormation = $this->sessionFormationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgSessions::sessionFormation._fields', compact('bulkEdit', 'sessionFormation_ids', 'itemSessionFormation', 'anneeFormations', 'filieres'));
        }
        return view('PkgSessions::sessionFormation.bulk-edit', compact('bulkEdit', 'sessionFormation_ids', 'itemSessionFormation', 'anneeFormations', 'filieres'));
    }
    /**
     */
    public function store(SessionFormationRequest $request) {
        $validatedData = $request->validated();
        $sessionFormation = $this->sessionFormationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sessionFormation,
                'modelName' => __('PkgSessions::sessionFormation.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sessionFormation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('sessionFormations.edit', ['sessionFormation' => $sessionFormation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sessionFormation,
                'modelName' => __('PkgSessions::sessionFormation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('sessionFormation.show_' . $id);

        $itemSessionFormation = $this->sessionFormationService->edit($id);


        $this->viewState->set('scope.alignementUa.session_formation_id', $id);
        

        $alignementUaService =  new AlignementUaService();
        $alignementUas_view_data = $alignementUaService->prepareDataForIndexView();
        extract($alignementUas_view_data);

        $this->viewState->set('scope.livrableSession.session_formation_id', $id);
        

        $livrableSessionService =  new LivrableSessionService();
        $livrableSessions_view_data = $livrableSessionService->prepareDataForIndexView();
        extract($livrableSessions_view_data);

        $this->viewState->set('scope.projet.session_formation_id', $id);
        

        $projetService =  new ProjetService();
        $projets_view_data = $projetService->prepareDataForIndexView();
        extract($projets_view_data);

        if (request()->ajax()) {
            return view('PkgSessions::sessionFormation._show', array_merge(compact('itemSessionFormation'),$alignementUa_compact_value, $livrableSession_compact_value, $projet_compact_value));
        }

        return view('PkgSessions::sessionFormation.show', array_merge(compact('itemSessionFormation'),$alignementUa_compact_value, $livrableSession_compact_value, $projet_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('sessionFormation.edit_' . $id);


        $itemSessionFormation = $this->sessionFormationService->edit($id);


        $filieres = $this->filiereService->all();
        $anneeFormations = $this->anneeFormationService->all();


        $this->viewState->set('scope.alignementUa.session_formation_id', $id);
        

        $alignementUaService =  new AlignementUaService();
        $alignementUas_view_data = $alignementUaService->prepareDataForIndexView();
        extract($alignementUas_view_data);

        $this->viewState->set('scope.livrableSession.session_formation_id', $id);
        

        $livrableSessionService =  new LivrableSessionService();
        $livrableSessions_view_data = $livrableSessionService->prepareDataForIndexView();
        extract($livrableSessions_view_data);

        $this->viewState->set('scope.projet.session_formation_id', $id);
        

        $projetService =  new ProjetService();
        $projets_view_data = $projetService->prepareDataForIndexView();
        extract($projets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgSessions::sessionFormation._edit', array_merge(compact('bulkEdit' , 'itemSessionFormation','anneeFormations', 'filieres'),$alignementUa_compact_value, $livrableSession_compact_value, $projet_compact_value));
        }

        return view('PkgSessions::sessionFormation.edit', array_merge(compact('bulkEdit' ,'itemSessionFormation','anneeFormations', 'filieres'),$alignementUa_compact_value, $livrableSession_compact_value, $projet_compact_value));


    }
    /**
     */
    public function update(SessionFormationRequest $request, string $id) {

        $validatedData = $request->validated();
        $sessionFormation = $this->sessionFormationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sessionFormation,
                'modelName' =>  __('PkgSessions::sessionFormation.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sessionFormation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('sessionFormations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sessionFormation,
                'modelName' =>  __('PkgSessions::sessionFormation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $sessionFormation_ids = $request->input('sessionFormation_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($sessionFormation_ids) || count($sessionFormation_ids) === 0) {
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
            $sessionFormation_ids,
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

        $sessionFormation = $this->sessionFormationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sessionFormation,
                'modelName' =>  __('PkgSessions::sessionFormation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('sessionFormations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sessionFormation,
                'modelName' =>  __('PkgSessions::sessionFormation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $sessionFormation_ids = $request->input('ids', []);
        if (!is_array($sessionFormation_ids) || count($sessionFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($sessionFormation_ids as $id) {
            $entity = $this->sessionFormationService->find($id);
            $this->sessionFormationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($sessionFormation_ids) . ' éléments',
            'modelName' => __('PkgSessions::sessionFormation.plural')
        ]));
    }

    public function export($format)
    {
        $sessionFormations_data = $this->sessionFormationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SessionFormationExport($sessionFormations_data,'csv'), 'sessionFormation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SessionFormationExport($sessionFormations_data,'xlsx'), 'sessionFormation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SessionFormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sessionFormations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sessionFormations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgSessions::sessionFormation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSessionFormations()
    {
        $sessionFormations = $this->sessionFormationService->all();
        return response()->json($sessionFormations);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (SessionFormation) par ID, en format JSON.
     */
    public function getSessionFormation(Request $request, $id)
    {
        try {
            $sessionFormation = $this->sessionFormationService->find($id);
            return response()->json($sessionFormation);
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
        $updatedSessionFormation = $this->sessionFormationService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedSessionFormation
        ]);
    }
    
    public function add_projet(Request $request, string $id) {
        $sessionFormation = $this->sessionFormationService->add_projet($id);
        if ($request->ajax()) {
            $message = "Le projet a été ajouté avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('SessionFormation.index')->with(
            'success',
            "Le projet a été ajouté avec succès"
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
        $sessionFormationRequest = new SessionFormationRequest();
        $fullRules = $sessionFormationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:session_formations,id'];
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