<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgTableauBord\Controllers\Base;
use Modules\PkgTableauBord\Services\BordereauxOfficielService;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\ModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgTableauBord\App\Requests\BordereauxOfficielRequest;
use Modules\PkgTableauBord\Models\BordereauxOfficiel;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgTableauBord\App\Exports\BordereauxOfficielExport;
use Modules\PkgTableauBord\App\Imports\BordereauxOfficielImport;
use Modules\Core\Services\ContextState;

class BaseBordereauxOfficielController extends AdminController
{
    protected $bordereauxOfficielService;
    protected $anneeFormationService;
    protected $userService;
    protected $groupeService;
    protected $moduleService;

    public function __construct(BordereauxOfficielService $bordereauxOfficielService, AnneeFormationService $anneeFormationService, UserService $userService, GroupeService $groupeService, ModuleService $moduleService) {
        parent::__construct();
        $this->service  =  $bordereauxOfficielService;
        $this->bordereauxOfficielService = $bordereauxOfficielService;
        $this->anneeFormationService = $anneeFormationService;
        $this->userService = $userService;
        $this->groupeService = $groupeService;
        $this->moduleService = $moduleService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('bordereauxOfficiel.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('bordereauxOfficiel');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $bordereauxOfficiels_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'bordereauxOfficiels_search',
                $this->viewState->get("filter.bordereauxOfficiel.bordereauxOfficiels_search")
            )],
            $request->except(['bordereauxOfficiels_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->bordereauxOfficielService->prepareDataForIndexView($bordereauxOfficiels_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgTableauBord::bordereauxOfficiel._index', $bordereauxOfficiel_compact_value)->render();
            }else{
                return view($bordereauxOfficiel_partialViewName, $bordereauxOfficiel_compact_value)->render();
            }
        }

        return view('PkgTableauBord::bordereauxOfficiel.index', $bordereauxOfficiel_compact_value);
    }
    /**
     */
    protected function dataForCreateView() {

        // scopeDataByRole
        $itemBordereauxOfficiel = $this->bordereauxOfficielService->createInstance();


        $modules = $this->moduleService->all();
        $groupes = $this->groupeService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $genereParUsers = $this->userService->all();

        $bulkEdit = false;
        return compact('bulkEdit' ,'itemBordereauxOfficiel', 'modules', 'groupes', 'anneeFormations', 'genereParUsers');

    }
    /**
     */
    public function create() {
        $viewData = $this->dataForCreateView();

        if (request()->ajax()) {
            return view('PkgTableauBord::bordereauxOfficiel._fields', $viewData);
        }

        return view('PkgTableauBord::bordereauxOfficiel.create', $viewData);
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $bordereauxOfficiel_ids = $request->input('ids', []);

        if (!is_array($bordereauxOfficiel_ids) || count($bordereauxOfficiel_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemBordereauxOfficiel = $this->bordereauxOfficielService->find($bordereauxOfficiel_ids[0]);
         
 
        $modules = $this->moduleService->getAllForSelect($itemBordereauxOfficiel->module);
        $groupes = $this->groupeService->getAllForSelect($itemBordereauxOfficiel->groupe);
        $anneeFormations = $this->anneeFormationService->getAllForSelect($itemBordereauxOfficiel->anneeFormation);
        $genereParUsers = $this->userService->getAllForSelect($itemBordereauxOfficiel->genereParUser);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemBordereauxOfficiel = $this->bordereauxOfficielService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgTableauBord::bordereauxOfficiel._fields', compact('bulkEdit', 'bordereauxOfficiel_ids', 'itemBordereauxOfficiel', 'modules', 'groupes', 'anneeFormations', 'genereParUsers'));
        }
        return view('PkgTableauBord::bordereauxOfficiel.bulk-edit', compact('bulkEdit', 'bordereauxOfficiel_ids', 'itemBordereauxOfficiel', 'modules', 'groupes', 'anneeFormations', 'genereParUsers'));
    }
    /**
     */
    public function store(BordereauxOfficielRequest $request) {
        $validatedData = $request->validated();
        $bordereauxOfficiel = $this->bordereauxOfficielService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $bordereauxOfficiel,
                'modelName' => __('PkgTableauBord::bordereauxOfficiel.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $bordereauxOfficiel->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('bordereauxOfficiels.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $bordereauxOfficiel,
                'modelName' => __('PkgTableauBord::bordereauxOfficiel.singular')
            ])
        );
    }
    /**
     */
    protected function dataForShowView(string $id) {
        $this->viewState->setContextKey('bordereauxOfficiel.show_' . $id);

        $itemBordereauxOfficiel = $this->bordereauxOfficielService->edit($id);


        return array_merge(compact('itemBordereauxOfficiel'),);

    }
    /**
     */
    public function show(string $id) {
        $viewData = $this->dataForShowView($id);

        if (request()->ajax()) {
            return view('PkgTableauBord::bordereauxOfficiel._show', $viewData);
        }

        return view('PkgTableauBord::bordereauxOfficiel.show', $viewData);

    }
    /**
     */
    protected function dataForEditView(string $id) {

        $this->viewState->setContextKey('bordereauxOfficiel.edit_' . $id);


        $itemBordereauxOfficiel = $this->bordereauxOfficielService->edit($id);


        $modules = $this->moduleService->getAllForSelect($itemBordereauxOfficiel->module);
        $groupes = $this->groupeService->getAllForSelect($itemBordereauxOfficiel->groupe);
        $anneeFormations = $this->anneeFormationService->getAllForSelect($itemBordereauxOfficiel->anneeFormation);
        $genereParUsers = $this->userService->getAllForSelect($itemBordereauxOfficiel->genereParUser);


        $bulkEdit = false;

        $viewData = array_merge(compact('bulkEdit' , 'itemBordereauxOfficiel','modules', 'groupes', 'anneeFormations', 'genereParUsers'),);

        return $viewData;

    }
    /**
     */
    public function edit(string $id) {
        $viewData = $this->dataForEditView($id);

        if (request()->ajax()) {
            return view('PkgTableauBord::bordereauxOfficiel._fields', $viewData);
        }

        return view('PkgTableauBord::bordereauxOfficiel.edit', $viewData);

    }
    /**
     */
    public function update(BordereauxOfficielRequest $request, string $id) {

        $validatedData = $request->validated();
        $bordereauxOfficiel = $this->bordereauxOfficielService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $bordereauxOfficiel,
                'modelName' =>  __('PkgTableauBord::bordereauxOfficiel.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $bordereauxOfficiel->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('bordereauxOfficiels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $bordereauxOfficiel,
                'modelName' =>  __('PkgTableauBord::bordereauxOfficiel.singular')
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
            'bordereauxOfficiel_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('bordereauxOfficiel_ids', []);
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
        $form         = new \Modules\PkgTableauBord\App\Requests\BordereauxOfficielRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->bordereauxOfficielService->find($id);
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

        $bordereauxOfficiel = $this->bordereauxOfficielService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $bordereauxOfficiel,
                'modelName' =>  __('PkgTableauBord::bordereauxOfficiel.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('bordereauxOfficiels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $bordereauxOfficiel,
                'modelName' =>  __('PkgTableauBord::bordereauxOfficiel.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $bordereauxOfficiel_ids = $request->input('ids', []);
        if (!is_array($bordereauxOfficiel_ids) || count($bordereauxOfficiel_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($bordereauxOfficiel_ids as $id) {
            $entity = $this->bordereauxOfficielService->find($id);
            $this->bordereauxOfficielService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($bordereauxOfficiel_ids) . ' éléments',
            'modelName' => __('PkgTableauBord::bordereauxOfficiel.plural')
        ]));
    }

    public function export($format)
    {
        $bordereauxOfficiels_data = $this->bordereauxOfficielService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new BordereauxOfficielExport($bordereauxOfficiels_data,'csv'), 'bordereauxOfficiel_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new BordereauxOfficielExport($bordereauxOfficiels_data,'xlsx'), 'bordereauxOfficiel_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new BordereauxOfficielImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('bordereauxOfficiels.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('bordereauxOfficiels.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgTableauBord::bordereauxOfficiel.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getBordereauxOfficiels()
    {
        $bordereauxOfficiels = $this->bordereauxOfficielService->all();
        return response()->json($bordereauxOfficiels);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (BordereauxOfficiel) par ID, en format JSON.
     */
    public function getBordereauxOfficiel(Request $request, $id)
    {
        try {
            $bordereauxOfficiel = $this->bordereauxOfficielService->find($id);
            return response()->json($bordereauxOfficiel);
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
        $updatedBordereauxOfficiel = $this->bordereauxOfficielService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedBordereauxOfficiel],
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
        $bordereauxOfficielRequest = new BordereauxOfficielRequest();
        $fullRules = $bordereauxOfficielRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:bordereaux_officiels,id'];
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
        $itemBordereauxOfficiel = BordereauxOfficiel::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemBordereauxOfficiel, $field);
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
        $itemBordereauxOfficiel = BordereauxOfficiel::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemBordereauxOfficiel);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemBordereauxOfficiel, $changes);

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