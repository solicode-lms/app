<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\MicroCompetenceService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\MicroCompetenceRequest;
use Modules\PkgCompetences\Models\MicroCompetence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\App\Exports\MicroCompetenceExport;
use Modules\PkgCompetences\App\Imports\MicroCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseMicroCompetenceController extends AdminController
{
    protected $microCompetenceService;
    protected $competenceService;

    public function __construct(MicroCompetenceService $microCompetenceService, CompetenceService $competenceService) {
        parent::__construct();
        $this->service  =  $microCompetenceService;
        $this->microCompetenceService = $microCompetenceService;
        $this->competenceService = $competenceService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('microCompetence.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('microCompetence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $microCompetences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'microCompetences_search',
                $this->viewState->get("filter.microCompetence.microCompetences_search")
            )],
            $request->except(['microCompetences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->microCompetenceService->prepareDataForIndexView($microCompetences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::microCompetence._index', $microCompetence_compact_value)->render();
            }else{
                return view($microCompetence_partialViewName, $microCompetence_compact_value)->render();
            }
        }

        return view('PkgCompetences::microCompetence.index', $microCompetence_compact_value);
    }
    /**
     */
    public function create() {


        $itemMicroCompetence = $this->microCompetenceService->createInstance();
        

        $competences = $this->competenceService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::microCompetence._fields', compact('bulkEdit' ,'itemMicroCompetence', 'competences'));
        }
        return view('PkgCompetences::microCompetence.create', compact('bulkEdit' ,'itemMicroCompetence', 'competences'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $microCompetence_ids = $request->input('ids', []);

        if (!is_array($microCompetence_ids) || count($microCompetence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemMicroCompetence = $this->microCompetenceService->find($microCompetence_ids[0]);
         
 
        $competences = $this->competenceService->getAllForSelect($itemMicroCompetence->competence);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemMicroCompetence = $this->microCompetenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::microCompetence._fields', compact('bulkEdit', 'microCompetence_ids', 'itemMicroCompetence', 'competences'));
        }
        return view('PkgCompetences::microCompetence.bulk-edit', compact('bulkEdit', 'microCompetence_ids', 'itemMicroCompetence', 'competences'));
    }
    /**
     */
    public function store(MicroCompetenceRequest $request) {
        $validatedData = $request->validated();
        $microCompetence = $this->microCompetenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $microCompetence,
                'modelName' => __('PkgCompetences::microCompetence.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $microCompetence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('microCompetences.edit', ['microCompetence' => $microCompetence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $microCompetence,
                'modelName' => __('PkgCompetences::microCompetence.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('microCompetence.show_' . $id);

        $itemMicroCompetence = $this->microCompetenceService->edit($id);


        $this->viewState->set('scope.uniteApprentissage.micro_competence_id', $id);
        

        $uniteApprentissageService =  new UniteApprentissageService();
        $uniteApprentissages_view_data = $uniteApprentissageService->prepareDataForIndexView();
        extract($uniteApprentissages_view_data);

        if (request()->ajax()) {
            return view('PkgCompetences::microCompetence._show', array_merge(compact('itemMicroCompetence'),$uniteApprentissage_compact_value));
        }

        return view('PkgCompetences::microCompetence.show', array_merge(compact('itemMicroCompetence'),$uniteApprentissage_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('microCompetence.edit_' . $id);


        $itemMicroCompetence = $this->microCompetenceService->edit($id);


        $competences = $this->competenceService->getAllForSelect($itemMicroCompetence->competence);


        $this->viewState->set('scope.uniteApprentissage.micro_competence_id', $id);
        

        $uniteApprentissageService =  new UniteApprentissageService();
        $uniteApprentissages_view_data = $uniteApprentissageService->prepareDataForIndexView();
        extract($uniteApprentissages_view_data);

        $this->viewState->set('scope.realisationMicroCompetence.micro_competence_id', $id);
        

        $realisationMicroCompetenceService =  new RealisationMicroCompetenceService();
        $realisationMicroCompetences_view_data = $realisationMicroCompetenceService->prepareDataForIndexView();
        extract($realisationMicroCompetences_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::microCompetence._edit', array_merge(compact('bulkEdit' , 'itemMicroCompetence','competences'),$uniteApprentissage_compact_value, $realisationMicroCompetence_compact_value));
        }

        return view('PkgCompetences::microCompetence.edit', array_merge(compact('bulkEdit' ,'itemMicroCompetence','competences'),$uniteApprentissage_compact_value, $realisationMicroCompetence_compact_value));


    }
    /**
     */
    public function update(MicroCompetenceRequest $request, string $id) {

        $validatedData = $request->validated();
        $microCompetence = $this->microCompetenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $microCompetence,
                'modelName' =>  __('PkgCompetences::microCompetence.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $microCompetence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('microCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $microCompetence,
                'modelName' =>  __('PkgCompetences::microCompetence.singular')
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
            'microCompetence_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('microCompetence_ids', []);
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
        $form         = new \Modules\PkgCompetences\App\Requests\MicroCompetenceRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->microCompetenceService->find($id);
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

        $microCompetence = $this->microCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $microCompetence,
                'modelName' =>  __('PkgCompetences::microCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('microCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $microCompetence,
                'modelName' =>  __('PkgCompetences::microCompetence.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $microCompetence_ids = $request->input('ids', []);
        if (!is_array($microCompetence_ids) || count($microCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($microCompetence_ids as $id) {
            $entity = $this->microCompetenceService->find($id);
            $this->microCompetenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($microCompetence_ids) . ' éléments',
            'modelName' => __('PkgCompetences::microCompetence.plural')
        ]));
    }

    public function export($format)
    {
        $microCompetences_data = $this->microCompetenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new MicroCompetenceExport($microCompetences_data,'csv'), 'microCompetence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new MicroCompetenceExport($microCompetences_data,'xlsx'), 'microCompetence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new MicroCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('microCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('microCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::microCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getMicroCompetences()
    {
        $microCompetences = $this->microCompetenceService->all();
        return response()->json($microCompetences);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (MicroCompetence) par ID, en format JSON.
     */
    public function getMicroCompetence(Request $request, $id)
    {
        try {
            $microCompetence = $this->microCompetenceService->find($id);
            return response()->json($microCompetence);
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
        $updatedMicroCompetence = $this->microCompetenceService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedMicroCompetence],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
    }
    
    public function startFormation(Request $request, string $id) {
        $microCompetence = $this->microCompetenceService->startFormation($id);
        if ($request->ajax()) {
            $message = "La formation a été lancée avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('MicroCompetence.index')->with(
            'success',
            "La formation a été lancée avec succès"
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
        $microCompetenceRequest = new MicroCompetenceRequest();
        $fullRules = $microCompetenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:micro_competences,id'];
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
        $itemMicroCompetence = MicroCompetence::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemMicroCompetence, $field);
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
        $itemMicroCompetence = MicroCompetence::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemMicroCompetence);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemMicroCompetence, $changes);

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