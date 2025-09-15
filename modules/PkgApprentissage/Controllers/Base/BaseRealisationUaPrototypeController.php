<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\RealisationUaPrototypeService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\RealisationUaPrototypeRequest;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\RealisationUaPrototypeExport;
use Modules\PkgApprentissage\App\Imports\RealisationUaPrototypeImport;
use Modules\Core\Services\ContextState;

class BaseRealisationUaPrototypeController extends AdminController
{
    protected $realisationUaPrototypeService;
    protected $realisationTacheService;
    protected $realisationUaService;

    public function __construct(RealisationUaPrototypeService $realisationUaPrototypeService, RealisationTacheService $realisationTacheService, RealisationUaService $realisationUaService) {
        parent::__construct();
        $this->service  =  $realisationUaPrototypeService;
        $this->realisationUaPrototypeService = $realisationUaPrototypeService;
        $this->realisationTacheService = $realisationTacheService;
        $this->realisationUaService = $realisationUaService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationUaPrototype.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationUaPrototype');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $realisationUaPrototypes_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationUaPrototypes_search',
                $this->viewState->get("filter.realisationUaPrototype.realisationUaPrototypes_search")
            )],
            $request->except(['realisationUaPrototypes_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationUaPrototypeService->prepareDataForIndexView($realisationUaPrototypes_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::realisationUaPrototype._index', $realisationUaPrototype_compact_value)->render();
            }else{
                return view($realisationUaPrototype_partialViewName, $realisationUaPrototype_compact_value)->render();
            }
        }

        return view('PkgApprentissage::realisationUaPrototype.index', $realisationUaPrototype_compact_value);
    }
    /**
     */
    public function create() {


        $itemRealisationUaPrototype = $this->realisationUaPrototypeService->createInstance();
        

        $realisationTaches = $this->realisationTacheService->all();
        $realisationUas = $this->realisationUaService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaPrototype._fields', compact('bulkEdit' ,'itemRealisationUaPrototype', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationUaPrototype.create', compact('bulkEdit' ,'itemRealisationUaPrototype', 'realisationTaches', 'realisationUas'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationUaPrototype_ids = $request->input('ids', []);

        if (!is_array($realisationUaPrototype_ids) || count($realisationUaPrototype_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRealisationUaPrototype = $this->realisationUaPrototypeService->find($realisationUaPrototype_ids[0]);
         
 
        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemRealisationUaPrototype->realisationTache);
        $realisationUas = $this->realisationUaService->getAllForSelect($itemRealisationUaPrototype->realisationUa);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationUaPrototype = $this->realisationUaPrototypeService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaPrototype._fields', compact('bulkEdit', 'realisationUaPrototype_ids', 'itemRealisationUaPrototype', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationUaPrototype.bulk-edit', compact('bulkEdit', 'realisationUaPrototype_ids', 'itemRealisationUaPrototype', 'realisationTaches', 'realisationUas'));
    }
    /**
     */
    public function store(RealisationUaPrototypeRequest $request) {
        $validatedData = $request->validated();
        $realisationUaPrototype = $this->realisationUaPrototypeService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' => __('PkgApprentissage::realisationUaPrototype.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationUaPrototype->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('realisationUaPrototypes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' => __('PkgApprentissage::realisationUaPrototype.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationUaPrototype.show_' . $id);

        $itemRealisationUaPrototype = $this->realisationUaPrototypeService->edit($id);


        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaPrototype._show', array_merge(compact('itemRealisationUaPrototype'),));
        }

        return view('PkgApprentissage::realisationUaPrototype.show', array_merge(compact('itemRealisationUaPrototype'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationUaPrototype.edit_' . $id);


        $itemRealisationUaPrototype = $this->realisationUaPrototypeService->edit($id);


        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemRealisationUaPrototype->realisationTache);
        $realisationUas = $this->realisationUaService->getAllForSelect($itemRealisationUaPrototype->realisationUa);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaPrototype._fields', array_merge(compact('bulkEdit' , 'itemRealisationUaPrototype','realisationTaches', 'realisationUas'),));
        }

        return view('PkgApprentissage::realisationUaPrototype.edit', array_merge(compact('bulkEdit' ,'itemRealisationUaPrototype','realisationTaches', 'realisationUas'),));


    }
    /**
     */
    public function update(RealisationUaPrototypeRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationUaPrototype = $this->realisationUaPrototypeService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' =>  __('PkgApprentissage::realisationUaPrototype.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationUaPrototype->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('realisationUaPrototypes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' =>  __('PkgApprentissage::realisationUaPrototype.singular')
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
            'realisationUaPrototype_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('realisationUaPrototype_ids', []);
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
        $form         = new \Modules\PkgApprentissage\App\Requests\RealisationUaPrototypeRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->realisationUaPrototypeService->find($id);
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

        $realisationUaPrototype = $this->realisationUaPrototypeService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' =>  __('PkgApprentissage::realisationUaPrototype.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('realisationUaPrototypes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' =>  __('PkgApprentissage::realisationUaPrototype.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationUaPrototype_ids = $request->input('ids', []);
        if (!is_array($realisationUaPrototype_ids) || count($realisationUaPrototype_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationUaPrototype_ids as $id) {
            $entity = $this->realisationUaPrototypeService->find($id);
            $this->realisationUaPrototypeService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationUaPrototype_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::realisationUaPrototype.plural')
        ]));
    }

    public function export($format)
    {
        $realisationUaPrototypes_data = $this->realisationUaPrototypeService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationUaPrototypeExport($realisationUaPrototypes_data,'csv'), 'realisationUaPrototype_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationUaPrototypeExport($realisationUaPrototypes_data,'xlsx'), 'realisationUaPrototype_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationUaPrototypeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationUaPrototypes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationUaPrototypes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::realisationUaPrototype.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationUaPrototypes()
    {
        $realisationUaPrototypes = $this->realisationUaPrototypeService->all();
        return response()->json($realisationUaPrototypes);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (RealisationUaPrototype) par ID, en format JSON.
     */
    public function getRealisationUaPrototype(Request $request, $id)
    {
        try {
            $realisationUaPrototype = $this->realisationUaPrototypeService->find($id);
            return response()->json($realisationUaPrototype);
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
        $updatedRealisationUaPrototype = $this->realisationUaPrototypeService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedRealisationUaPrototype],
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
        $realisationUaPrototypeRequest = new RealisationUaPrototypeRequest();
        $fullRules = $realisationUaPrototypeRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_ua_prototypes,id'];
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
        $itemRealisationUaPrototype = RealisationUaPrototype::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemRealisationUaPrototype, $field);
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
        $itemRealisationUaPrototype = RealisationUaPrototype::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemRealisationUaPrototype);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemRealisationUaPrototype, $changes);

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