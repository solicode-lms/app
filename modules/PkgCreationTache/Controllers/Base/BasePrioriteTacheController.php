<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationTache\Controllers\Base;
use Modules\PkgCreationTache\Services\PrioriteTacheService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationTache\App\Requests\PrioriteTacheRequest;
use Modules\PkgCreationTache\Models\PrioriteTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationTache\App\Exports\PrioriteTacheExport;
use Modules\PkgCreationTache\App\Imports\PrioriteTacheImport;
use Modules\Core\Services\ContextState;

class BasePrioriteTacheController extends AdminController
{
    protected $prioriteTacheService;
    protected $formateurService;

    public function __construct(PrioriteTacheService $prioriteTacheService, FormateurService $formateurService) {
        parent::__construct();
        $this->service  =  $prioriteTacheService;
        $this->prioriteTacheService = $prioriteTacheService;
        $this->formateurService = $formateurService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('prioriteTache.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('prioriteTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $prioriteTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'prioriteTaches_search',
                $this->viewState->get("filter.prioriteTache.prioriteTaches_search")
            )],
            $request->except(['prioriteTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->prioriteTacheService->prepareDataForIndexView($prioriteTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationTache::prioriteTache._index', $prioriteTache_compact_value)->render();
            }else{
                return view($prioriteTache_partialViewName, $prioriteTache_compact_value)->render();
            }
        }

        return view('PkgCreationTache::prioriteTache.index', $prioriteTache_compact_value);
    }
    /**
     */
    public function create() {


        $itemPrioriteTache = $this->prioriteTacheService->createInstance();
        

        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationTache::prioriteTache._fields', compact('bulkEdit' ,'itemPrioriteTache', 'formateurs'));
        }
        return view('PkgCreationTache::prioriteTache.create', compact('bulkEdit' ,'itemPrioriteTache', 'formateurs'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $prioriteTache_ids = $request->input('ids', []);

        if (!is_array($prioriteTache_ids) || count($prioriteTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemPrioriteTache = $this->prioriteTacheService->find($prioriteTache_ids[0]);
         
 
        $formateurs = $this->formateurService->getAllForSelect($itemPrioriteTache->formateur);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemPrioriteTache = $this->prioriteTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationTache::prioriteTache._fields', compact('bulkEdit', 'prioriteTache_ids', 'itemPrioriteTache', 'formateurs'));
        }
        return view('PkgCreationTache::prioriteTache.bulk-edit', compact('bulkEdit', 'prioriteTache_ids', 'itemPrioriteTache', 'formateurs'));
    }
    /**
     */
    public function store(PrioriteTacheRequest $request) {
        $validatedData = $request->validated();
        $prioriteTache = $this->prioriteTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' => __('PkgCreationTache::prioriteTache.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $prioriteTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('prioriteTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' => __('PkgCreationTache::prioriteTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('prioriteTache.show_' . $id);

        $itemPrioriteTache = $this->prioriteTacheService->edit($id);


        if (request()->ajax()) {
            return view('PkgCreationTache::prioriteTache._show', array_merge(compact('itemPrioriteTache'),));
        }

        return view('PkgCreationTache::prioriteTache.show', array_merge(compact('itemPrioriteTache'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('prioriteTache.edit_' . $id);


        $itemPrioriteTache = $this->prioriteTacheService->edit($id);


        $formateurs = $this->formateurService->getAllForSelect($itemPrioriteTache->formateur);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationTache::prioriteTache._fields', array_merge(compact('bulkEdit' , 'itemPrioriteTache','formateurs'),));
        }

        return view('PkgCreationTache::prioriteTache.edit', array_merge(compact('bulkEdit' ,'itemPrioriteTache','formateurs'),));


    }
    /**
     */
    public function update(PrioriteTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $prioriteTache = $this->prioriteTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgCreationTache::prioriteTache.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $prioriteTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgCreationTache::prioriteTache.singular')
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
            'prioriteTache_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('prioriteTache_ids', []);
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
        $form         = new \Modules\PkgCreationTache\App\Requests\PrioriteTacheRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->prioriteTacheService->find($id);
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

        $prioriteTache = $this->prioriteTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgCreationTache::prioriteTache.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgCreationTache::prioriteTache.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $prioriteTache_ids = $request->input('ids', []);
        if (!is_array($prioriteTache_ids) || count($prioriteTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($prioriteTache_ids as $id) {
            $entity = $this->prioriteTacheService->find($id);
            $this->prioriteTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($prioriteTache_ids) . ' éléments',
            'modelName' => __('PkgCreationTache::prioriteTache.plural')
        ]));
    }

    public function export($format)
    {
        $prioriteTaches_data = $this->prioriteTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new PrioriteTacheExport($prioriteTaches_data,'csv'), 'prioriteTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new PrioriteTacheExport($prioriteTaches_data,'xlsx'), 'prioriteTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new PrioriteTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('prioriteTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationTache::prioriteTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getPrioriteTaches()
    {
        $prioriteTaches = $this->prioriteTacheService->all();
        return response()->json($prioriteTaches);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (PrioriteTache) par ID, en format JSON.
     */
    public function getPrioriteTache(Request $request, $id)
    {
        try {
            $prioriteTache = $this->prioriteTacheService->find($id);
            return response()->json($prioriteTache);
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
        $updatedPrioriteTache = $this->prioriteTacheService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedPrioriteTache],
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
        $prioriteTacheRequest = new PrioriteTacheRequest();
        $fullRules = $prioriteTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:priorite_taches,id'];
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
        $itemPrioriteTache = PrioriteTache::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemPrioriteTache, $field);
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
        $itemPrioriteTache = PrioriteTache::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemPrioriteTache);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemPrioriteTache, $changes);

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