<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Controllers\Base;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;
use Modules\Core\Services\SysColorService;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationTache\App\Requests\WorkflowTacheRequest;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\App\Exports\WorkflowTacheExport;
use Modules\PkgRealisationTache\App\Imports\WorkflowTacheImport;
use Modules\Core\Services\ContextState;

class BaseWorkflowTacheController extends AdminController
{
    protected $workflowTacheService;
    protected $sysColorService;

    public function __construct(WorkflowTacheService $workflowTacheService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $workflowTacheService;
        $this->workflowTacheService = $workflowTacheService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('workflowTache.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('workflowTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $workflowTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'workflowTaches_search',
                $this->viewState->get("filter.workflowTache.workflowTaches_search")
            )],
            $request->except(['workflowTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->workflowTacheService->prepareDataForIndexView($workflowTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationTache::workflowTache._index', $workflowTache_compact_value)->render();
            }else{
                return view($workflowTache_partialViewName, $workflowTache_compact_value)->render();
            }
        }

        return view('PkgRealisationTache::workflowTache.index', $workflowTache_compact_value);
    }
    /**
     */
    public function create() {


        $itemWorkflowTache = $this->workflowTacheService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationTache::workflowTache._fields', compact('bulkEdit' ,'itemWorkflowTache', 'sysColors'));
        }
        return view('PkgRealisationTache::workflowTache.create', compact('bulkEdit' ,'itemWorkflowTache', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $workflowTache_ids = $request->input('ids', []);

        if (!is_array($workflowTache_ids) || count($workflowTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemWorkflowTache = $this->workflowTacheService->find($workflowTache_ids[0]);
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemWorkflowTache->sysColor);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemWorkflowTache = $this->workflowTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationTache::workflowTache._fields', compact('bulkEdit', 'workflowTache_ids', 'itemWorkflowTache', 'sysColors'));
        }
        return view('PkgRealisationTache::workflowTache.bulk-edit', compact('bulkEdit', 'workflowTache_ids', 'itemWorkflowTache', 'sysColors'));
    }
    /**
     */
    public function store(WorkflowTacheRequest $request) {
        $validatedData = $request->validated();
        $workflowTache = $this->workflowTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $workflowTache,
                'modelName' => __('PkgRealisationTache::workflowTache.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $workflowTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('workflowTaches.edit', ['workflowTache' => $workflowTache->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $workflowTache,
                'modelName' => __('PkgRealisationTache::workflowTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('workflowTache.show_' . $id);

        $itemWorkflowTache = $this->workflowTacheService->edit($id);


        $this->viewState->set('scope.etatRealisationTache.workflow_tache_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_view_data = $etatRealisationTacheService->prepareDataForIndexView();
        extract($etatRealisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationTache::workflowTache._show', array_merge(compact('itemWorkflowTache'),$etatRealisationTache_compact_value));
        }

        return view('PkgRealisationTache::workflowTache.show', array_merge(compact('itemWorkflowTache'),$etatRealisationTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('workflowTache.edit_' . $id);


        $itemWorkflowTache = $this->workflowTacheService->edit($id);


        $sysColors = $this->sysColorService->getAllForSelect($itemWorkflowTache->sysColor);


        $this->viewState->set('scope.etatRealisationTache.workflow_tache_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_view_data = $etatRealisationTacheService->prepareDataForIndexView();
        extract($etatRealisationTaches_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgRealisationTache::workflowTache._edit', array_merge(compact('bulkEdit' , 'itemWorkflowTache','sysColors'),$etatRealisationTache_compact_value));
        }

        return view('PkgRealisationTache::workflowTache.edit', array_merge(compact('bulkEdit' ,'itemWorkflowTache','sysColors'),$etatRealisationTache_compact_value));


    }
    /**
     */
    public function update(WorkflowTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $workflowTache = $this->workflowTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $workflowTache,
                'modelName' =>  __('PkgRealisationTache::workflowTache.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $workflowTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('workflowTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $workflowTache,
                'modelName' =>  __('PkgRealisationTache::workflowTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $workflowTache_ids = $request->input('workflowTache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($workflowTache_ids) || count($workflowTache_ids) === 0) {
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
            Auth::id(),
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $workflowTache_ids,
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

        $workflowTache = $this->workflowTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowTache,
                'modelName' =>  __('PkgRealisationTache::workflowTache.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('workflowTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowTache,
                'modelName' =>  __('PkgRealisationTache::workflowTache.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $workflowTache_ids = $request->input('ids', []);
        if (!is_array($workflowTache_ids) || count($workflowTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($workflowTache_ids as $id) {
            $entity = $this->workflowTacheService->find($id);
            $this->workflowTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($workflowTache_ids) . ' éléments',
            'modelName' => __('PkgRealisationTache::workflowTache.plural')
        ]));
    }

    public function export($format)
    {
        $workflowTaches_data = $this->workflowTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WorkflowTacheExport($workflowTaches_data,'csv'), 'workflowTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WorkflowTacheExport($workflowTaches_data,'xlsx'), 'workflowTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WorkflowTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('workflowTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('workflowTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationTache::workflowTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWorkflowTaches()
    {
        $workflowTaches = $this->workflowTacheService->all();
        return response()->json($workflowTaches);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (WorkflowTache) par ID, en format JSON.
     */
    public function getWorkflowTache(Request $request, $id)
    {
        try {
            $workflowTache = $this->workflowTacheService->find($id);
            return response()->json($workflowTache);
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
        $updatedWorkflowTache = $this->workflowTacheService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedWorkflowTache],
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
        $workflowTacheRequest = new WorkflowTacheRequest();
        $fullRules = $workflowTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:workflow_taches,id'];
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
        $itemWorkflowTache = WorkflowTache::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemWorkflowTache, $field);
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
        $itemWorkflowTache = WorkflowTache::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemWorkflowTache);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemWorkflowTache, $changes);

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