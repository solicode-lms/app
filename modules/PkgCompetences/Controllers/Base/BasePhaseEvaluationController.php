<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\PhaseEvaluationService;
use Modules\PkgCompetences\Services\CritereEvaluationService;
use Modules\PkgCreationTache\Services\TacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\PhaseEvaluationRequest;
use Modules\PkgCompetences\Models\PhaseEvaluation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\App\Exports\PhaseEvaluationExport;
use Modules\PkgCompetences\App\Imports\PhaseEvaluationImport;
use Modules\Core\Services\ContextState;

class BasePhaseEvaluationController extends AdminController
{
    protected $phaseEvaluationService;

    public function __construct(PhaseEvaluationService $phaseEvaluationService) {
        parent::__construct();
        $this->service  =  $phaseEvaluationService;
        $this->phaseEvaluationService = $phaseEvaluationService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('phaseEvaluation.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('phaseEvaluation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $phaseEvaluations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'phaseEvaluations_search',
                $this->viewState->get("filter.phaseEvaluation.phaseEvaluations_search")
            )],
            $request->except(['phaseEvaluations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->phaseEvaluationService->prepareDataForIndexView($phaseEvaluations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::phaseEvaluation._index', $phaseEvaluation_compact_value)->render();
            }else{
                return view($phaseEvaluation_partialViewName, $phaseEvaluation_compact_value)->render();
            }
        }

        return view('PkgCompetences::phaseEvaluation.index', $phaseEvaluation_compact_value);
    }
    /**
     */
    public function create() {


        $itemPhaseEvaluation = $this->phaseEvaluationService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::phaseEvaluation._fields', compact('bulkEdit' ,'itemPhaseEvaluation'));
        }
        return view('PkgCompetences::phaseEvaluation.create', compact('bulkEdit' ,'itemPhaseEvaluation'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $phaseEvaluation_ids = $request->input('ids', []);

        if (!is_array($phaseEvaluation_ids) || count($phaseEvaluation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemPhaseEvaluation = $this->phaseEvaluationService->find($phaseEvaluation_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemPhaseEvaluation = $this->phaseEvaluationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::phaseEvaluation._fields', compact('bulkEdit', 'phaseEvaluation_ids', 'itemPhaseEvaluation'));
        }
        return view('PkgCompetences::phaseEvaluation.bulk-edit', compact('bulkEdit', 'phaseEvaluation_ids', 'itemPhaseEvaluation'));
    }
    /**
     */
    public function store(PhaseEvaluationRequest $request) {
        $validatedData = $request->validated();
        $phaseEvaluation = $this->phaseEvaluationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $phaseEvaluation,
                'modelName' => __('PkgCompetences::phaseEvaluation.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $phaseEvaluation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('phaseEvaluations.edit', ['phaseEvaluation' => $phaseEvaluation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $phaseEvaluation,
                'modelName' => __('PkgCompetences::phaseEvaluation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('phaseEvaluation.show_' . $id);

        $itemPhaseEvaluation = $this->phaseEvaluationService->edit($id);


        $this->viewState->set('scope.critereEvaluation.phase_evaluation_id', $id);
        

        $critereEvaluationService =  new CritereEvaluationService();
        $critereEvaluations_view_data = $critereEvaluationService->prepareDataForIndexView();
        extract($critereEvaluations_view_data);

        $this->viewState->set('scope.tache.phase_evaluation_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        if (request()->ajax()) {
            return view('PkgCompetences::phaseEvaluation._show', array_merge(compact('itemPhaseEvaluation'),$critereEvaluation_compact_value, $tache_compact_value));
        }

        return view('PkgCompetences::phaseEvaluation.show', array_merge(compact('itemPhaseEvaluation'),$critereEvaluation_compact_value, $tache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('phaseEvaluation.edit_' . $id);


        $itemPhaseEvaluation = $this->phaseEvaluationService->edit($id);




        $this->viewState->set('scope.critereEvaluation.phase_evaluation_id', $id);
        

        $critereEvaluationService =  new CritereEvaluationService();
        $critereEvaluations_view_data = $critereEvaluationService->prepareDataForIndexView();
        extract($critereEvaluations_view_data);

        $this->viewState->set('scope.tache.phase_evaluation_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::phaseEvaluation._edit', array_merge(compact('bulkEdit' , 'itemPhaseEvaluation',),$critereEvaluation_compact_value, $tache_compact_value));
        }

        return view('PkgCompetences::phaseEvaluation.edit', array_merge(compact('bulkEdit' ,'itemPhaseEvaluation',),$critereEvaluation_compact_value, $tache_compact_value));


    }
    /**
     */
    public function update(PhaseEvaluationRequest $request, string $id) {

        $validatedData = $request->validated();
        $phaseEvaluation = $this->phaseEvaluationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $phaseEvaluation,
                'modelName' =>  __('PkgCompetences::phaseEvaluation.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $phaseEvaluation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('phaseEvaluations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $phaseEvaluation,
                'modelName' =>  __('PkgCompetences::phaseEvaluation.singular')
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
            'phaseEvaluation_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('phaseEvaluation_ids', []);
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
        $form         = new \Modules\PkgCompetences\App\Requests\PhaseEvaluationRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->phaseEvaluationService->find($id);
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

        $phaseEvaluation = $this->phaseEvaluationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $phaseEvaluation,
                'modelName' =>  __('PkgCompetences::phaseEvaluation.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('phaseEvaluations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $phaseEvaluation,
                'modelName' =>  __('PkgCompetences::phaseEvaluation.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $phaseEvaluation_ids = $request->input('ids', []);
        if (!is_array($phaseEvaluation_ids) || count($phaseEvaluation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($phaseEvaluation_ids as $id) {
            $entity = $this->phaseEvaluationService->find($id);
            $this->phaseEvaluationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($phaseEvaluation_ids) . ' éléments',
            'modelName' => __('PkgCompetences::phaseEvaluation.plural')
        ]));
    }

    public function export($format)
    {
        $phaseEvaluations_data = $this->phaseEvaluationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new PhaseEvaluationExport($phaseEvaluations_data,'csv'), 'phaseEvaluation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new PhaseEvaluationExport($phaseEvaluations_data,'xlsx'), 'phaseEvaluation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new PhaseEvaluationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('phaseEvaluations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('phaseEvaluations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::phaseEvaluation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getPhaseEvaluations()
    {
        $phaseEvaluations = $this->phaseEvaluationService->all();
        return response()->json($phaseEvaluations);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (PhaseEvaluation) par ID, en format JSON.
     */
    public function getPhaseEvaluation(Request $request, $id)
    {
        try {
            $phaseEvaluation = $this->phaseEvaluationService->find($id);
            return response()->json($phaseEvaluation);
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
        $updatedPhaseEvaluation = $this->phaseEvaluationService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedPhaseEvaluation],
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
        $phaseEvaluationRequest = new PhaseEvaluationRequest();
        $fullRules = $phaseEvaluationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:phase_evaluations,id'];
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
        $itemPhaseEvaluation = PhaseEvaluation::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemPhaseEvaluation, $field);
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
        $itemPhaseEvaluation = PhaseEvaluation::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemPhaseEvaluation);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemPhaseEvaluation, $changes);

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