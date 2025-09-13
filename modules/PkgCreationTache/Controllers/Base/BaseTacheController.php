<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationTache\Controllers\Base;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgCompetences\Services\PhaseEvaluationService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgRealisationTache\Services\TacheAffectationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationTache\App\Requests\TacheRequest;
use Modules\PkgCreationTache\Models\Tache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationTache\App\Exports\TacheExport;
use Modules\PkgCreationTache\App\Imports\TacheImport;
use Modules\Core\Services\ContextState;

class BaseTacheController extends AdminController
{
    protected $tacheService;
    protected $livrableService;
    protected $chapitreService;
    protected $phaseEvaluationService;
    protected $projetService;

    public function __construct(TacheService $tacheService, LivrableService $livrableService, ChapitreService $chapitreService, PhaseEvaluationService $phaseEvaluationService, ProjetService $projetService) {
        parent::__construct();
        $this->service  =  $tacheService;
        $this->tacheService = $tacheService;
        $this->livrableService = $livrableService;
        $this->chapitreService = $chapitreService;
        $this->phaseEvaluationService = $phaseEvaluationService;
        $this->projetService = $projetService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('tache.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('tache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $taches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'taches_search',
                $this->viewState->get("filter.tache.taches_search")
            )],
            $request->except(['taches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->tacheService->prepareDataForIndexView($taches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationTache::tache._index', $tache_compact_value)->render();
            }else{
                return view($tache_partialViewName, $tache_compact_value)->render();
            }
        }

        return view('PkgCreationTache::tache.index', $tache_compact_value);
    }
    /**
     */
    public function create() {


        $itemTache = $this->tacheService->createInstance();
        
        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet_id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $projets = $this->projetService->all();
        $phaseEvaluations = $this->phaseEvaluationService->all();
        $chapitres = $this->chapitreService->all();
        $livrables = $this->livrableService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationTache::tache._fields', compact('bulkEdit' ,'itemTache', 'livrables', 'chapitres', 'phaseEvaluations', 'projets'));
        }
        return view('PkgCreationTache::tache.create', compact('bulkEdit' ,'itemTache', 'livrables', 'chapitres', 'phaseEvaluations', 'projets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $tache_ids = $request->input('ids', []);

        if (!is_array($tache_ids) || count($tache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemTache = $this->tacheService->find($tache_ids[0]);
         
        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet_id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);
 
        $projets = $this->projetService->getAllForSelect($itemTache->projet);
        $phaseEvaluations = $this->phaseEvaluationService->getAllForSelect($itemTache->phaseEvaluation);
        $chapitres = $this->chapitreService->getAllForSelect($itemTache->chapitre);
        $livrables = $this->livrableService->getAllForSelect($itemTache->livrables);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemTache = $this->tacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationTache::tache._fields', compact('bulkEdit', 'tache_ids', 'itemTache', 'livrables', 'chapitres', 'phaseEvaluations', 'projets'));
        }
        return view('PkgCreationTache::tache.bulk-edit', compact('bulkEdit', 'tache_ids', 'itemTache', 'livrables', 'chapitres', 'phaseEvaluations', 'projets'));
    }
    /**
     */
    public function store(TacheRequest $request) {
        $validatedData = $request->validated();
        $tache = $this->tacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $tache,
                'modelName' => __('PkgCreationTache::tache.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $tache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('taches.edit', ['tache' => $tache->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $tache,
                'modelName' => __('PkgCreationTache::tache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('tache.show_' . $id);

        $itemTache = $this->tacheService->edit($id);


        $this->viewState->set('scope.tacheAffectation.tache_id', $id);
        

        $tacheAffectationService =  new TacheAffectationService();
        $tacheAffectations_view_data = $tacheAffectationService->prepareDataForIndexView();
        extract($tacheAffectations_view_data);

        if (request()->ajax()) {
            return view('PkgCreationTache::tache._show', array_merge(compact('itemTache'),$tacheAffectation_compact_value));
        }

        return view('PkgCreationTache::tache.show', array_merge(compact('itemTache'),$tacheAffectation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('tache.edit_' . $id);


        $itemTache = $this->tacheService->edit($id);

        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet_id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $projets = $this->projetService->getAllForSelect($itemTache->projet);
        $phaseEvaluations = $this->phaseEvaluationService->getAllForSelect($itemTache->phaseEvaluation);
        $chapitres = $this->chapitreService->getAllForSelect($itemTache->chapitre);
        $livrables = $this->livrableService->getAllForSelect($itemTache->livrables);


        $this->viewState->set('scope.realisationTache.tache_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_view_data = $realisationTacheService->prepareDataForIndexView();
        extract($realisationTaches_view_data);

        $this->viewState->set('scope.tacheAffectation.tache_id', $id);
        

        $tacheAffectationService =  new TacheAffectationService();
        $tacheAffectations_view_data = $tacheAffectationService->prepareDataForIndexView();
        extract($tacheAffectations_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationTache::tache._edit', array_merge(compact('bulkEdit' , 'itemTache','livrables', 'chapitres', 'phaseEvaluations', 'projets'),$realisationTache_compact_value, $tacheAffectation_compact_value));
        }

        return view('PkgCreationTache::tache.edit', array_merge(compact('bulkEdit' ,'itemTache','livrables', 'chapitres', 'phaseEvaluations', 'projets'),$realisationTache_compact_value, $tacheAffectation_compact_value));


    }
    /**
     */
    public function update(TacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $tache = $this->tacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgCreationTache::tache.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $tache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('taches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgCreationTache::tache.singular')
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
            'tache_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('tache_ids', []);
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
        $form         = new \Modules\PkgCreationTache\App\Requests\TacheRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->tacheService->find($id);
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

        $tache = $this->tacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgCreationTache::tache.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('taches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgCreationTache::tache.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $tache_ids = $request->input('ids', []);
        if (!is_array($tache_ids) || count($tache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($tache_ids as $id) {
            $entity = $this->tacheService->find($id);
            $this->tacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($tache_ids) . ' éléments',
            'modelName' => __('PkgCreationTache::tache.plural')
        ]));
    }

    public function export($format)
    {
        $taches_data = $this->tacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new TacheExport($taches_data,'csv'), 'tache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new TacheExport($taches_data,'xlsx'), 'tache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new TacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('taches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('taches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationTache::tache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTaches()
    {
        $taches = $this->tacheService->all();
        return response()->json($taches);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Tache) par ID, en format JSON.
     */
    public function getTache(Request $request, $id)
    {
        try {
            $tache = $this->tacheService->find($id);
            return response()->json($tache);
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
        $updatedTache = $this->tacheService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedTache],
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
        $tacheRequest = new TacheRequest();
        $fullRules = $tacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:taches,id'];
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
        $itemTache = Tache::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet_id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $data = $this->service->buildFieldMeta($itemTache, $field);
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
        $itemTache = Tache::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet_id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemTache);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemTache, $changes);

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