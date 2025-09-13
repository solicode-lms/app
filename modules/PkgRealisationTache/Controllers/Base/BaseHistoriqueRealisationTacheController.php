<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Controllers\Base;
use Modules\PkgRealisationTache\Services\HistoriqueRealisationTacheService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationTache\App\Requests\HistoriqueRealisationTacheRequest;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\App\Exports\HistoriqueRealisationTacheExport;
use Modules\PkgRealisationTache\App\Imports\HistoriqueRealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseHistoriqueRealisationTacheController extends AdminController
{
    protected $historiqueRealisationTacheService;
    protected $realisationTacheService;
    protected $userService;

    public function __construct(HistoriqueRealisationTacheService $historiqueRealisationTacheService, RealisationTacheService $realisationTacheService, UserService $userService) {
        parent::__construct();
        $this->service  =  $historiqueRealisationTacheService;
        $this->historiqueRealisationTacheService = $historiqueRealisationTacheService;
        $this->realisationTacheService = $realisationTacheService;
        $this->userService = $userService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('historiqueRealisationTache.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('historiqueRealisationTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $historiqueRealisationTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'historiqueRealisationTaches_search',
                $this->viewState->get("filter.historiqueRealisationTache.historiqueRealisationTaches_search")
            )],
            $request->except(['historiqueRealisationTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->historiqueRealisationTacheService->prepareDataForIndexView($historiqueRealisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationTache::historiqueRealisationTache._index', $historiqueRealisationTache_compact_value)->render();
            }else{
                return view($historiqueRealisationTache_partialViewName, $historiqueRealisationTache_compact_value)->render();
            }
        }

        return view('PkgRealisationTache::historiqueRealisationTache.index', $historiqueRealisationTache_compact_value);
    }
    /**
     */
    public function create() {


        $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->createInstance();
        

        $realisationTaches = $this->realisationTacheService->all();
        $users = $this->userService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationTache::historiqueRealisationTache._fields', compact('bulkEdit' ,'itemHistoriqueRealisationTache', 'realisationTaches', 'users'));
        }
        return view('PkgRealisationTache::historiqueRealisationTache.create', compact('bulkEdit' ,'itemHistoriqueRealisationTache', 'realisationTaches', 'users'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $historiqueRealisationTache_ids = $request->input('ids', []);

        if (!is_array($historiqueRealisationTache_ids) || count($historiqueRealisationTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->find($historiqueRealisationTache_ids[0]);
         
 
        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemHistoriqueRealisationTache->realisationTache);
        $users = $this->userService->getAllForSelect($itemHistoriqueRealisationTache->user);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationTache::historiqueRealisationTache._fields', compact('bulkEdit', 'historiqueRealisationTache_ids', 'itemHistoriqueRealisationTache', 'realisationTaches', 'users'));
        }
        return view('PkgRealisationTache::historiqueRealisationTache.bulk-edit', compact('bulkEdit', 'historiqueRealisationTache_ids', 'itemHistoriqueRealisationTache', 'realisationTaches', 'users'));
    }
    /**
     */
    public function store(HistoriqueRealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $historiqueRealisationTache = $this->historiqueRealisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' => __('PkgRealisationTache::historiqueRealisationTache.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $historiqueRealisationTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('historiqueRealisationTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' => __('PkgRealisationTache::historiqueRealisationTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('historiqueRealisationTache.show_' . $id);

        $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->edit($id);


        if (request()->ajax()) {
            return view('PkgRealisationTache::historiqueRealisationTache._show', array_merge(compact('itemHistoriqueRealisationTache'),));
        }

        return view('PkgRealisationTache::historiqueRealisationTache.show', array_merge(compact('itemHistoriqueRealisationTache'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('historiqueRealisationTache.edit_' . $id);


        $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->edit($id);


        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemHistoriqueRealisationTache->realisationTache);
        $users = $this->userService->getAllForSelect($itemHistoriqueRealisationTache->user);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgRealisationTache::historiqueRealisationTache._fields', array_merge(compact('bulkEdit' , 'itemHistoriqueRealisationTache','realisationTaches', 'users'),));
        }

        return view('PkgRealisationTache::historiqueRealisationTache.edit', array_merge(compact('bulkEdit' ,'itemHistoriqueRealisationTache','realisationTaches', 'users'),));


    }
    /**
     */
    public function update(HistoriqueRealisationTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $historiqueRealisationTache = $this->historiqueRealisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' =>  __('PkgRealisationTache::historiqueRealisationTache.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $historiqueRealisationTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('historiqueRealisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' =>  __('PkgRealisationTache::historiqueRealisationTache.singular')
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
            'historiqueRealisationTache_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('historiqueRealisationTache_ids', []);
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
        $form         = new \Modules\PkgRealisationTache\App\Requests\HistoriqueRealisationTacheRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->historiqueRealisationTacheService->find($id);
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

        $historiqueRealisationTache = $this->historiqueRealisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' =>  __('PkgRealisationTache::historiqueRealisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('historiqueRealisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' =>  __('PkgRealisationTache::historiqueRealisationTache.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $historiqueRealisationTache_ids = $request->input('ids', []);
        if (!is_array($historiqueRealisationTache_ids) || count($historiqueRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($historiqueRealisationTache_ids as $id) {
            $entity = $this->historiqueRealisationTacheService->find($id);
            $this->historiqueRealisationTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($historiqueRealisationTache_ids) . ' éléments',
            'modelName' => __('PkgRealisationTache::historiqueRealisationTache.plural')
        ]));
    }

    public function export($format)
    {
        $historiqueRealisationTaches_data = $this->historiqueRealisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new HistoriqueRealisationTacheExport($historiqueRealisationTaches_data,'csv'), 'historiqueRealisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new HistoriqueRealisationTacheExport($historiqueRealisationTaches_data,'xlsx'), 'historiqueRealisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new HistoriqueRealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('historiqueRealisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('historiqueRealisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationTache::historiqueRealisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getHistoriqueRealisationTaches()
    {
        $historiqueRealisationTaches = $this->historiqueRealisationTacheService->all();
        return response()->json($historiqueRealisationTaches);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (HistoriqueRealisationTache) par ID, en format JSON.
     */
    public function getHistoriqueRealisationTache(Request $request, $id)
    {
        try {
            $historiqueRealisationTache = $this->historiqueRealisationTacheService->find($id);
            return response()->json($historiqueRealisationTache);
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
        $updatedHistoriqueRealisationTache = $this->historiqueRealisationTacheService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedHistoriqueRealisationTache],
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
        $historiqueRealisationTacheRequest = new HistoriqueRealisationTacheRequest();
        $fullRules = $historiqueRealisationTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:historique_realisation_taches,id'];
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
        $itemHistoriqueRealisationTache = HistoriqueRealisationTache::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemHistoriqueRealisationTache, $field);
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
        $itemHistoriqueRealisationTache = HistoriqueRealisationTache::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemHistoriqueRealisationTache);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemHistoriqueRealisationTache, $changes);

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