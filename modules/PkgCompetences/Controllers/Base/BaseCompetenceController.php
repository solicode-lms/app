<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgFormation\Services\ModuleService;
use Modules\PkgCompetences\Services\MicroCompetenceService;
use Modules\PkgApprentissage\Services\RealisationCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\CompetenceRequest;
use Modules\PkgCompetences\Models\Competence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\App\Exports\CompetenceExport;
use Modules\PkgCompetences\App\Imports\CompetenceImport;
use Modules\Core\Services\ContextState;

class BaseCompetenceController extends AdminController
{
    protected $competenceService;
    protected $moduleService;

    public function __construct(CompetenceService $competenceService, ModuleService $moduleService) {
        parent::__construct();
        $this->service  =  $competenceService;
        $this->competenceService = $competenceService;
        $this->moduleService = $moduleService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('competence.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('competence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.competence.module.filiere.groupes.formateurs.user_id') == null){
           $this->viewState->init('scope.competence.module.filiere.groupes.formateurs.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('scope.competence.module.filiere.groupes.apprenants.user_id') == null){
           $this->viewState->init('scope.competence.module.filiere.groupes.apprenants.user_id'  , $this->sessionState->get('user_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $competences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'competences_search',
                $this->viewState->get("filter.competence.competences_search")
            )],
            $request->except(['competences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->competenceService->prepareDataForIndexView($competences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::competence._index', $competence_compact_value)->render();
            }else{
                return view($competence_partialViewName, $competence_compact_value)->render();
            }
        }

        return view('PkgCompetences::competence.index', $competence_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.competence.module.filiere.groupes.formateurs.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.competence.module.filiere.groupes.apprenants.user_id'  , $this->sessionState->get('user_id'));
        }


        $itemCompetence = $this->competenceService->createInstance();
        

        $modules = $this->moduleService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('bulkEdit' ,'itemCompetence', 'modules'));
        }
        return view('PkgCompetences::competence.create', compact('bulkEdit' ,'itemCompetence', 'modules'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $competence_ids = $request->input('ids', []);

        if (!is_array($competence_ids) || count($competence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.competence.module.filiere.groupes.formateurs.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.competence.module.filiere.groupes.apprenants.user_id'  , $this->sessionState->get('user_id'));
        }
 
         $itemCompetence = $this->competenceService->find($competence_ids[0]);
         
 
        $modules = $this->moduleService->getAllForSelect($itemCompetence->module);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemCompetence = $this->competenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('bulkEdit', 'competence_ids', 'itemCompetence', 'modules'));
        }
        return view('PkgCompetences::competence.bulk-edit', compact('bulkEdit', 'competence_ids', 'itemCompetence', 'modules'));
    }
    /**
     */
    public function store(CompetenceRequest $request) {
        $validatedData = $request->validated();
        $competence = $this->competenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $competence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('competences.edit', ['competence' => $competence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('competence.show_' . $id);

        $itemCompetence = $this->competenceService->edit($id);
        $this->authorize('view', $itemCompetence);


        $this->viewState->set('scope.microCompetence.competence_id', $id);
        

        $microCompetenceService =  new MicroCompetenceService();
        $microCompetences_view_data = $microCompetenceService->prepareDataForIndexView();
        extract($microCompetences_view_data);

        $this->viewState->set('scope.realisationCompetence.competence_id', $id);
        

        $realisationCompetenceService =  new RealisationCompetenceService();
        $realisationCompetences_view_data = $realisationCompetenceService->prepareDataForIndexView();
        extract($realisationCompetences_view_data);

        if (request()->ajax()) {
            return view('PkgCompetences::competence._show', array_merge(compact('itemCompetence'),$microCompetence_compact_value, $realisationCompetence_compact_value));
        }

        return view('PkgCompetences::competence.show', array_merge(compact('itemCompetence'),$microCompetence_compact_value, $realisationCompetence_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('competence.edit_' . $id);


        $itemCompetence = $this->competenceService->edit($id);
        $this->authorize('edit', $itemCompetence);


        $modules = $this->moduleService->getAllForSelect($itemCompetence->module);


        $this->viewState->set('scope.microCompetence.competence_id', $id);
        

        $microCompetenceService =  new MicroCompetenceService();
        $microCompetences_view_data = $microCompetenceService->prepareDataForIndexView();
        extract($microCompetences_view_data);

        $this->viewState->set('scope.realisationCompetence.competence_id', $id);
        

        $realisationCompetenceService =  new RealisationCompetenceService();
        $realisationCompetences_view_data = $realisationCompetenceService->prepareDataForIndexView();
        extract($realisationCompetences_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::competence._edit', array_merge(compact('bulkEdit' , 'itemCompetence','modules'),$microCompetence_compact_value, $realisationCompetence_compact_value));
        }

        return view('PkgCompetences::competence.edit', array_merge(compact('bulkEdit' ,'itemCompetence','modules'),$microCompetence_compact_value, $realisationCompetence_compact_value));


    }
    /**
     */
    public function update(CompetenceRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $competence = $this->competenceService->find($id);
        $this->authorize('update', $competence);

        $validatedData = $request->validated();
        $competence = $this->competenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $competence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
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
            'competence_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('competence_ids', []);
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
        $form         = new \Modules\PkgCompetences\App\Requests\CompetenceRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->competenceService->find($id);
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
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $competence = $this->competenceService->find($id);
        $this->authorize('delete', $competence);

        $competence = $this->competenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $competence_ids = $request->input('ids', []);
        if (!is_array($competence_ids) || count($competence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($competence_ids as $id) {
            $entity = $this->competenceService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $competence = $this->competenceService->find($id);
            $this->authorize('delete', $competence);
            $this->competenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($competence_ids) . ' éléments',
            'modelName' => __('PkgCompetences::competence.plural')
        ]));
    }

    public function export($format)
    {
        $competences_data = $this->competenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new CompetenceExport($competences_data,'csv'), 'competence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new CompetenceExport($competences_data,'xlsx'), 'competence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new CompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('competences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('competences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::competence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCompetences()
    {
        $competences = $this->competenceService->all();
        return response()->json($competences);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Competence) par ID, en format JSON.
     */
    public function getCompetence(Request $request, $id)
    {
        try {
            $competence = $this->competenceService->find($id);
            return response()->json($competence);
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
        $updatedCompetence = $this->competenceService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedCompetence],
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
        $competenceRequest = new CompetenceRequest();
        $fullRules = $competenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:competences,id'];
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
        $itemCompetence = Competence::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemCompetence, $field);
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
        $itemCompetence = Competence::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemCompetence);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemCompetence, $changes);

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