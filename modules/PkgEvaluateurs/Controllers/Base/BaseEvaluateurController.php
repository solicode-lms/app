<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgEvaluateurs\Controllers\Base;
use Modules\PkgEvaluateurs\Services\EvaluateurService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgEvaluateurs\App\Requests\EvaluateurRequest;
use Modules\PkgEvaluateurs\Models\Evaluateur;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgEvaluateurs\App\Exports\EvaluateurExport;
use Modules\PkgEvaluateurs\App\Imports\EvaluateurImport;
use Modules\Core\Services\ContextState;

class BaseEvaluateurController extends AdminController
{
    protected $evaluateurService;
    protected $affectationProjetService;
    protected $userService;

    public function __construct(EvaluateurService $evaluateurService, AffectationProjetService $affectationProjetService, UserService $userService) {
        parent::__construct();
        $this->service  =  $evaluateurService;
        $this->evaluateurService = $evaluateurService;
        $this->affectationProjetService = $affectationProjetService;
        $this->userService = $userService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('evaluateur.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('evaluateur');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $evaluateurs_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'evaluateurs_search',
                $this->viewState->get("filter.evaluateur.evaluateurs_search")
            )],
            $request->except(['evaluateurs_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->evaluateurService->prepareDataForIndexView($evaluateurs_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgEvaluateurs::evaluateur._index', $evaluateur_compact_value)->render();
            }else{
                return view($evaluateur_partialViewName, $evaluateur_compact_value)->render();
            }
        }

        return view('PkgEvaluateurs::evaluateur.index', $evaluateur_compact_value);
    }
    /**
     */
    public function create() {


        $itemEvaluateur = $this->evaluateurService->createInstance();
        

        $users = $this->userService->all();
        $affectationProjets = $this->affectationProjetService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluateur._fields', compact('bulkEdit' ,'itemEvaluateur', 'affectationProjets', 'users'));
        }
        return view('PkgEvaluateurs::evaluateur.create', compact('bulkEdit' ,'itemEvaluateur', 'affectationProjets', 'users'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $evaluateur_ids = $request->input('ids', []);

        if (!is_array($evaluateur_ids) || count($evaluateur_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEvaluateur = $this->evaluateurService->find($evaluateur_ids[0]);
         
 
        $users = $this->userService->getAllForSelect($itemEvaluateur->user);
        $affectationProjets = $this->affectationProjetService->getAllForSelect($itemEvaluateur->affectationProjets);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEvaluateur = $this->evaluateurService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluateur._fields', compact('bulkEdit', 'evaluateur_ids', 'itemEvaluateur', 'affectationProjets', 'users'));
        }
        return view('PkgEvaluateurs::evaluateur.bulk-edit', compact('bulkEdit', 'evaluateur_ids', 'itemEvaluateur', 'affectationProjets', 'users'));
    }
    /**
     */
    public function store(EvaluateurRequest $request) {
        $validatedData = $request->validated();
        $evaluateur = $this->evaluateurService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $evaluateur,
                'modelName' => __('PkgEvaluateurs::evaluateur.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $evaluateur->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('evaluateurs.edit', ['evaluateur' => $evaluateur->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $evaluateur,
                'modelName' => __('PkgEvaluateurs::evaluateur.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('evaluateur.show_' . $id);

        $itemEvaluateur = $this->evaluateurService->edit($id);


        $this->viewState->set('scope.evaluationRealisationProjet.evaluateur_id', $id);
        

        $evaluationRealisationProjetService =  new EvaluationRealisationProjetService();
        $evaluationRealisationProjets_view_data = $evaluationRealisationProjetService->prepareDataForIndexView();
        extract($evaluationRealisationProjets_view_data);

        $this->viewState->set('scope.evaluationRealisationTache.evaluateur_id', $id);
        

        $evaluationRealisationTacheService =  new EvaluationRealisationTacheService();
        $evaluationRealisationTaches_view_data = $evaluationRealisationTacheService->prepareDataForIndexView();
        extract($evaluationRealisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluateur._show', array_merge(compact('itemEvaluateur'),$evaluationRealisationProjet_compact_value, $evaluationRealisationTache_compact_value));
        }

        return view('PkgEvaluateurs::evaluateur.show', array_merge(compact('itemEvaluateur'),$evaluationRealisationProjet_compact_value, $evaluationRealisationTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('evaluateur.edit_' . $id);


        $itemEvaluateur = $this->evaluateurService->edit($id);


        $users = $this->userService->getAllForSelect($itemEvaluateur->user);
        $affectationProjets = $this->affectationProjetService->getAllForSelect($itemEvaluateur->affectationProjets);


        $this->viewState->set('scope.evaluationRealisationProjet.evaluateur_id', $id);
        

        $evaluationRealisationProjetService =  new EvaluationRealisationProjetService();
        $evaluationRealisationProjets_view_data = $evaluationRealisationProjetService->prepareDataForIndexView();
        extract($evaluationRealisationProjets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgEvaluateurs::evaluateur._edit', array_merge(compact('bulkEdit' , 'itemEvaluateur','affectationProjets', 'users'),$evaluationRealisationProjet_compact_value));
        }

        return view('PkgEvaluateurs::evaluateur.edit', array_merge(compact('bulkEdit' ,'itemEvaluateur','affectationProjets', 'users'),$evaluationRealisationProjet_compact_value));


    }
    /**
     */
    public function update(EvaluateurRequest $request, string $id) {

        $validatedData = $request->validated();
        $evaluateur = $this->evaluateurService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $evaluateur,
                'modelName' =>  __('PkgEvaluateurs::evaluateur.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $evaluateur->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('evaluateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $evaluateur,
                'modelName' =>  __('PkgEvaluateurs::evaluateur.singular')
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
            'evaluateur_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('evaluateur_ids', []);
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
        $form         = new \Modules\PkgEvaluateurs\App\Requests\EvaluateurRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->evaluateurService->find($id);
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

        $evaluateur = $this->evaluateurService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluateur,
                'modelName' =>  __('PkgEvaluateurs::evaluateur.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('evaluateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluateur,
                'modelName' =>  __('PkgEvaluateurs::evaluateur.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $evaluateur_ids = $request->input('ids', []);
        if (!is_array($evaluateur_ids) || count($evaluateur_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($evaluateur_ids as $id) {
            $entity = $this->evaluateurService->find($id);
            $this->evaluateurService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($evaluateur_ids) . ' éléments',
            'modelName' => __('PkgEvaluateurs::evaluateur.plural')
        ]));
    }

    public function export($format)
    {
        $evaluateurs_data = $this->evaluateurService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EvaluateurExport($evaluateurs_data,'csv'), 'evaluateur_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EvaluateurExport($evaluateurs_data,'xlsx'), 'evaluateur_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EvaluateurImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('evaluateurs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('evaluateurs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgEvaluateurs::evaluateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEvaluateurs()
    {
        $evaluateurs = $this->evaluateurService->all();
        return response()->json($evaluateurs);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Evaluateur) par ID, en format JSON.
     */
    public function getEvaluateur(Request $request, $id)
    {
        try {
            $evaluateur = $this->evaluateurService->find($id);
            return response()->json($evaluateur);
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
        $updatedEvaluateur = $this->evaluateurService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEvaluateur],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
    }
    
    public function initPassword(Request $request, string $id) {
        $evaluateur = $this->evaluateurService->initPassword($id);
        if ($request->ajax()) {
            $message = "Le mot de passe a été modifier avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('Evaluateur.index')->with(
            'success',
            "Le mot de passe a été modifier avec succès"
        );
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
        $evaluateurRequest = new EvaluateurRequest();
        $fullRules = $evaluateurRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:evaluateurs,id'];
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
        $itemEvaluateur = Evaluateur::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEvaluateur, $field);
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
        $itemEvaluateur = Evaluateur::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEvaluateur);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEvaluateur, $changes);

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