<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgEvaluateurs\Controllers\Base;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;
use Modules\PkgEvaluateurs\Services\EtatEvaluationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluateurService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgEvaluateurs\App\Requests\EvaluationRealisationProjetRequest;
use Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgEvaluateurs\App\Exports\EvaluationRealisationProjetExport;
use Modules\PkgEvaluateurs\App\Imports\EvaluationRealisationProjetImport;
use Modules\Core\Services\ContextState;

class BaseEvaluationRealisationProjetController extends AdminController
{
    protected $evaluationRealisationProjetService;
    protected $etatEvaluationProjetService;
    protected $evaluateurService;
    protected $realisationProjetService;

    public function __construct(EvaluationRealisationProjetService $evaluationRealisationProjetService, EtatEvaluationProjetService $etatEvaluationProjetService, EvaluateurService $evaluateurService, RealisationProjetService $realisationProjetService) {
        parent::__construct();
        $this->service  =  $evaluationRealisationProjetService;
        $this->evaluationRealisationProjetService = $evaluationRealisationProjetService;
        $this->etatEvaluationProjetService = $etatEvaluationProjetService;
        $this->evaluateurService = $evaluateurService;
        $this->realisationProjetService = $realisationProjetService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('evaluationRealisationProjet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('evaluationRealisationProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('evaluateur') && $this->viewState->get('scope.evaluationRealisationProjet.evaluateur_id') == null){
           $this->viewState->init('scope.evaluationRealisationProjet.evaluateur_id'  , $this->sessionState->get('evaluateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $evaluationRealisationProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'evaluationRealisationProjets_search',
                $this->viewState->get("filter.evaluationRealisationProjet.evaluationRealisationProjets_search")
            )],
            $request->except(['evaluationRealisationProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->evaluationRealisationProjetService->prepareDataForIndexView($evaluationRealisationProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgEvaluateurs::evaluationRealisationProjet._index', $evaluationRealisationProjet_compact_value)->render();
            }else{
                return view($evaluationRealisationProjet_partialViewName, $evaluationRealisationProjet_compact_value)->render();
            }
        }

        return view('PkgEvaluateurs::evaluationRealisationProjet.index', $evaluationRealisationProjet_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('evaluateur')){
           $this->viewState->set('scope_form.evaluationRealisationProjet.evaluateur_id'  , $this->sessionState->get('evaluateur_id'));
        }


        $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->createInstance();
        

        $realisationProjets = $this->realisationProjetService->all();
        $evaluateurs = $this->evaluateurService->all();
        $etatEvaluationProjets = $this->etatEvaluationProjetService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluationRealisationProjet._fields', compact('bulkEdit' ,'itemEvaluationRealisationProjet', 'etatEvaluationProjets', 'evaluateurs', 'realisationProjets'));
        }
        return view('PkgEvaluateurs::evaluationRealisationProjet.create', compact('bulkEdit' ,'itemEvaluationRealisationProjet', 'etatEvaluationProjets', 'evaluateurs', 'realisationProjets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $evaluationRealisationProjet_ids = $request->input('ids', []);

        if (!is_array($evaluationRealisationProjet_ids) || count($evaluationRealisationProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('evaluateur')){
           $this->viewState->set('scope_form.evaluationRealisationProjet.evaluateur_id'  , $this->sessionState->get('evaluateur_id'));
        }
 
         $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->find($evaluationRealisationProjet_ids[0]);
         
 
        $realisationProjets = $this->realisationProjetService->getAllForSelect($itemEvaluationRealisationProjet->realisationProjet);
        $evaluateurs = $this->evaluateurService->getAllForSelect($itemEvaluationRealisationProjet->evaluateur);
        $etatEvaluationProjets = $this->etatEvaluationProjetService->getAllForSelect($itemEvaluationRealisationProjet->etatEvaluationProjet);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluationRealisationProjet._fields', compact('bulkEdit', 'evaluationRealisationProjet_ids', 'itemEvaluationRealisationProjet', 'etatEvaluationProjets', 'evaluateurs', 'realisationProjets'));
        }
        return view('PkgEvaluateurs::evaluationRealisationProjet.bulk-edit', compact('bulkEdit', 'evaluationRealisationProjet_ids', 'itemEvaluationRealisationProjet', 'etatEvaluationProjets', 'evaluateurs', 'realisationProjets'));
    }
    /**
     */
    public function store(EvaluationRealisationProjetRequest $request) {
        $validatedData = $request->validated();
        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' => __('PkgEvaluateurs::evaluationRealisationProjet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $evaluationRealisationProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('evaluationRealisationProjets.edit', ['evaluationRealisationProjet' => $evaluationRealisationProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' => __('PkgEvaluateurs::evaluationRealisationProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('evaluationRealisationProjet.show_' . $id);

        $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->edit($id);
        $this->authorize('view', $itemEvaluationRealisationProjet);


        $this->viewState->set('scope.evaluationRealisationTache.evaluation_realisation_projet_id', $id);
        

        $evaluationRealisationTacheService =  new EvaluationRealisationTacheService();
        $evaluationRealisationTaches_view_data = $evaluationRealisationTacheService->prepareDataForIndexView();
        extract($evaluationRealisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluationRealisationProjet._show', array_merge(compact('itemEvaluationRealisationProjet'),$evaluationRealisationTache_compact_value));
        }

        return view('PkgEvaluateurs::evaluationRealisationProjet.show', array_merge(compact('itemEvaluationRealisationProjet'),$evaluationRealisationTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('evaluationRealisationProjet.edit_' . $id);


        $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->edit($id);
        $this->authorize('edit', $itemEvaluationRealisationProjet);


        $realisationProjets = $this->realisationProjetService->getAllForSelect($itemEvaluationRealisationProjet->realisationProjet);
        $evaluateurs = $this->evaluateurService->getAllForSelect($itemEvaluationRealisationProjet->evaluateur);
        $etatEvaluationProjets = $this->etatEvaluationProjetService->getAllForSelect($itemEvaluationRealisationProjet->etatEvaluationProjet);


        $this->viewState->set('scope.evaluationRealisationTache.evaluation_realisation_projet_id', $id);
        

        $evaluationRealisationTacheService =  new EvaluationRealisationTacheService();
        $evaluationRealisationTaches_view_data = $evaluationRealisationTacheService->prepareDataForIndexView();
        extract($evaluationRealisationTaches_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluationRealisationProjet._fields', array_merge(compact('bulkEdit' , 'itemEvaluationRealisationProjet','etatEvaluationProjets', 'evaluateurs', 'realisationProjets'),$evaluationRealisationTache_compact_value));
        }

        return view('PkgEvaluateurs::evaluationRealisationProjet.edit', array_merge(compact('bulkEdit' ,'itemEvaluationRealisationProjet','etatEvaluationProjets', 'evaluateurs', 'realisationProjets'),$evaluationRealisationTache_compact_value));


    }
    /**
     */
    public function update(EvaluationRealisationProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->find($id);
        $this->authorize('update', $evaluationRealisationProjet);

        $validatedData = $request->validated();
        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' =>  __('PkgEvaluateurs::evaluationRealisationProjet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $evaluationRealisationProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('evaluationRealisationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' =>  __('PkgEvaluateurs::evaluationRealisationProjet.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $evaluationRealisationProjet_ids = $request->input('evaluationRealisationProjet_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($evaluationRealisationProjet_ids) || count($evaluationRealisationProjet_ids) === 0) {
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
            $evaluationRealisationProjet_ids,
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
        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->find($id);
        $this->authorize('delete', $evaluationRealisationProjet);

        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' =>  __('PkgEvaluateurs::evaluationRealisationProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('evaluationRealisationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' =>  __('PkgEvaluateurs::evaluationRealisationProjet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $evaluationRealisationProjet_ids = $request->input('ids', []);
        if (!is_array($evaluationRealisationProjet_ids) || count($evaluationRealisationProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($evaluationRealisationProjet_ids as $id) {
            $entity = $this->evaluationRealisationProjetService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $evaluationRealisationProjet = $this->evaluationRealisationProjetService->find($id);
            $this->authorize('delete', $evaluationRealisationProjet);
            $this->evaluationRealisationProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($evaluationRealisationProjet_ids) . ' éléments',
            'modelName' => __('PkgEvaluateurs::evaluationRealisationProjet.plural')
        ]));
    }

    public function export($format)
    {
        $evaluationRealisationProjets_data = $this->evaluationRealisationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EvaluationRealisationProjetExport($evaluationRealisationProjets_data,'csv'), 'evaluationRealisationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EvaluationRealisationProjetExport($evaluationRealisationProjets_data,'xlsx'), 'evaluationRealisationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EvaluationRealisationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('evaluationRealisationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('evaluationRealisationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgEvaluateurs::evaluationRealisationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEvaluationRealisationProjets()
    {
        $evaluationRealisationProjets = $this->evaluationRealisationProjetService->all();
        return response()->json($evaluationRealisationProjets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EvaluationRealisationProjet) par ID, en format JSON.
     */
    public function getEvaluationRealisationProjet(Request $request, $id)
    {
        try {
            $evaluationRealisationProjet = $this->evaluationRealisationProjetService->find($id);
            return response()->json($evaluationRealisationProjet);
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
        $updatedEvaluationRealisationProjet = $this->evaluationRealisationProjetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEvaluationRealisationProjet],
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
        $evaluationRealisationProjetRequest = new EvaluationRealisationProjetRequest();
        $fullRules = $evaluationRealisationProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:evaluation_realisation_projets,id'];
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
        $itemEvaluationRealisationProjet = EvaluationRealisationProjet::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEvaluationRealisationProjet, $field);
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
        $itemEvaluationRealisationProjet = EvaluationRealisationProjet::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEvaluationRealisationProjet);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEvaluationRealisationProjet, $changes);

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