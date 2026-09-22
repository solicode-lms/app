<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Controllers\Base;
use Modules\PkgQcm\Services\QuestionQcmService;
use Modules\PkgQcm\Services\QcmService;
use Modules\PkgQcm\Services\QuestionLibService;
use Modules\PkgQcm\Services\ReponseQcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgQcm\App\Requests\QuestionQcmRequest;
use Modules\PkgQcm\Models\QuestionQcm;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\App\Exports\QuestionQcmExport;
use Modules\PkgQcm\App\Imports\QuestionQcmImport;
use Modules\Core\Services\ContextState;

class BaseQuestionQcmController extends AdminController
{
    protected $questionQcmService;
    protected $qcmService;
    protected $questionLibService;

    public function __construct(QuestionQcmService $questionQcmService, QcmService $qcmService, QuestionLibService $questionLibService) {
        parent::__construct();
        $this->service  =  $questionQcmService;
        $this->questionQcmService = $questionQcmService;
        $this->qcmService = $qcmService;
        $this->questionLibService = $questionLibService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('questionQcm.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('questionQcm');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $questionQcms_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'questionQcms_search',
                $this->viewState->get("filter.questionQcm.questionQcms_search")
            )],
            $request->except(['questionQcms_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->questionQcmService->prepareDataForIndexView($questionQcms_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgQcm::questionQcm._index', $questionQcm_compact_value)->render();
            }else{
                return view($questionQcm_partialViewName, $questionQcm_compact_value)->render();
            }
        }

        return view('PkgQcm::questionQcm.index', $questionQcm_compact_value);
    }
    /**
     */
    public function create() {


        // scopeDataByRole
        $itemQuestionQcm = $this->questionQcmService->createInstance();
 

        $qcms = $this->qcmService->all();
        $questionLibs = $this->questionLibService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgQcm::questionQcm._fields', compact('bulkEdit' ,'itemQuestionQcm', 'qcms', 'questionLibs'));
        }
        return view('PkgQcm::questionQcm.create', compact('bulkEdit' ,'itemQuestionQcm', 'qcms', 'questionLibs'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $questionQcm_ids = $request->input('ids', []);

        if (!is_array($questionQcm_ids) || count($questionQcm_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemQuestionQcm = $this->questionQcmService->find($questionQcm_ids[0]);
         
 
        $qcms = $this->qcmService->getAllForSelect($itemQuestionQcm->qcm);
        $questionLibs = $this->questionLibService->getAllForSelect($itemQuestionQcm->questionLib);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemQuestionQcm = $this->questionQcmService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgQcm::questionQcm._fields', compact('bulkEdit', 'questionQcm_ids', 'itemQuestionQcm', 'qcms', 'questionLibs'));
        }
        return view('PkgQcm::questionQcm.bulk-edit', compact('bulkEdit', 'questionQcm_ids', 'itemQuestionQcm', 'qcms', 'questionLibs'));
    }
    /**
     */
    public function store(QuestionQcmRequest $request) {
        $validatedData = $request->validated();
        $questionQcm = $this->questionQcmService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $questionQcm,
                'modelName' => __('PkgQcm::questionQcm.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $questionQcm->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('questionQcms.edit', ['questionQcm' => $questionQcm->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $questionQcm,
                'modelName' => __('PkgQcm::questionQcm.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('questionQcm.show_' . $id);

        $itemQuestionQcm = $this->questionQcmService->edit($id);


        $this->viewState->set('scope.reponseQcm.question_qcm_id', $id);
        

        $reponseQcmService =  new ReponseQcmService();
        $reponseQcms_view_data = $reponseQcmService->prepareDataForIndexView();
        extract($reponseQcms_view_data);

        if (request()->ajax()) {
            return view('PkgQcm::questionQcm._show', array_merge(compact('itemQuestionQcm'),$reponseQcm_compact_value));
        }

        return view('PkgQcm::questionQcm.show', array_merge(compact('itemQuestionQcm'),$reponseQcm_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('questionQcm.edit_' . $id);


        $itemQuestionQcm = $this->questionQcmService->edit($id);


        $qcms = $this->qcmService->getAllForSelect($itemQuestionQcm->qcm);
        $questionLibs = $this->questionLibService->getAllForSelect($itemQuestionQcm->questionLib);


        $this->viewState->set('scope.reponseQcm.question_qcm_id', $id);
        

        $reponseQcmService =  new ReponseQcmService();
        $reponseQcms_view_data = $reponseQcmService->prepareDataForIndexView();
        extract($reponseQcms_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgQcm::questionQcm._edit', array_merge(compact('bulkEdit' , 'itemQuestionQcm','qcms', 'questionLibs'),$reponseQcm_compact_value));
        }

        return view('PkgQcm::questionQcm.edit', array_merge(compact('bulkEdit' ,'itemQuestionQcm','qcms', 'questionLibs'),$reponseQcm_compact_value));


    }
    /**
     */
    public function update(QuestionQcmRequest $request, string $id) {

        $validatedData = $request->validated();
        $questionQcm = $this->questionQcmService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $questionQcm,
                'modelName' =>  __('PkgQcm::questionQcm.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $questionQcm->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('questionQcms.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $questionQcm,
                'modelName' =>  __('PkgQcm::questionQcm.singular')
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
            'questionQcm_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('questionQcm_ids', []);
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
        $form         = new \Modules\PkgQcm\App\Requests\QuestionQcmRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->questionQcmService->find($id);
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

        $questionQcm = $this->questionQcmService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $questionQcm,
                'modelName' =>  __('PkgQcm::questionQcm.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('questionQcms.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $questionQcm,
                'modelName' =>  __('PkgQcm::questionQcm.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $questionQcm_ids = $request->input('ids', []);
        if (!is_array($questionQcm_ids) || count($questionQcm_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($questionQcm_ids as $id) {
            $entity = $this->questionQcmService->find($id);
            $this->questionQcmService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($questionQcm_ids) . ' éléments',
            'modelName' => __('PkgQcm::questionQcm.plural')
        ]));
    }

    public function export($format)
    {
        $questionQcms_data = $this->questionQcmService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new QuestionQcmExport($questionQcms_data,'csv'), 'questionQcm_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new QuestionQcmExport($questionQcms_data,'xlsx'), 'questionQcm_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new QuestionQcmImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('questionQcms.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('questionQcms.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgQcm::questionQcm.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getQuestionQcms()
    {
        $questionQcms = $this->questionQcmService->all();
        return response()->json($questionQcms);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (QuestionQcm) par ID, en format JSON.
     */
    public function getQuestionQcm(Request $request, $id)
    {
        try {
            $questionQcm = $this->questionQcmService->find($id);
            return response()->json($questionQcm);
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
        $updatedQuestionQcm = $this->questionQcmService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedQuestionQcm],
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
        $questionQcmRequest = new QuestionQcmRequest();
        $fullRules = $questionQcmRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:question_qcms,id'];
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
        $itemQuestionQcm = QuestionQcm::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemQuestionQcm, $field);
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
        $itemQuestionQcm = QuestionQcm::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemQuestionQcm);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemQuestionQcm, $changes);

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