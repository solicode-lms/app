<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\EtatRealisationModuleService;
use Modules\Core\Services\SysColorService;
use Modules\PkgApprentissage\Services\RealisationModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\EtatRealisationModuleRequest;
use Modules\PkgApprentissage\Models\EtatRealisationModule;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\EtatRealisationModuleExport;
use Modules\PkgApprentissage\App\Imports\EtatRealisationModuleImport;
use Modules\Core\Services\ContextState;

class BaseEtatRealisationModuleController extends AdminController
{
    protected $etatRealisationModuleService;
    protected $sysColorService;

    public function __construct(EtatRealisationModuleService $etatRealisationModuleService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $etatRealisationModuleService;
        $this->etatRealisationModuleService = $etatRealisationModuleService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('etatRealisationModule.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('etatRealisationModule');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $etatRealisationModules_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatRealisationModules_search',
                $this->viewState->get("filter.etatRealisationModule.etatRealisationModules_search")
            )],
            $request->except(['etatRealisationModules_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatRealisationModuleService->prepareDataForIndexView($etatRealisationModules_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::etatRealisationModule._index', $etatRealisationModule_compact_value)->render();
            }else{
                return view($etatRealisationModule_partialViewName, $etatRealisationModule_compact_value)->render();
            }
        }

        return view('PkgApprentissage::etatRealisationModule.index', $etatRealisationModule_compact_value);
    }
    /**
     */
    public function create() {


        $itemEtatRealisationModule = $this->etatRealisationModuleService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationModule._fields', compact('bulkEdit' ,'itemEtatRealisationModule', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationModule.create', compact('bulkEdit' ,'itemEtatRealisationModule', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatRealisationModule_ids = $request->input('ids', []);

        if (!is_array($etatRealisationModule_ids) || count($etatRealisationModule_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEtatRealisationModule = $this->etatRealisationModuleService->find($etatRealisationModule_ids[0]);
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationModule->sysColor);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatRealisationModule = $this->etatRealisationModuleService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationModule._fields', compact('bulkEdit', 'etatRealisationModule_ids', 'itemEtatRealisationModule', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationModule.bulk-edit', compact('bulkEdit', 'etatRealisationModule_ids', 'itemEtatRealisationModule', 'sysColors'));
    }
    /**
     */
    public function store(EtatRealisationModuleRequest $request) {
        $validatedData = $request->validated();
        $etatRealisationModule = $this->etatRealisationModuleService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationModule,
                'modelName' => __('PkgApprentissage::etatRealisationModule.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationModule->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('etatRealisationModules.edit', ['etatRealisationModule' => $etatRealisationModule->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationModule,
                'modelName' => __('PkgApprentissage::etatRealisationModule.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatRealisationModule.show_' . $id);

        $itemEtatRealisationModule = $this->etatRealisationModuleService->edit($id);


        $this->viewState->set('scope.realisationModule.etat_realisation_module_id', $id);
        

        $realisationModuleService =  new RealisationModuleService();
        $realisationModules_view_data = $realisationModuleService->prepareDataForIndexView();
        extract($realisationModules_view_data);

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationModule._show', array_merge(compact('itemEtatRealisationModule'),$realisationModule_compact_value));
        }

        return view('PkgApprentissage::etatRealisationModule.show', array_merge(compact('itemEtatRealisationModule'),$realisationModule_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatRealisationModule.edit_' . $id);


        $itemEtatRealisationModule = $this->etatRealisationModuleService->edit($id);


        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationModule->sysColor);


        $this->viewState->set('scope.realisationModule.etat_realisation_module_id', $id);
        

        $realisationModuleService =  new RealisationModuleService();
        $realisationModules_view_data = $realisationModuleService->prepareDataForIndexView();
        extract($realisationModules_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationModule._edit', array_merge(compact('bulkEdit' , 'itemEtatRealisationModule','sysColors'),$realisationModule_compact_value));
        }

        return view('PkgApprentissage::etatRealisationModule.edit', array_merge(compact('bulkEdit' ,'itemEtatRealisationModule','sysColors'),$realisationModule_compact_value));


    }
    /**
     */
    public function update(EtatRealisationModuleRequest $request, string $id) {

        $validatedData = $request->validated();
        $etatRealisationModule = $this->etatRealisationModuleService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationModule,
                'modelName' =>  __('PkgApprentissage::etatRealisationModule.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationModule->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('etatRealisationModules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationModule,
                'modelName' =>  __('PkgApprentissage::etatRealisationModule.singular')
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
            'etatRealisationModule_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('etatRealisationModule_ids', []);
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
        $form         = new \Modules\PkgApprentissage\App\Requests\EtatRealisationModuleRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->etatRealisationModuleService->find($id);
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

        $etatRealisationModule = $this->etatRealisationModuleService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationModule,
                'modelName' =>  __('PkgApprentissage::etatRealisationModule.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('etatRealisationModules.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationModule,
                'modelName' =>  __('PkgApprentissage::etatRealisationModule.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatRealisationModule_ids = $request->input('ids', []);
        if (!is_array($etatRealisationModule_ids) || count($etatRealisationModule_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatRealisationModule_ids as $id) {
            $entity = $this->etatRealisationModuleService->find($id);
            $this->etatRealisationModuleService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatRealisationModule_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::etatRealisationModule.plural')
        ]));
    }

    public function export($format)
    {
        $etatRealisationModules_data = $this->etatRealisationModuleService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatRealisationModuleExport($etatRealisationModules_data,'csv'), 'etatRealisationModule_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatRealisationModuleExport($etatRealisationModules_data,'xlsx'), 'etatRealisationModule_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatRealisationModuleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatRealisationModules.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatRealisationModules.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::etatRealisationModule.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatRealisationModules()
    {
        $etatRealisationModules = $this->etatRealisationModuleService->all();
        return response()->json($etatRealisationModules);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EtatRealisationModule) par ID, en format JSON.
     */
    public function getEtatRealisationModule(Request $request, $id)
    {
        try {
            $etatRealisationModule = $this->etatRealisationModuleService->find($id);
            return response()->json($etatRealisationModule);
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
        $updatedEtatRealisationModule = $this->etatRealisationModuleService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEtatRealisationModule],
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
        $etatRealisationModuleRequest = new EtatRealisationModuleRequest();
        $fullRules = $etatRealisationModuleRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_realisation_modules,id'];
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
        $itemEtatRealisationModule = EtatRealisationModule::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEtatRealisationModule, $field);
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
        $itemEtatRealisationModule = EtatRealisationModule::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEtatRealisationModule);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEtatRealisationModule, $changes);

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