<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Controllers\Base;
use Modules\PkgRealisationTache\Services\RevisionTacheService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationTache\App\Requests\RevisionTacheRequest;
use Modules\PkgRealisationTache\Models\RevisionTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\App\Exports\RevisionTacheExport;
use Modules\PkgRealisationTache\App\Imports\RevisionTacheImport;
use Modules\Core\Services\ContextState;

class BaseRevisionTacheController extends AdminController
{
    protected $revisionTacheService;
    protected $realisationTacheService;

    public function __construct(RevisionTacheService $revisionTacheService, RealisationTacheService $realisationTacheService) {
        parent::__construct();
        $this->service  =  $revisionTacheService;
        $this->revisionTacheService = $revisionTacheService;
        $this->realisationTacheService = $realisationTacheService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('revisionTache.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('revisionTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $revisionTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'revisionTaches_search',
                $this->viewState->get("filter.revisionTache.revisionTaches_search")
            )],
            $request->except(['revisionTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->revisionTacheService->prepareDataForIndexView($revisionTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationTache::revisionTache._index', $revisionTache_compact_value)->render();
            }else{
                return view($revisionTache_partialViewName, $revisionTache_compact_value)->render();
            }
        }

        return view('PkgRealisationTache::revisionTache.index', $revisionTache_compact_value);
    }
    /**
     */
    protected function dataForCreateView() {

        // scopeDataByRole
        $itemRevisionTache = $this->revisionTacheService->createInstance();


        $realisationTaches = $this->realisationTacheService->all();

        $bulkEdit = false;
        return compact('bulkEdit' ,'itemRevisionTache', 'realisationTaches');

    }
    /**
     */
    public function create() {
        $viewData = $this->dataForCreateView();

        if (request()->ajax()) {
            return view('PkgRealisationTache::revisionTache._fields', $viewData);
        }

        return view('PkgRealisationTache::revisionTache.create', $viewData);
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $revisionTache_ids = $request->input('ids', []);

        if (!is_array($revisionTache_ids) || count($revisionTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRevisionTache = $this->revisionTacheService->find($revisionTache_ids[0]);
         
 
        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemRevisionTache->realisationTache);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRevisionTache = $this->revisionTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationTache::revisionTache._fields', compact('bulkEdit', 'revisionTache_ids', 'itemRevisionTache', 'realisationTaches'));
        }
        return view('PkgRealisationTache::revisionTache.bulk-edit', compact('bulkEdit', 'revisionTache_ids', 'itemRevisionTache', 'realisationTaches'));
    }
    /**
     */
    public function store(RevisionTacheRequest $request) {
        $validatedData = $request->validated();
        $revisionTache = $this->revisionTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $revisionTache,
                'modelName' => __('PkgRealisationTache::revisionTache.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $revisionTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('revisionTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $revisionTache,
                'modelName' => __('PkgRealisationTache::revisionTache.singular')
            ])
        );
    }
    /**
     */
    protected function dataForShowView(string $id) {
        $this->viewState->setContextKey('revisionTache.show_' . $id);

        $itemRevisionTache = $this->revisionTacheService->edit($id);


        return array_merge(compact('itemRevisionTache'),);

    }
    /**
     */
    public function show(string $id) {
        $viewData = $this->dataForShowView($id);

        if (request()->ajax()) {
            return view('PkgRealisationTache::revisionTache._show', $viewData);
        }

        return view('PkgRealisationTache::revisionTache.show', $viewData);

    }
    /**
     */
    protected function dataForEditView(string $id) {

        $this->viewState->setContextKey('revisionTache.edit_' . $id);


        $itemRevisionTache = $this->revisionTacheService->edit($id);


        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemRevisionTache->realisationTache);


        $bulkEdit = false;

        $viewData = array_merge(compact('bulkEdit' , 'itemRevisionTache','realisationTaches'),);

        return $viewData;

    }
    /**
     */
    public function edit(string $id) {
        $viewData = $this->dataForEditView($id);

        if (request()->ajax()) {
            return view('PkgRealisationTache::revisionTache._fields', $viewData);
        }

        return view('PkgRealisationTache::revisionTache.edit', $viewData);

    }
    /**
     */
    public function update(RevisionTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $revisionTache = $this->revisionTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $revisionTache,
                'modelName' =>  __('PkgRealisationTache::revisionTache.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $revisionTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('revisionTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $revisionTache,
                'modelName' =>  __('PkgRealisationTache::revisionTache.singular')
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
            'revisionTache_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('revisionTache_ids', []);
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
        $form         = new \Modules\PkgRealisationTache\App\Requests\RevisionTacheRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->revisionTacheService->find($id);
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

        $revisionTache = $this->revisionTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $revisionTache,
                'modelName' =>  __('PkgRealisationTache::revisionTache.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('revisionTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $revisionTache,
                'modelName' =>  __('PkgRealisationTache::revisionTache.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $revisionTache_ids = $request->input('ids', []);
        if (!is_array($revisionTache_ids) || count($revisionTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($revisionTache_ids as $id) {
            $entity = $this->revisionTacheService->find($id);
            $this->revisionTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($revisionTache_ids) . ' éléments',
            'modelName' => __('PkgRealisationTache::revisionTache.plural')
        ]));
    }

    public function export($format)
    {
        $revisionTaches_data = $this->revisionTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RevisionTacheExport($revisionTaches_data,'csv'), 'revisionTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RevisionTacheExport($revisionTaches_data,'xlsx'), 'revisionTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RevisionTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('revisionTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('revisionTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationTache::revisionTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRevisionTaches()
    {
        $revisionTaches = $this->revisionTacheService->all();
        return response()->json($revisionTaches);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (RevisionTache) par ID, en format JSON.
     */
    public function getRevisionTache(Request $request, $id)
    {
        try {
            $revisionTache = $this->revisionTacheService->find($id);
            return response()->json($revisionTache);
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
        $updatedRevisionTache = $this->revisionTacheService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedRevisionTache],
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
        $revisionTacheRequest = new RevisionTacheRequest();
        $fullRules = $revisionTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:revision_taches,id'];
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
        $itemRevisionTache = RevisionTache::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemRevisionTache, $field);
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
        $itemRevisionTache = RevisionTache::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemRevisionTache);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemRevisionTache, $changes);

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