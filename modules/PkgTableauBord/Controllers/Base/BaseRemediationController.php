<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgTableauBord\Controllers\Base;
use Modules\PkgTableauBord\Services\RemediationService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgTableauBord\App\Requests\RemediationRequest;
use Modules\PkgTableauBord\Models\Remediation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgTableauBord\App\Exports\RemediationExport;
use Modules\PkgTableauBord\App\Imports\RemediationImport;
use Modules\Core\Services\ContextState;

class BaseRemediationController extends AdminController
{
    protected $remediationService;
    protected $formateurService;
    protected $realisationTacheService;

    public function __construct(RemediationService $remediationService, FormateurService $formateurService, RealisationTacheService $realisationTacheService) {
        parent::__construct();
        $this->service  =  $remediationService;
        $this->remediationService = $remediationService;
        $this->formateurService = $formateurService;
        $this->realisationTacheService = $realisationTacheService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('remediation.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('remediation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $remediations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'remediations_search',
                $this->viewState->get("filter.remediation.remediations_search")
            )],
            $request->except(['remediations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->remediationService->prepareDataForIndexView($remediations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgTableauBord::remediation._index', $remediation_compact_value)->render();
            }else{
                return view($remediation_partialViewName, $remediation_compact_value)->render();
            }
        }

        return view('PkgTableauBord::remediation.index', $remediation_compact_value);
    }
    /**
     */
    protected function dataForCreateView() {

        // scopeDataByRole
        $itemRemediation = $this->remediationService->createInstance();


        $realisationTaches = $this->realisationTacheService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        return compact('bulkEdit' ,'itemRemediation', 'realisationTaches', 'formateurs');

    }
    /**
     */
    public function create() {
        $viewData = $this->dataForCreateView();

        if (request()->ajax()) {
            return view('PkgTableauBord::remediation._fields', $viewData);
        }

        return view('PkgTableauBord::remediation.create', $viewData);
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $remediation_ids = $request->input('ids', []);

        if (!is_array($remediation_ids) || count($remediation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRemediation = $this->remediationService->find($remediation_ids[0]);
         
 
        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemRemediation->realisationTache);
        $formateurs = $this->formateurService->getAllForSelect($itemRemediation->formateur);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRemediation = $this->remediationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgTableauBord::remediation._fields', compact('bulkEdit', 'remediation_ids', 'itemRemediation', 'realisationTaches', 'formateurs'));
        }
        return view('PkgTableauBord::remediation.bulk-edit', compact('bulkEdit', 'remediation_ids', 'itemRemediation', 'realisationTaches', 'formateurs'));
    }
    /**
     */
    public function store(RemediationRequest $request) {
        $validatedData = $request->validated();
        $remediation = $this->remediationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $remediation,
                'modelName' => __('PkgTableauBord::remediation.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $remediation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('remediations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $remediation,
                'modelName' => __('PkgTableauBord::remediation.singular')
            ])
        );
    }
    /**
     */
    protected function dataForShowView(string $id) {
        $this->viewState->setContextKey('remediation.show_' . $id);

        $itemRemediation = $this->remediationService->edit($id);


        return array_merge(compact('itemRemediation'),);

    }
    /**
     */
    public function show(string $id) {
        $viewData = $this->dataForShowView($id);

        if (request()->ajax()) {
            return view('PkgTableauBord::remediation._show', $viewData);
        }

        return view('PkgTableauBord::remediation.show', $viewData);

    }
    /**
     */
    protected function dataForEditView(string $id) {

        $this->viewState->setContextKey('remediation.edit_' . $id);


        $itemRemediation = $this->remediationService->edit($id);


        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemRemediation->realisationTache);
        $formateurs = $this->formateurService->getAllForSelect($itemRemediation->formateur);


        $bulkEdit = false;

        $viewData = array_merge(compact('bulkEdit' , 'itemRemediation','realisationTaches', 'formateurs'),);

        return $viewData;

    }
    /**
     */
    public function edit(string $id) {
        $viewData = $this->dataForEditView($id);

        if (request()->ajax()) {
            return view('PkgTableauBord::remediation._fields', $viewData);
        }

        return view('PkgTableauBord::remediation.edit', $viewData);

    }
    /**
     */
    public function update(RemediationRequest $request, string $id) {

        $validatedData = $request->validated();
        $remediation = $this->remediationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $remediation,
                'modelName' =>  __('PkgTableauBord::remediation.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $remediation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('remediations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $remediation,
                'modelName' =>  __('PkgTableauBord::remediation.singular')
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
            'remediation_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('remediation_ids', []);
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
        $form         = new \Modules\PkgTableauBord\App\Requests\RemediationRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->remediationService->find($id);
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

        $remediation = $this->remediationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $remediation,
                'modelName' =>  __('PkgTableauBord::remediation.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('remediations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $remediation,
                'modelName' =>  __('PkgTableauBord::remediation.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $remediation_ids = $request->input('ids', []);
        if (!is_array($remediation_ids) || count($remediation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($remediation_ids as $id) {
            $entity = $this->remediationService->find($id);
            $this->remediationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($remediation_ids) . ' éléments',
            'modelName' => __('PkgTableauBord::remediation.plural')
        ]));
    }

    public function export($format)
    {
        $remediations_data = $this->remediationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RemediationExport($remediations_data,'csv'), 'remediation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RemediationExport($remediations_data,'xlsx'), 'remediation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RemediationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('remediations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('remediations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgTableauBord::remediation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRemediations()
    {
        $remediations = $this->remediationService->all();
        return response()->json($remediations);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Remediation) par ID, en format JSON.
     */
    public function getRemediation(Request $request, $id)
    {
        try {
            $remediation = $this->remediationService->find($id);
            return response()->json($remediation);
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
        $updatedRemediation = $this->remediationService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedRemediation],
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
        $remediationRequest = new RemediationRequest();
        $fullRules = $remediationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:remediations,id'];
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
        $itemRemediation = Remediation::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemRemediation, $field);
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
        $itemRemediation = Remediation::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemRemediation);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemRemediation, $changes);

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