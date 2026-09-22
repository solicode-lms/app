<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Controllers\Base;
use Modules\PkgQcm\Services\PropositionReponseService;
use Modules\PkgQcm\Services\ReponseQcmService;
use Modules\PkgQcm\Services\QuestionLibService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgQcm\App\Requests\PropositionReponseRequest;
use Modules\PkgQcm\Models\PropositionReponse;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\App\Exports\PropositionReponseExport;
use Modules\PkgQcm\App\Imports\PropositionReponseImport;
use Modules\Core\Services\ContextState;

class BasePropositionReponseController extends AdminController
{
    protected $propositionReponseService;
    protected $reponseQcmService;
    protected $questionLibService;

    public function __construct(PropositionReponseService $propositionReponseService, ReponseQcmService $reponseQcmService, QuestionLibService $questionLibService) {
        parent::__construct();
        $this->service  =  $propositionReponseService;
        $this->propositionReponseService = $propositionReponseService;
        $this->reponseQcmService = $reponseQcmService;
        $this->questionLibService = $questionLibService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('propositionReponse.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('propositionReponse');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $propositionReponses_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'propositionReponses_search',
                $this->viewState->get("filter.propositionReponse.propositionReponses_search")
            )],
            $request->except(['propositionReponses_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->propositionReponseService->prepareDataForIndexView($propositionReponses_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgQcm::propositionReponse._index', $propositionReponse_compact_value)->render();
            }else{
                return view($propositionReponse_partialViewName, $propositionReponse_compact_value)->render();
            }
        }

        return view('PkgQcm::propositionReponse.index', $propositionReponse_compact_value);
    }
    /**
     */
    public function create() {


        // scopeDataByRole
        $itemPropositionReponse = $this->propositionReponseService->createInstance();
 

        $questionLibs = $this->questionLibService->all();
        $reponseQcms = $this->reponseQcmService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgQcm::propositionReponse._fields', compact('bulkEdit' ,'itemPropositionReponse', 'questionLibs', 'reponseQcms'));
        }
        return view('PkgQcm::propositionReponse.create', compact('bulkEdit' ,'itemPropositionReponse', 'questionLibs', 'reponseQcms'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $propositionReponse_ids = $request->input('ids', []);

        if (!is_array($propositionReponse_ids) || count($propositionReponse_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemPropositionReponse = $this->propositionReponseService->find($propositionReponse_ids[0]);
         
 
        $questionLibs = $this->questionLibService->getAllForSelect($itemPropositionReponse->questionLib);
        $reponseQcms = $this->reponseQcmService->getAllForSelect($itemPropositionReponse->reponseQcms);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemPropositionReponse = $this->propositionReponseService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgQcm::propositionReponse._fields', compact('bulkEdit', 'propositionReponse_ids', 'itemPropositionReponse', 'questionLibs', 'reponseQcms'));
        }
        return view('PkgQcm::propositionReponse.bulk-edit', compact('bulkEdit', 'propositionReponse_ids', 'itemPropositionReponse', 'questionLibs', 'reponseQcms'));
    }
    /**
     */
    public function store(PropositionReponseRequest $request) {
        $validatedData = $request->validated();
        $propositionReponse = $this->propositionReponseService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $propositionReponse,
                'modelName' => __('PkgQcm::propositionReponse.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $propositionReponse->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('propositionReponses.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $propositionReponse,
                'modelName' => __('PkgQcm::propositionReponse.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('propositionReponse.show_' . $id);

        $itemPropositionReponse = $this->propositionReponseService->edit($id);


        if (request()->ajax()) {
            return view('PkgQcm::propositionReponse._show', array_merge(compact('itemPropositionReponse'),));
        }

        return view('PkgQcm::propositionReponse.show', array_merge(compact('itemPropositionReponse'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('propositionReponse.edit_' . $id);


        $itemPropositionReponse = $this->propositionReponseService->edit($id);


        $questionLibs = $this->questionLibService->getAllForSelect($itemPropositionReponse->questionLib);
        $reponseQcms = $this->reponseQcmService->getAllForSelect($itemPropositionReponse->reponseQcms);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgQcm::propositionReponse._fields', array_merge(compact('bulkEdit' , 'itemPropositionReponse','questionLibs', 'reponseQcms'),));
        }

        return view('PkgQcm::propositionReponse.edit', array_merge(compact('bulkEdit' ,'itemPropositionReponse','questionLibs', 'reponseQcms'),));


    }
    /**
     */
    public function update(PropositionReponseRequest $request, string $id) {

        $validatedData = $request->validated();
        $propositionReponse = $this->propositionReponseService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $propositionReponse,
                'modelName' =>  __('PkgQcm::propositionReponse.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $propositionReponse->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('propositionReponses.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $propositionReponse,
                'modelName' =>  __('PkgQcm::propositionReponse.singular')
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
            'propositionReponse_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('propositionReponse_ids', []);
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
        $form         = new \Modules\PkgQcm\App\Requests\PropositionReponseRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->propositionReponseService->find($id);
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

        $propositionReponse = $this->propositionReponseService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $propositionReponse,
                'modelName' =>  __('PkgQcm::propositionReponse.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('propositionReponses.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $propositionReponse,
                'modelName' =>  __('PkgQcm::propositionReponse.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $propositionReponse_ids = $request->input('ids', []);
        if (!is_array($propositionReponse_ids) || count($propositionReponse_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($propositionReponse_ids as $id) {
            $entity = $this->propositionReponseService->find($id);
            $this->propositionReponseService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($propositionReponse_ids) . ' éléments',
            'modelName' => __('PkgQcm::propositionReponse.plural')
        ]));
    }

    public function export($format)
    {
        $propositionReponses_data = $this->propositionReponseService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new PropositionReponseExport($propositionReponses_data,'csv'), 'propositionReponse_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new PropositionReponseExport($propositionReponses_data,'xlsx'), 'propositionReponse_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new PropositionReponseImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('propositionReponses.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('propositionReponses.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgQcm::propositionReponse.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getPropositionReponses()
    {
        $propositionReponses = $this->propositionReponseService->all();
        return response()->json($propositionReponses);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (PropositionReponse) par ID, en format JSON.
     */
    public function getPropositionReponse(Request $request, $id)
    {
        try {
            $propositionReponse = $this->propositionReponseService->find($id);
            return response()->json($propositionReponse);
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
        $updatedPropositionReponse = $this->propositionReponseService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedPropositionReponse],
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
        $propositionReponseRequest = new PropositionReponseRequest();
        $fullRules = $propositionReponseRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:proposition_reponses,id'];
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
        $itemPropositionReponse = PropositionReponse::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemPropositionReponse, $field);
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
        $itemPropositionReponse = PropositionReponse::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemPropositionReponse);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemPropositionReponse, $changes);

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