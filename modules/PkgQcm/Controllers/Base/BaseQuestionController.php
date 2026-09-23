<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Controllers\Base;
use Modules\PkgQcm\Services\QuestionService;
use Modules\PkgQcm\Services\QcmService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Modules\PkgQcm\Services\PropositionReponseService;
use Modules\PkgQcm\Services\ReponseQcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgQcm\App\Requests\QuestionRequest;
use Modules\PkgQcm\Models\Question;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\App\Exports\QuestionExport;
use Modules\PkgQcm\App\Imports\QuestionImport;
use Modules\Core\Services\ContextState;

class BaseQuestionController extends AdminController
{
    protected $questionService;
    protected $qcmService;
    protected $uniteApprentissageService;

    public function __construct(QuestionService $questionService, QcmService $qcmService, UniteApprentissageService $uniteApprentissageService) {
        parent::__construct();
        $this->service  =  $questionService;
        $this->questionService = $questionService;
        $this->qcmService = $qcmService;
        $this->uniteApprentissageService = $uniteApprentissageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('question.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('question');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $questions_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'questions_search',
                $this->viewState->get("filter.question.questions_search")
            )],
            $request->except(['questions_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->questionService->prepareDataForIndexView($questions_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgQcm::question._index', $question_compact_value)->render();
            }else{
                return view($question_partialViewName, $question_compact_value)->render();
            }
        }

        return view('PkgQcm::question.index', $question_compact_value);
    }
    /**
     */
    public function create() {


        // scopeDataByRole
        $itemQuestion = $this->questionService->createInstance();
 

        $uniteApprentissages = $this->uniteApprentissageService->all();
        $qcms = $this->qcmService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgQcm::question._fields', compact('bulkEdit' ,'itemQuestion', 'uniteApprentissages', 'qcms'));
        }
        return view('PkgQcm::question.create', compact('bulkEdit' ,'itemQuestion', 'uniteApprentissages', 'qcms'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $question_ids = $request->input('ids', []);

        if (!is_array($question_ids) || count($question_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemQuestion = $this->questionService->find($question_ids[0]);
         
 
        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemQuestion->uniteApprentissage);
        $qcms = $this->qcmService->getAllForSelect($itemQuestion->qcm);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemQuestion = $this->questionService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgQcm::question._fields', compact('bulkEdit', 'question_ids', 'itemQuestion', 'uniteApprentissages', 'qcms'));
        }
        return view('PkgQcm::question.bulk-edit', compact('bulkEdit', 'question_ids', 'itemQuestion', 'uniteApprentissages', 'qcms'));
    }
    /**
     */
    public function store(QuestionRequest $request) {
        $validatedData = $request->validated();
        $question = $this->questionService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $question,
                'modelName' => __('PkgQcm::question.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $question->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('questions.edit', ['question' => $question->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $question,
                'modelName' => __('PkgQcm::question.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('question.show_' . $id);

        $itemQuestion = $this->questionService->edit($id);


        $this->viewState->set('scope.propositionReponse.question_lib_id', $id);
        

        $propositionReponseService =  new PropositionReponseService();
        $propositionReponses_view_data = $propositionReponseService->prepareDataForIndexView();
        extract($propositionReponses_view_data);

        $this->viewState->set('scope.reponseQcm.question_id', $id);
        

        $reponseQcmService =  new ReponseQcmService();
        $reponseQcms_view_data = $reponseQcmService->prepareDataForIndexView();
        extract($reponseQcms_view_data);

        if (request()->ajax()) {
            return view('PkgQcm::question._show', array_merge(compact('itemQuestion'),$propositionReponse_compact_value, $reponseQcm_compact_value));
        }

        return view('PkgQcm::question.show', array_merge(compact('itemQuestion'),$propositionReponse_compact_value, $reponseQcm_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('question.edit_' . $id);


        $itemQuestion = $this->questionService->edit($id);


        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemQuestion->uniteApprentissage);
        $qcms = $this->qcmService->getAllForSelect($itemQuestion->qcm);


        $this->viewState->set('scope.propositionReponse.question_lib_id', $id);
        

        $propositionReponseService =  new PropositionReponseService();
        $propositionReponses_view_data = $propositionReponseService->prepareDataForIndexView();
        extract($propositionReponses_view_data);

        $this->viewState->set('scope.reponseQcm.question_id', $id);
        

        $reponseQcmService =  new ReponseQcmService();
        $reponseQcms_view_data = $reponseQcmService->prepareDataForIndexView();
        extract($reponseQcms_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgQcm::question._edit', array_merge(compact('bulkEdit' , 'itemQuestion','uniteApprentissages', 'qcms'),$propositionReponse_compact_value, $reponseQcm_compact_value));
        }

        return view('PkgQcm::question.edit', array_merge(compact('bulkEdit' ,'itemQuestion','uniteApprentissages', 'qcms'),$propositionReponse_compact_value, $reponseQcm_compact_value));


    }
    /**
     */
    public function update(QuestionRequest $request, string $id) {

        $validatedData = $request->validated();
        $question = $this->questionService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $question,
                'modelName' =>  __('PkgQcm::question.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $question->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('questions.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $question,
                'modelName' =>  __('PkgQcm::question.singular')
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
            'question_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('question_ids', []);
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
        $form         = new \Modules\PkgQcm\App\Requests\QuestionRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->questionService->find($id);
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

        $question = $this->questionService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $question,
                'modelName' =>  __('PkgQcm::question.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('questions.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $question,
                'modelName' =>  __('PkgQcm::question.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $question_ids = $request->input('ids', []);
        if (!is_array($question_ids) || count($question_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($question_ids as $id) {
            $entity = $this->questionService->find($id);
            $this->questionService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($question_ids) . ' éléments',
            'modelName' => __('PkgQcm::question.plural')
        ]));
    }

    public function export($format)
    {
        $questions_data = $this->questionService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new QuestionExport($questions_data,'csv'), 'question_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new QuestionExport($questions_data,'xlsx'), 'question_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new QuestionImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('questions.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('questions.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgQcm::question.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getQuestions()
    {
        $questions = $this->questionService->all();
        return response()->json($questions);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Question) par ID, en format JSON.
     */
    public function getQuestion(Request $request, $id)
    {
        try {
            $question = $this->questionService->find($id);
            return response()->json($question);
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
        $updatedQuestion = $this->questionService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedQuestion],
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
        $questionRequest = new QuestionRequest();
        $fullRules = $questionRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:questions,id'];
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
        $itemQuestion = Question::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemQuestion, $field);
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
        $itemQuestion = Question::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemQuestion);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemQuestion, $changes);

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