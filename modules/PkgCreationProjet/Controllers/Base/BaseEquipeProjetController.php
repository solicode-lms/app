<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\EquipeProjetService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\Core\Services\SysColorService;
use Modules\PkgCreationTache\Services\TacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\EquipeProjetRequest;
use Modules\PkgCreationProjet\Models\EquipeProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\App\Exports\EquipeProjetExport;
use Modules\PkgCreationProjet\App\Imports\EquipeProjetImport;
use Modules\Core\Services\ContextState;

class BaseEquipeProjetController extends AdminController
{
    protected $equipeProjetService;
    protected $apprenantService;
    protected $projetService;
    protected $sysColorService;

    public function __construct(EquipeProjetService $equipeProjetService, ApprenantService $apprenantService, ProjetService $projetService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $equipeProjetService;
        $this->equipeProjetService = $equipeProjetService;
        $this->apprenantService = $apprenantService;
        $this->projetService = $projetService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('equipeProjet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('equipeProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.equipeProjet.Projet.formateur.user_id') == null){
           $this->viewState->init('scope.equipeProjet.Projet.formateur.user_id'  , $this->sessionState->get('user_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $equipeProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'equipeProjets_search',
                $this->viewState->get("filter.equipeProjet.equipeProjets_search")
            )],
            $request->except(['equipeProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->equipeProjetService->prepareDataForIndexView($equipeProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationProjet::equipeProjet._index', $equipeProjet_compact_value)->render();
            }else{
                return view($equipeProjet_partialViewName, $equipeProjet_compact_value)->render();
            }
        }

        return view('PkgCreationProjet::equipeProjet.index', $equipeProjet_compact_value);
    }
    /**
     */
    protected function dataForCreateView() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.equipeProjet.Projet.formateur.user_id'  , $this->sessionState->get('user_id'));
        }

        // scopeDataByRole
        $itemEquipeProjet = $this->equipeProjetService->createInstance();


        $sysColors = $this->sysColorService->all();
        $projets = $this->projetService->all();
        $apprenants = $this->apprenantService->all();

        $bulkEdit = false;
        return compact('bulkEdit' ,'itemEquipeProjet', 'sysColors', 'projets', 'apprenants');

    }
    /**
     */
    public function create() {
        $viewData = $this->dataForCreateView();

        if (request()->ajax()) {
            return view('PkgCreationProjet::equipeProjet._fields', $viewData);
        }

        return view('PkgCreationProjet::equipeProjet.create', $viewData);
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $equipeProjet_ids = $request->input('ids', []);

        if (!is_array($equipeProjet_ids) || count($equipeProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.equipeProjet.Projet.formateur.user_id'  , $this->sessionState->get('user_id'));
        }
 
         $itemEquipeProjet = $this->equipeProjetService->find($equipeProjet_ids[0]);
         
 
        $apprenants = $this->apprenantService->getAllForSelect($itemEquipeProjet->apprenants);
        $sysColors = $this->sysColorService->getAllForSelect($itemEquipeProjet->sysColor);
        $projets = $this->projetService->getAllForSelect($itemEquipeProjet->projet);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEquipeProjet = $this->equipeProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationProjet::equipeProjet._fields', compact('bulkEdit', 'equipeProjet_ids', 'itemEquipeProjet', 'sysColors', 'projets', 'apprenants'));
        }
        return view('PkgCreationProjet::equipeProjet.bulk-edit', compact('bulkEdit', 'equipeProjet_ids', 'itemEquipeProjet', 'sysColors', 'projets', 'apprenants'));
    }
    /**
     */
    public function store(EquipeProjetRequest $request) {
        $validatedData = $request->validated();
        $equipeProjet = $this->equipeProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $equipeProjet,
                'modelName' => __('PkgCreationProjet::equipeProjet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $equipeProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('equipeProjets.edit', ['equipeProjet' => $equipeProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $equipeProjet,
                'modelName' => __('PkgCreationProjet::equipeProjet.singular')
            ])
        );
    }
    /**
     */
    protected function dataForShowView(string $id) {
        $this->viewState->setContextKey('equipeProjet.show_' . $id);

        $itemEquipeProjet = $this->equipeProjetService->edit($id);
        $this->authorize('view', $itemEquipeProjet);


        $this->viewState->set('scope.tache.equipe_projet_id', $id);


        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        return array_merge(compact('itemEquipeProjet'),$tache_compact_value);

    }
    /**
     */
    public function show(string $id) {
        $viewData = $this->dataForShowView($id);

        if (request()->ajax()) {
            return view('PkgCreationProjet::equipeProjet._show', $viewData);
        }

        return view('PkgCreationProjet::equipeProjet.show', $viewData);

    }
    /**
     */
    protected function dataForEditView(string $id) {

        $this->viewState->setContextKey('equipeProjet.edit_' . $id);


        $itemEquipeProjet = $this->equipeProjetService->edit($id);
        $this->authorize('edit', $itemEquipeProjet);


        $apprenants = $this->apprenantService->getAllForSelect($itemEquipeProjet->apprenants);
        $sysColors = $this->sysColorService->getAllForSelect($itemEquipeProjet->sysColor);
        $projets = $this->projetService->getAllForSelect($itemEquipeProjet->projet);


        $this->viewState->set('scope.tache.equipe_projet_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        $bulkEdit = false;

        $viewData = array_merge(compact('bulkEdit' , 'itemEquipeProjet','sysColors', 'projets', 'apprenants'),$tache_compact_value);

        return $viewData;

    }
    /**
     */
    public function edit(string $id) {
        $viewData = $this->dataForEditView($id);

        if (request()->ajax()) {
            return view('PkgCreationProjet::equipeProjet._edit', $viewData);
        }

        return view('PkgCreationProjet::equipeProjet.edit', $viewData);

    }
    /**
     */
    public function update(EquipeProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $equipeProjet = $this->equipeProjetService->find($id);
        $this->authorize('update', $equipeProjet);

        $validatedData = $request->validated();
        $equipeProjet = $this->equipeProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $equipeProjet,
                'modelName' =>  __('PkgCreationProjet::equipeProjet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $equipeProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('equipeProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $equipeProjet,
                'modelName' =>  __('PkgCreationProjet::equipeProjet.singular')
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
            'equipeProjet_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('equipeProjet_ids', []);
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
        $form         = new \Modules\PkgCreationProjet\App\Requests\EquipeProjetRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->equipeProjetService->find($id);
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
        $equipeProjet = $this->equipeProjetService->find($id);
        $this->authorize('delete', $equipeProjet);

        $equipeProjet = $this->equipeProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $equipeProjet,
                'modelName' =>  __('PkgCreationProjet::equipeProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('equipeProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $equipeProjet,
                'modelName' =>  __('PkgCreationProjet::equipeProjet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $equipeProjet_ids = $request->input('ids', []);
        if (!is_array($equipeProjet_ids) || count($equipeProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($equipeProjet_ids as $id) {
            $entity = $this->equipeProjetService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $equipeProjet = $this->equipeProjetService->find($id);
            $this->authorize('delete', $equipeProjet);
            $this->equipeProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($equipeProjet_ids) . ' éléments',
            'modelName' => __('PkgCreationProjet::equipeProjet.plural')
        ]));
    }

    public function export($format)
    {
        $equipeProjets_data = $this->equipeProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EquipeProjetExport($equipeProjets_data,'csv'), 'equipeProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EquipeProjetExport($equipeProjets_data,'xlsx'), 'equipeProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EquipeProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('equipeProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('equipeProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::equipeProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEquipeProjets()
    {
        $equipeProjets = $this->equipeProjetService->all();
        return response()->json($equipeProjets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EquipeProjet) par ID, en format JSON.
     */
    public function getEquipeProjet(Request $request, $id)
    {
        try {
            $equipeProjet = $this->equipeProjetService->find($id);
            return response()->json($equipeProjet);
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
        $updatedEquipeProjet = $this->equipeProjetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEquipeProjet],
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
        $equipeProjetRequest = new EquipeProjetRequest();
        $fullRules = $equipeProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:equipe_projets,id'];
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
        $itemEquipeProjet = EquipeProjet::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEquipeProjet, $field);
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
        $itemEquipeProjet = EquipeProjet::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEquipeProjet);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEquipeProjet, $changes);

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