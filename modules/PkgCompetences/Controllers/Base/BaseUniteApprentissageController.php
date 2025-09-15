<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Modules\PkgCompetences\Services\MicroCompetenceService;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgCompetences\Services\CritereEvaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\UniteApprentissageRequest;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\App\Exports\UniteApprentissageExport;
use Modules\PkgCompetences\App\Imports\UniteApprentissageImport;
use Modules\Core\Services\ContextState;

class BaseUniteApprentissageController extends AdminController
{
    protected $uniteApprentissageService;
    protected $microCompetenceService;

    public function __construct(UniteApprentissageService $uniteApprentissageService, MicroCompetenceService $microCompetenceService) {
        parent::__construct();
        $this->service  =  $uniteApprentissageService;
        $this->uniteApprentissageService = $uniteApprentissageService;
        $this->microCompetenceService = $microCompetenceService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('uniteApprentissage.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('uniteApprentissage');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $uniteApprentissages_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'uniteApprentissages_search',
                $this->viewState->get("filter.uniteApprentissage.uniteApprentissages_search")
            )],
            $request->except(['uniteApprentissages_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->uniteApprentissageService->prepareDataForIndexView($uniteApprentissages_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::uniteApprentissage._index', $uniteApprentissage_compact_value)->render();
            }else{
                return view($uniteApprentissage_partialViewName, $uniteApprentissage_compact_value)->render();
            }
        }

        return view('PkgCompetences::uniteApprentissage.index', $uniteApprentissage_compact_value);
    }
    /**
     */
    public function create() {


        $itemUniteApprentissage = $this->uniteApprentissageService->createInstance();
        

        $microCompetences = $this->microCompetenceService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::uniteApprentissage._fields', compact('bulkEdit' ,'itemUniteApprentissage', 'microCompetences'));
        }
        return view('PkgCompetences::uniteApprentissage.create', compact('bulkEdit' ,'itemUniteApprentissage', 'microCompetences'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $uniteApprentissage_ids = $request->input('ids', []);

        if (!is_array($uniteApprentissage_ids) || count($uniteApprentissage_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemUniteApprentissage = $this->uniteApprentissageService->find($uniteApprentissage_ids[0]);
         
 
        $microCompetences = $this->microCompetenceService->getAllForSelect($itemUniteApprentissage->microCompetence);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemUniteApprentissage = $this->uniteApprentissageService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::uniteApprentissage._fields', compact('bulkEdit', 'uniteApprentissage_ids', 'itemUniteApprentissage', 'microCompetences'));
        }
        return view('PkgCompetences::uniteApprentissage.bulk-edit', compact('bulkEdit', 'uniteApprentissage_ids', 'itemUniteApprentissage', 'microCompetences'));
    }
    /**
     */
    public function store(UniteApprentissageRequest $request) {
        $validatedData = $request->validated();
        $uniteApprentissage = $this->uniteApprentissageService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' => __('PkgCompetences::uniteApprentissage.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $uniteApprentissage->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('uniteApprentissages.edit', ['uniteApprentissage' => $uniteApprentissage->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' => __('PkgCompetences::uniteApprentissage.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('uniteApprentissage.show_' . $id);

        $itemUniteApprentissage = $this->uniteApprentissageService->edit($id);


        $this->viewState->set('scope.chapitre.unite_apprentissage_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.critereEvaluation.unite_apprentissage_id', $id);
        

        $critereEvaluationService =  new CritereEvaluationService();
        $critereEvaluations_view_data = $critereEvaluationService->prepareDataForIndexView();
        extract($critereEvaluations_view_data);

        if (request()->ajax()) {
            return view('PkgCompetences::uniteApprentissage._show', array_merge(compact('itemUniteApprentissage'),$chapitre_compact_value, $critereEvaluation_compact_value));
        }

        return view('PkgCompetences::uniteApprentissage.show', array_merge(compact('itemUniteApprentissage'),$chapitre_compact_value, $critereEvaluation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('uniteApprentissage.edit_' . $id);


        $itemUniteApprentissage = $this->uniteApprentissageService->edit($id);


        $microCompetences = $this->microCompetenceService->getAllForSelect($itemUniteApprentissage->microCompetence);


        $this->viewState->set('scope.chapitre.unite_apprentissage_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.critereEvaluation.unite_apprentissage_id', $id);
        

        $critereEvaluationService =  new CritereEvaluationService();
        $critereEvaluations_view_data = $critereEvaluationService->prepareDataForIndexView();
        extract($critereEvaluations_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::uniteApprentissage._edit', array_merge(compact('bulkEdit' , 'itemUniteApprentissage','microCompetences'),$chapitre_compact_value, $critereEvaluation_compact_value));
        }

        return view('PkgCompetences::uniteApprentissage.edit', array_merge(compact('bulkEdit' ,'itemUniteApprentissage','microCompetences'),$chapitre_compact_value, $critereEvaluation_compact_value));


    }
    /**
     */
    public function update(UniteApprentissageRequest $request, string $id) {

        $validatedData = $request->validated();
        $uniteApprentissage = $this->uniteApprentissageService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' =>  __('PkgCompetences::uniteApprentissage.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $uniteApprentissage->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('uniteApprentissages.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' =>  __('PkgCompetences::uniteApprentissage.singular')
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
            'uniteApprentissage_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('uniteApprentissage_ids', []);
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
        $form         = new \Modules\PkgCompetences\App\Requests\UniteApprentissageRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->uniteApprentissageService->find($id);
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

        $uniteApprentissage = $this->uniteApprentissageService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' =>  __('PkgCompetences::uniteApprentissage.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('uniteApprentissages.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' =>  __('PkgCompetences::uniteApprentissage.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $uniteApprentissage_ids = $request->input('ids', []);
        if (!is_array($uniteApprentissage_ids) || count($uniteApprentissage_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($uniteApprentissage_ids as $id) {
            $entity = $this->uniteApprentissageService->find($id);
            $this->uniteApprentissageService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($uniteApprentissage_ids) . ' éléments',
            'modelName' => __('PkgCompetences::uniteApprentissage.plural')
        ]));
    }

    public function export($format)
    {
        $uniteApprentissages_data = $this->uniteApprentissageService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new UniteApprentissageExport($uniteApprentissages_data,'csv'), 'uniteApprentissage_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new UniteApprentissageExport($uniteApprentissages_data,'xlsx'), 'uniteApprentissage_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new UniteApprentissageImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('uniteApprentissages.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('uniteApprentissages.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::uniteApprentissage.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getUniteApprentissages()
    {
        $uniteApprentissages = $this->uniteApprentissageService->all();
        return response()->json($uniteApprentissages);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (UniteApprentissage) par ID, en format JSON.
     */
    public function getUniteApprentissage(Request $request, $id)
    {
        try {
            $uniteApprentissage = $this->uniteApprentissageService->find($id);
            return response()->json($uniteApprentissage);
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
        $updatedUniteApprentissage = $this->uniteApprentissageService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedUniteApprentissage],
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
        $uniteApprentissageRequest = new UniteApprentissageRequest();
        $fullRules = $uniteApprentissageRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:unite_apprentissages,id'];
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
        $itemUniteApprentissage = UniteApprentissage::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemUniteApprentissage, $field);
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
        $itemUniteApprentissage = UniteApprentissage::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemUniteApprentissage);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemUniteApprentissage, $changes);

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