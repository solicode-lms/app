<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\CritereEvaluationService;
use Modules\PkgCompetences\Services\PhaseEvaluationService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\CritereEvaluationRequest;
use Modules\PkgCompetences\Models\CritereEvaluation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\App\Exports\CritereEvaluationExport;
use Modules\PkgCompetences\App\Imports\CritereEvaluationImport;
use Modules\Core\Services\ContextState;

class BaseCritereEvaluationController extends AdminController
{
    protected $critereEvaluationService;
    protected $phaseEvaluationService;
    protected $uniteApprentissageService;

    public function __construct(CritereEvaluationService $critereEvaluationService, PhaseEvaluationService $phaseEvaluationService, UniteApprentissageService $uniteApprentissageService) {
        parent::__construct();
        $this->service  =  $critereEvaluationService;
        $this->critereEvaluationService = $critereEvaluationService;
        $this->phaseEvaluationService = $phaseEvaluationService;
        $this->uniteApprentissageService = $uniteApprentissageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('critereEvaluation.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('critereEvaluation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $critereEvaluations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'critereEvaluations_search',
                $this->viewState->get("filter.critereEvaluation.critereEvaluations_search")
            )],
            $request->except(['critereEvaluations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->critereEvaluationService->prepareDataForIndexView($critereEvaluations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::critereEvaluation._index', $critereEvaluation_compact_value)->render();
            }else{
                return view($critereEvaluation_partialViewName, $critereEvaluation_compact_value)->render();
            }
        }

        return view('PkgCompetences::critereEvaluation.index', $critereEvaluation_compact_value);
    }
    /**
     */
    public function create() {


        $itemCritereEvaluation = $this->critereEvaluationService->createInstance();
        

        $phaseEvaluations = $this->phaseEvaluationService->all();
        $uniteApprentissages = $this->uniteApprentissageService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::critereEvaluation._fields', compact('bulkEdit' ,'itemCritereEvaluation', 'phaseEvaluations', 'uniteApprentissages'));
        }
        return view('PkgCompetences::critereEvaluation.create', compact('bulkEdit' ,'itemCritereEvaluation', 'phaseEvaluations', 'uniteApprentissages'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $critereEvaluation_ids = $request->input('ids', []);

        if (!is_array($critereEvaluation_ids) || count($critereEvaluation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemCritereEvaluation = $this->critereEvaluationService->find($critereEvaluation_ids[0]);
         
 
        $phaseEvaluations = $this->phaseEvaluationService->getAllForSelect($itemCritereEvaluation->phaseEvaluation);
        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemCritereEvaluation->uniteApprentissage);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemCritereEvaluation = $this->critereEvaluationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::critereEvaluation._fields', compact('bulkEdit', 'critereEvaluation_ids', 'itemCritereEvaluation', 'phaseEvaluations', 'uniteApprentissages'));
        }
        return view('PkgCompetences::critereEvaluation.bulk-edit', compact('bulkEdit', 'critereEvaluation_ids', 'itemCritereEvaluation', 'phaseEvaluations', 'uniteApprentissages'));
    }
    /**
     */
    public function store(CritereEvaluationRequest $request) {
        $validatedData = $request->validated();
        $critereEvaluation = $this->critereEvaluationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' => __('PkgCompetences::critereEvaluation.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $critereEvaluation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('critereEvaluations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' => __('PkgCompetences::critereEvaluation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('critereEvaluation.show_' . $id);

        $itemCritereEvaluation = $this->critereEvaluationService->edit($id);


        if (request()->ajax()) {
            return view('PkgCompetences::critereEvaluation._show', array_merge(compact('itemCritereEvaluation'),));
        }

        return view('PkgCompetences::critereEvaluation.show', array_merge(compact('itemCritereEvaluation'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('critereEvaluation.edit_' . $id);


        $itemCritereEvaluation = $this->critereEvaluationService->edit($id);


        $phaseEvaluations = $this->phaseEvaluationService->getAllForSelect($itemCritereEvaluation->phaseEvaluation);
        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemCritereEvaluation->uniteApprentissage);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::critereEvaluation._fields', array_merge(compact('bulkEdit' , 'itemCritereEvaluation','phaseEvaluations', 'uniteApprentissages'),));
        }

        return view('PkgCompetences::critereEvaluation.edit', array_merge(compact('bulkEdit' ,'itemCritereEvaluation','phaseEvaluations', 'uniteApprentissages'),));


    }
    /**
     */
    public function update(CritereEvaluationRequest $request, string $id) {

        $validatedData = $request->validated();
        $critereEvaluation = $this->critereEvaluationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' =>  __('PkgCompetences::critereEvaluation.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $critereEvaluation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('critereEvaluations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' =>  __('PkgCompetences::critereEvaluation.singular')
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
            'critereEvaluation_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('critereEvaluation_ids', []);
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
        $form         = new \Modules\PkgCompetences\App\Requests\CritereEvaluationRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->critereEvaluationService->find($id);
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

        $critereEvaluation = $this->critereEvaluationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' =>  __('PkgCompetences::critereEvaluation.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('critereEvaluations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' =>  __('PkgCompetences::critereEvaluation.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $critereEvaluation_ids = $request->input('ids', []);
        if (!is_array($critereEvaluation_ids) || count($critereEvaluation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($critereEvaluation_ids as $id) {
            $entity = $this->critereEvaluationService->find($id);
            $this->critereEvaluationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($critereEvaluation_ids) . ' éléments',
            'modelName' => __('PkgCompetences::critereEvaluation.plural')
        ]));
    }

    public function export($format)
    {
        $critereEvaluations_data = $this->critereEvaluationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new CritereEvaluationExport($critereEvaluations_data,'csv'), 'critereEvaluation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new CritereEvaluationExport($critereEvaluations_data,'xlsx'), 'critereEvaluation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new CritereEvaluationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('critereEvaluations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('critereEvaluations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::critereEvaluation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCritereEvaluations()
    {
        $critereEvaluations = $this->critereEvaluationService->all();
        return response()->json($critereEvaluations);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (CritereEvaluation) par ID, en format JSON.
     */
    public function getCritereEvaluation(Request $request, $id)
    {
        try {
            $critereEvaluation = $this->critereEvaluationService->find($id);
            return response()->json($critereEvaluation);
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
        $updatedCritereEvaluation = $this->critereEvaluationService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedCritereEvaluation],
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
        $critereEvaluationRequest = new CritereEvaluationRequest();
        $fullRules = $critereEvaluationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:critere_evaluations,id'];
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
        $itemCritereEvaluation = CritereEvaluation::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemCritereEvaluation, $field);
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
        $itemCritereEvaluation = CritereEvaluation::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemCritereEvaluation);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemCritereEvaluation, $changes);

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