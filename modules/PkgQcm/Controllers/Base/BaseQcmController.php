<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Controllers\Base;
use Modules\PkgQcm\Services\QcmService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgQcm\Services\AffectationQcmProjetService;
use Modules\PkgQcm\Services\QuestionService;
use Modules\PkgQcm\Services\RealisationQcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgQcm\App\Requests\QcmRequest;
use Modules\PkgQcm\Models\Qcm;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\App\Exports\QcmExport;
use Modules\PkgQcm\App\Imports\QcmImport;
use Modules\Core\Services\ContextState;

class BaseQcmController extends AdminController
{
    protected $qcmService;
    protected $formateurService;

    public function __construct(QcmService $qcmService, FormateurService $formateurService) {
        parent::__construct();
        $this->service  =  $qcmService;
        $this->qcmService = $qcmService;
        $this->formateurService = $formateurService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('qcm.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('qcm');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.qcm.formateur_id') == null){
           $this->viewState->init('scope.qcm.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $qcms_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'qcms_search',
                $this->viewState->get("filter.qcm.qcms_search")
            )],
            $request->except(['qcms_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->qcmService->prepareDataForIndexView($qcms_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgQcm::qcm._index', $qcm_compact_value)->render();
            }else{
                return view($qcm_partialViewName, $qcm_compact_value)->render();
            }
        }

        return view('PkgQcm::qcm.index', $qcm_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.qcm.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        // scopeDataByRole
        $itemQcm = $this->qcmService->createInstance();
 

        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgQcm::qcm._fields', compact('bulkEdit' ,'itemQcm', 'formateurs'));
        }
        return view('PkgQcm::qcm.create', compact('bulkEdit' ,'itemQcm', 'formateurs'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $qcm_ids = $request->input('ids', []);

        if (!is_array($qcm_ids) || count($qcm_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.qcm.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
         $itemQcm = $this->qcmService->find($qcm_ids[0]);
         
 
        $formateurs = $this->formateurService->getAllForSelect($itemQcm->formateur);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemQcm = $this->qcmService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgQcm::qcm._fields', compact('bulkEdit', 'qcm_ids', 'itemQcm', 'formateurs'));
        }
        return view('PkgQcm::qcm.bulk-edit', compact('bulkEdit', 'qcm_ids', 'itemQcm', 'formateurs'));
    }
    /**
     */
    public function store(QcmRequest $request) {
        $validatedData = $request->validated();
        $qcm = $this->qcmService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $qcm,
                'modelName' => __('PkgQcm::qcm.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $qcm->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('qcms.edit', ['qcm' => $qcm->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $qcm,
                'modelName' => __('PkgQcm::qcm.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('qcm.show_' . $id);

        $itemQcm = $this->qcmService->edit($id);
        $this->authorize('view', $itemQcm);


        $this->viewState->set('scope.affectationQcmProjet.qcm_id', $id);
        

        $affectationQcmProjetService =  new AffectationQcmProjetService();
        $affectationQcmProjets_view_data = $affectationQcmProjetService->prepareDataForIndexView();
        extract($affectationQcmProjets_view_data);

        $this->viewState->set('scope.question.qcm_id', $id);
        

        $questionService =  new QuestionService();
        $questions_view_data = $questionService->prepareDataForIndexView();
        extract($questions_view_data);

        $this->viewState->set('scope.realisationQcm.qcm_id', $id);
        

        $realisationQcmService =  new RealisationQcmService();
        $realisationQcms_view_data = $realisationQcmService->prepareDataForIndexView();
        extract($realisationQcms_view_data);

        if (request()->ajax()) {
            return view('PkgQcm::qcm._show', array_merge(compact('itemQcm'),$affectationQcmProjet_compact_value, $question_compact_value, $realisationQcm_compact_value));
        }

        return view('PkgQcm::qcm.show', array_merge(compact('itemQcm'),$affectationQcmProjet_compact_value, $question_compact_value, $realisationQcm_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('qcm.edit_' . $id);


        $itemQcm = $this->qcmService->edit($id);
        $this->authorize('edit', $itemQcm);


        $formateurs = $this->formateurService->getAllForSelect($itemQcm->formateur);


        $this->viewState->set('scope.affectationQcmProjet.qcm_id', $id);
        

        $affectationQcmProjetService =  new AffectationQcmProjetService();
        $affectationQcmProjets_view_data = $affectationQcmProjetService->prepareDataForIndexView();
        extract($affectationQcmProjets_view_data);

        $this->viewState->set('scope.question.qcm_id', $id);
        

        $questionService =  new QuestionService();
        $questions_view_data = $questionService->prepareDataForIndexView();
        extract($questions_view_data);

        $this->viewState->set('scope.realisationQcm.qcm_id', $id);
        

        $realisationQcmService =  new RealisationQcmService();
        $realisationQcms_view_data = $realisationQcmService->prepareDataForIndexView();
        extract($realisationQcms_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgQcm::qcm._edit', array_merge(compact('bulkEdit' , 'itemQcm','formateurs'),$affectationQcmProjet_compact_value, $question_compact_value, $realisationQcm_compact_value));
        }

        return view('PkgQcm::qcm.edit', array_merge(compact('bulkEdit' ,'itemQcm','formateurs'),$affectationQcmProjet_compact_value, $question_compact_value, $realisationQcm_compact_value));


    }
    /**
     */
    public function update(QcmRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $qcm = $this->qcmService->find($id);
        $this->authorize('update', $qcm);

        $validatedData = $request->validated();
        $qcm = $this->qcmService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $qcm,
                'modelName' =>  __('PkgQcm::qcm.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $qcm->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('qcms.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $qcm,
                'modelName' =>  __('PkgQcm::qcm.singular')
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
            'qcm_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('qcm_ids', []);
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
        $form         = new \Modules\PkgQcm\App\Requests\QcmRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->qcmService->find($id);
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
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $qcm = $this->qcmService->find($id);
        $this->authorize('delete', $qcm);

        $qcm = $this->qcmService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $qcm,
                'modelName' =>  __('PkgQcm::qcm.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('qcms.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $qcm,
                'modelName' =>  __('PkgQcm::qcm.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $qcm_ids = $request->input('ids', []);
        if (!is_array($qcm_ids) || count($qcm_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($qcm_ids as $id) {
            $entity = $this->qcmService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $qcm = $this->qcmService->find($id);
            $this->authorize('delete', $qcm);
            $this->qcmService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($qcm_ids) . ' éléments',
            'modelName' => __('PkgQcm::qcm.plural')
        ]));
    }

    public function export($format)
    {
        $qcms_data = $this->qcmService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new QcmExport($qcms_data,'csv'), 'qcm_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new QcmExport($qcms_data,'xlsx'), 'qcm_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new QcmImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('qcms.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('qcms.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgQcm::qcm.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getQcms()
    {
        $qcms = $this->qcmService->all();
        return response()->json($qcms);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Qcm) par ID, en format JSON.
     */
    public function getQcm(Request $request, $id)
    {
        try {
            $qcm = $this->qcmService->find($id);
            return response()->json($qcm);
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
        $updatedQcm = $this->qcmService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedQcm],
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
        $qcmRequest = new QcmRequest();
        $fullRules = $qcmRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:qcms,id'];
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
        $itemQcm = Qcm::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemQcm, $field);
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
        $itemQcm = Qcm::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemQcm);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemQcm, $changes);

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