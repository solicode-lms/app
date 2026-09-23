<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Controllers\Base;
use Modules\PkgQcm\Services\QuestionLibService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Modules\PkgQcm\Services\PropositionReponseService;
use Modules\PkgQcm\Services\QuestionQcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgQcm\App\Requests\QuestionLibRequest;
use Modules\PkgQcm\Models\QuestionLib;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\App\Exports\QuestionLibExport;
use Modules\PkgQcm\App\Imports\QuestionLibImport;
use Modules\Core\Services\ContextState;

class BaseQuestionLibController extends AdminController
{
    protected $questionLibService;
    protected $uniteApprentissageService;

    public function __construct(QuestionLibService $questionLibService, UniteApprentissageService $uniteApprentissageService) {
        parent::__construct();
        $this->service  =  $questionLibService;
        $this->questionLibService = $questionLibService;
        $this->uniteApprentissageService = $uniteApprentissageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('questionLib.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('questionLib');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $questionLibs_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'questionLibs_search',
                $this->viewState->get("filter.questionLib.questionLibs_search")
            )],
            $request->except(['questionLibs_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->questionLibService->prepareDataForIndexView($questionLibs_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgQcm::questionLib._index', $questionLib_compact_value)->render();
            }else{
                return view($questionLib_partialViewName, $questionLib_compact_value)->render();
            }
        }

        return view('PkgQcm::questionLib.index', $questionLib_compact_value);
    }
    /**
     */
    public function create() {


        // scopeDataByRole
        $itemQuestionLib = $this->questionLibService->createInstance();
 

        $uniteApprentissages = $this->uniteApprentissageService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgQcm::questionLib._fields', compact('bulkEdit' ,'itemQuestionLib', 'uniteApprentissages'));
        }
        return view('PkgQcm::questionLib.create', compact('bulkEdit' ,'itemQuestionLib', 'uniteApprentissages'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $questionLib_ids = $request->input('ids', []);

        if (!is_array($questionLib_ids) || count($questionLib_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemQuestionLib = $this->questionLibService->find($questionLib_ids[0]);
         
 
        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemQuestionLib->uniteApprentissage);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemQuestionLib = $this->questionLibService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgQcm::questionLib._fields', compact('bulkEdit', 'questionLib_ids', 'itemQuestionLib', 'uniteApprentissages'));
        }
        return view('PkgQcm::questionLib.bulk-edit', compact('bulkEdit', 'questionLib_ids', 'itemQuestionLib', 'uniteApprentissages'));
    }
    /**
     */
    public function store(QuestionLibRequest $request) {
        $validatedData = $request->validated();
        $questionLib = $this->questionLibService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $questionLib,
                'modelName' => __('PkgQcm::questionLib.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $questionLib->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('questionLibs.edit', ['questionLib' => $questionLib->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $questionLib,
                'modelName' => __('PkgQcm::questionLib.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('questionLib.show_' . $id);

        $itemQuestionLib = $this->questionLibService->edit($id);


        $this->viewState->set('scope.propositionReponse.question_lib_id', $id);
        

        $propositionReponseService =  new PropositionReponseService();
        $propositionReponses_view_data = $propositionReponseService->prepareDataForIndexView();
        extract($propositionReponses_view_data);

        $this->viewState->set('scope.questionQcm.question_lib_id', $id);
        

        $questionQcmService =  new QuestionQcmService();
        $questionQcms_view_data = $questionQcmService->prepareDataForIndexView();
        extract($questionQcms_view_data);

        if (request()->ajax()) {
            return view('PkgQcm::questionLib._show', array_merge(compact('itemQuestionLib'),$propositionReponse_compact_value, $questionQcm_compact_value));
        }

        return view('PkgQcm::questionLib.show', array_merge(compact('itemQuestionLib'),$propositionReponse_compact_value, $questionQcm_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('questionLib.edit_' . $id);


        $itemQuestionLib = $this->questionLibService->edit($id);


        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemQuestionLib->uniteApprentissage);


        $this->viewState->set('scope.propositionReponse.question_lib_id', $id);
        

        $propositionReponseService =  new PropositionReponseService();
        $propositionReponses_view_data = $propositionReponseService->prepareDataForIndexView();
        extract($propositionReponses_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgQcm::questionLib._fields', array_merge(compact('bulkEdit' , 'itemQuestionLib','uniteApprentissages'),$propositionReponse_compact_value));
        }

        return view('PkgQcm::questionLib.edit', array_merge(compact('bulkEdit' ,'itemQuestionLib','uniteApprentissages'),$propositionReponse_compact_value));


    }
    /**
     */
    public function update(QuestionLibRequest $request, string $id) {

        $validatedData = $request->validated();
        $questionLib = $this->questionLibService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $questionLib,
                'modelName' =>  __('PkgQcm::questionLib.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $questionLib->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('questionLibs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $questionLib,
                'modelName' =>  __('PkgQcm::questionLib.singular')
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
            'questionLib_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('questionLib_ids', []);
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
        $form         = new \Modules\PkgQcm\App\Requests\QuestionLibRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->questionLibService->find($id);
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

        $questionLib = $this->questionLibService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $questionLib,
                'modelName' =>  __('PkgQcm::questionLib.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('questionLibs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $questionLib,
                'modelName' =>  __('PkgQcm::questionLib.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $questionLib_ids = $request->input('ids', []);
        if (!is_array($questionLib_ids) || count($questionLib_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($questionLib_ids as $id) {
            $entity = $this->questionLibService->find($id);
            $this->questionLibService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($questionLib_ids) . ' éléments',
            'modelName' => __('PkgQcm::questionLib.plural')
        ]));
    }

    public function export($format)
    {
        $questionLibs_data = $this->questionLibService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new QuestionLibExport($questionLibs_data,'csv'), 'questionLib_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new QuestionLibExport($questionLibs_data,'xlsx'), 'questionLib_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new QuestionLibImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('questionLibs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('questionLibs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgQcm::questionLib.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getQuestionLibs()
    {
        $questionLibs = $this->questionLibService->all();
        return response()->json($questionLibs);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (QuestionLib) par ID, en format JSON.
     */
    public function getQuestionLib(Request $request, $id)
    {
        try {
            $questionLib = $this->questionLibService->find($id);
            return response()->json($questionLib);
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
        $updatedQuestionLib = $this->questionLibService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedQuestionLib],
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
        $questionLibRequest = new QuestionLibRequest();
        $fullRules = $questionLibRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:question_libs,id'];
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
        $itemQuestionLib = QuestionLib::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemQuestionLib, $field);
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
        $itemQuestionLib = QuestionLib::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemQuestionLib);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemQuestionLib, $changes);

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