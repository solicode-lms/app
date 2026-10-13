<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgTableauBord\Controllers\Base;
use Modules\PkgTableauBord\Services\LogActiviteFormateurService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgTableauBord\App\Requests\LogActiviteFormateurRequest;
use Modules\PkgTableauBord\Models\LogActiviteFormateur;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgTableauBord\App\Exports\LogActiviteFormateurExport;
use Modules\PkgTableauBord\App\Imports\LogActiviteFormateurImport;
use Modules\Core\Services\ContextState;

class BaseLogActiviteFormateurController extends AdminController
{
    protected $logActiviteFormateurService;
    protected $formateurService;

    public function __construct(LogActiviteFormateurService $logActiviteFormateurService, FormateurService $formateurService) {
        parent::__construct();
        $this->service  =  $logActiviteFormateurService;
        $this->logActiviteFormateurService = $logActiviteFormateurService;
        $this->formateurService = $formateurService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('logActiviteFormateur.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('logActiviteFormateur');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $logActiviteFormateurs_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'logActiviteFormateurs_search',
                $this->viewState->get("filter.logActiviteFormateur.logActiviteFormateurs_search")
            )],
            $request->except(['logActiviteFormateurs_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->logActiviteFormateurService->prepareDataForIndexView($logActiviteFormateurs_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgTableauBord::logActiviteFormateur._index', $logActiviteFormateur_compact_value)->render();
            }else{
                return view($logActiviteFormateur_partialViewName, $logActiviteFormateur_compact_value)->render();
            }
        }

        return view('PkgTableauBord::logActiviteFormateur.index', $logActiviteFormateur_compact_value);
    }
    /**
     */
    protected function dataForCreateView() {

        // scopeDataByRole
        $itemLogActiviteFormateur = $this->logActiviteFormateurService->createInstance();


        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        return compact('bulkEdit' ,'itemLogActiviteFormateur', 'formateurs');

    }
    /**
     */
    public function create() {
        $viewData = $this->dataForCreateView();

        if (request()->ajax()) {
            return view('PkgTableauBord::logActiviteFormateur._fields', $viewData);
        }

        return view('PkgTableauBord::logActiviteFormateur.create', $viewData);
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $logActiviteFormateur_ids = $request->input('ids', []);

        if (!is_array($logActiviteFormateur_ids) || count($logActiviteFormateur_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemLogActiviteFormateur = $this->logActiviteFormateurService->find($logActiviteFormateur_ids[0]);
         
 
        $formateurs = $this->formateurService->getAllForSelect($itemLogActiviteFormateur->formateur);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemLogActiviteFormateur = $this->logActiviteFormateurService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgTableauBord::logActiviteFormateur._fields', compact('bulkEdit', 'logActiviteFormateur_ids', 'itemLogActiviteFormateur', 'formateurs'));
        }
        return view('PkgTableauBord::logActiviteFormateur.bulk-edit', compact('bulkEdit', 'logActiviteFormateur_ids', 'itemLogActiviteFormateur', 'formateurs'));
    }
    /**
     */
    public function store(LogActiviteFormateurRequest $request) {
        $validatedData = $request->validated();
        $logActiviteFormateur = $this->logActiviteFormateurService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $logActiviteFormateur,
                'modelName' => __('PkgTableauBord::logActiviteFormateur.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $logActiviteFormateur->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('logActiviteFormateurs.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $logActiviteFormateur,
                'modelName' => __('PkgTableauBord::logActiviteFormateur.singular')
            ])
        );
    }
    /**
     */
    protected function dataForShowView(string $id) {
        $this->viewState->setContextKey('logActiviteFormateur.show_' . $id);

        $itemLogActiviteFormateur = $this->logActiviteFormateurService->edit($id);


        return array_merge(compact('itemLogActiviteFormateur'),);

    }
    /**
     */
    public function show(string $id) {
        $viewData = $this->dataForShowView($id);

        if (request()->ajax()) {
            return view('PkgTableauBord::logActiviteFormateur._show', $viewData);
        }

        return view('PkgTableauBord::logActiviteFormateur.show', $viewData);

    }
    /**
     */
    protected function dataForEditView(string $id) {

        $this->viewState->setContextKey('logActiviteFormateur.edit_' . $id);


        $itemLogActiviteFormateur = $this->logActiviteFormateurService->edit($id);


        $formateurs = $this->formateurService->getAllForSelect($itemLogActiviteFormateur->formateur);


        $bulkEdit = false;

        $viewData = array_merge(compact('bulkEdit' , 'itemLogActiviteFormateur','formateurs'),);

        return $viewData;

    }
    /**
     */
    public function edit(string $id) {
        $viewData = $this->dataForEditView($id);

        if (request()->ajax()) {
            return view('PkgTableauBord::logActiviteFormateur._fields', $viewData);
        }

        return view('PkgTableauBord::logActiviteFormateur.edit', $viewData);

    }
    /**
     */
    public function update(LogActiviteFormateurRequest $request, string $id) {

        $validatedData = $request->validated();
        $logActiviteFormateur = $this->logActiviteFormateurService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $logActiviteFormateur,
                'modelName' =>  __('PkgTableauBord::logActiviteFormateur.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $logActiviteFormateur->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('logActiviteFormateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $logActiviteFormateur,
                'modelName' =>  __('PkgTableauBord::logActiviteFormateur.singular')
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
            'logActiviteFormateur_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('logActiviteFormateur_ids', []);
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
        $form         = new \Modules\PkgTableauBord\App\Requests\LogActiviteFormateurRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->logActiviteFormateurService->find($id);
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

        $logActiviteFormateur = $this->logActiviteFormateurService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $logActiviteFormateur,
                'modelName' =>  __('PkgTableauBord::logActiviteFormateur.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('logActiviteFormateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $logActiviteFormateur,
                'modelName' =>  __('PkgTableauBord::logActiviteFormateur.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $logActiviteFormateur_ids = $request->input('ids', []);
        if (!is_array($logActiviteFormateur_ids) || count($logActiviteFormateur_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($logActiviteFormateur_ids as $id) {
            $entity = $this->logActiviteFormateurService->find($id);
            $this->logActiviteFormateurService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($logActiviteFormateur_ids) . ' éléments',
            'modelName' => __('PkgTableauBord::logActiviteFormateur.plural')
        ]));
    }

    public function export($format)
    {
        $logActiviteFormateurs_data = $this->logActiviteFormateurService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new LogActiviteFormateurExport($logActiviteFormateurs_data,'csv'), 'logActiviteFormateur_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new LogActiviteFormateurExport($logActiviteFormateurs_data,'xlsx'), 'logActiviteFormateur_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new LogActiviteFormateurImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('logActiviteFormateurs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('logActiviteFormateurs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgTableauBord::logActiviteFormateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLogActiviteFormateurs()
    {
        $logActiviteFormateurs = $this->logActiviteFormateurService->all();
        return response()->json($logActiviteFormateurs);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (LogActiviteFormateur) par ID, en format JSON.
     */
    public function getLogActiviteFormateur(Request $request, $id)
    {
        try {
            $logActiviteFormateur = $this->logActiviteFormateurService->find($id);
            return response()->json($logActiviteFormateur);
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
        $updatedLogActiviteFormateur = $this->logActiviteFormateurService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedLogActiviteFormateur],
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
        $logActiviteFormateurRequest = new LogActiviteFormateurRequest();
        $fullRules = $logActiviteFormateurRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:log_activite_formateurs,id'];
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
        $itemLogActiviteFormateur = LogActiviteFormateur::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemLogActiviteFormateur, $field);
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
        $itemLogActiviteFormateur = LogActiviteFormateur::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemLogActiviteFormateur);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemLogActiviteFormateur, $changes);

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