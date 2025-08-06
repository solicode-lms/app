<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgEvaluateurs\Controllers\Base;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;
use Modules\PkgEvaluateurs\Services\EvaluateurService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgEvaluateurs\App\Requests\EvaluationRealisationTacheRequest;
use Modules\PkgEvaluateurs\Models\EvaluationRealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgEvaluateurs\App\Exports\EvaluationRealisationTacheExport;
use Modules\PkgEvaluateurs\App\Imports\EvaluationRealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseEvaluationRealisationTacheController extends AdminController
{
    protected $evaluationRealisationTacheService;
    protected $evaluateurService;
    protected $realisationTacheService;
    protected $evaluationRealisationProjetService;

    public function __construct(EvaluationRealisationTacheService $evaluationRealisationTacheService, EvaluateurService $evaluateurService, RealisationTacheService $realisationTacheService, EvaluationRealisationProjetService $evaluationRealisationProjetService) {
        parent::__construct();
        $this->service  =  $evaluationRealisationTacheService;
        $this->evaluationRealisationTacheService = $evaluationRealisationTacheService;
        $this->evaluateurService = $evaluateurService;
        $this->realisationTacheService = $realisationTacheService;
        $this->evaluationRealisationProjetService = $evaluationRealisationProjetService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('evaluationRealisationTache.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('evaluationRealisationTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $evaluationRealisationTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'evaluationRealisationTaches_search',
                $this->viewState->get("filter.evaluationRealisationTache.evaluationRealisationTaches_search")
            )],
            $request->except(['evaluationRealisationTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->evaluationRealisationTacheService->prepareDataForIndexView($evaluationRealisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgEvaluateurs::evaluationRealisationTache._index', $evaluationRealisationTache_compact_value)->render();
            }else{
                return view($evaluationRealisationTache_partialViewName, $evaluationRealisationTache_compact_value)->render();
            }
        }

        return view('PkgEvaluateurs::evaluationRealisationTache.index', $evaluationRealisationTache_compact_value);
    }
    /**
     */
    public function create() {


        $itemEvaluationRealisationTache = $this->evaluationRealisationTacheService->createInstance();
        

        $realisationTaches = $this->realisationTacheService->all();
        $evaluateurs = $this->evaluateurService->all();
        $evaluationRealisationProjets = $this->evaluationRealisationProjetService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluationRealisationTache._fields', compact('bulkEdit' ,'itemEvaluationRealisationTache', 'evaluateurs', 'realisationTaches', 'evaluationRealisationProjets'));
        }
        return view('PkgEvaluateurs::evaluationRealisationTache.create', compact('bulkEdit' ,'itemEvaluationRealisationTache', 'evaluateurs', 'realisationTaches', 'evaluationRealisationProjets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $evaluationRealisationTache_ids = $request->input('ids', []);

        if (!is_array($evaluationRealisationTache_ids) || count($evaluationRealisationTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEvaluationRealisationTache = $this->evaluationRealisationTacheService->find($evaluationRealisationTache_ids[0]);
         
 
        $realisationTaches = $this->realisationTacheService->all();
        $evaluateurs = $this->evaluateurService->all();
        $evaluationRealisationProjets = $this->evaluationRealisationProjetService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEvaluationRealisationTache = $this->evaluationRealisationTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluationRealisationTache._fields', compact('bulkEdit', 'evaluationRealisationTache_ids', 'itemEvaluationRealisationTache', 'evaluateurs', 'realisationTaches', 'evaluationRealisationProjets'));
        }
        return view('PkgEvaluateurs::evaluationRealisationTache.bulk-edit', compact('bulkEdit', 'evaluationRealisationTache_ids', 'itemEvaluationRealisationTache', 'evaluateurs', 'realisationTaches', 'evaluationRealisationProjets'));
    }
    /**
     */
    public function store(EvaluationRealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $evaluationRealisationTache = $this->evaluationRealisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $evaluationRealisationTache,
                'modelName' => __('PkgEvaluateurs::evaluationRealisationTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $evaluationRealisationTache->id]
            );
        }

        return redirect()->route('evaluationRealisationTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $evaluationRealisationTache,
                'modelName' => __('PkgEvaluateurs::evaluationRealisationTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('evaluationRealisationTache.show_' . $id);

        $itemEvaluationRealisationTache = $this->evaluationRealisationTacheService->edit($id);


        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluationRealisationTache._show', array_merge(compact('itemEvaluationRealisationTache'),));
        }

        return view('PkgEvaluateurs::evaluationRealisationTache.show', array_merge(compact('itemEvaluationRealisationTache'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('evaluationRealisationTache.edit_' . $id);


        $itemEvaluationRealisationTache = $this->evaluationRealisationTacheService->edit($id);


        $realisationTaches = $this->realisationTacheService->all();
        $evaluateurs = $this->evaluateurService->all();
        $evaluationRealisationProjets = $this->evaluationRealisationProjetService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluationRealisationTache._fields', array_merge(compact('bulkEdit' , 'itemEvaluationRealisationTache','evaluateurs', 'realisationTaches', 'evaluationRealisationProjets'),));
        }

        return view('PkgEvaluateurs::evaluationRealisationTache.edit', array_merge(compact('bulkEdit' ,'itemEvaluationRealisationTache','evaluateurs', 'realisationTaches', 'evaluationRealisationProjets'),));


    }
    /**
     */
    public function update(EvaluationRealisationTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $evaluationRealisationTache = $this->evaluationRealisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $evaluationRealisationTache,
                'modelName' =>  __('PkgEvaluateurs::evaluationRealisationTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $evaluationRealisationTache->id]
            );
        }

        return redirect()->route('evaluationRealisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $evaluationRealisationTache,
                'modelName' =>  __('PkgEvaluateurs::evaluationRealisationTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $evaluationRealisationTache_ids = $request->input('evaluationRealisationTache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($evaluationRealisationTache_ids) || count($evaluationRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($evaluationRealisationTache_ids as $id) {
            $entity = $this->evaluationRealisationTacheService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->evaluationRealisationTacheService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->evaluationRealisationTacheService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $evaluationRealisationTache = $this->evaluationRealisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluationRealisationTache,
                'modelName' =>  __('PkgEvaluateurs::evaluationRealisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('evaluationRealisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluationRealisationTache,
                'modelName' =>  __('PkgEvaluateurs::evaluationRealisationTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $evaluationRealisationTache_ids = $request->input('ids', []);
        if (!is_array($evaluationRealisationTache_ids) || count($evaluationRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($evaluationRealisationTache_ids as $id) {
            $entity = $this->evaluationRealisationTacheService->find($id);
            $this->evaluationRealisationTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($evaluationRealisationTache_ids) . ' éléments',
            'modelName' => __('PkgEvaluateurs::evaluationRealisationTache.plural')
        ]));
    }

    public function export($format)
    {
        $evaluationRealisationTaches_data = $this->evaluationRealisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EvaluationRealisationTacheExport($evaluationRealisationTaches_data,'csv'), 'evaluationRealisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EvaluationRealisationTacheExport($evaluationRealisationTaches_data,'xlsx'), 'evaluationRealisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EvaluationRealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('evaluationRealisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('evaluationRealisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgEvaluateurs::evaluationRealisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEvaluationRealisationTaches()
    {
        $evaluationRealisationTaches = $this->evaluationRealisationTacheService->all();
        return response()->json($evaluationRealisationTaches);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EvaluationRealisationTache) par ID, en format JSON.
     */
    public function getEvaluationRealisationTache(Request $request, $id)
    {
        try {
            $evaluationRealisationTache = $this->evaluationRealisationTacheService->find($id);
            return response()->json($evaluationRealisationTache);
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
        $updatedEvaluationRealisationTache = $this->evaluationRealisationTacheService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedEvaluationRealisationTache
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
        $evaluationRealisationTacheRequest = new EvaluationRealisationTacheRequest();
        $fullRules = $evaluationRealisationTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:evaluation_realisation_taches,id'];
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