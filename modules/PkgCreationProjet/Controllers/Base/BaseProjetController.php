<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgSessions\Services\SessionFormationService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgCreationProjet\Services\MobilisationUaService;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgCreationProjet\Services\ResourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\ProjetRequest;
use Modules\PkgCreationProjet\Models\Projet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\App\Exports\ProjetExport;
use Modules\PkgCreationProjet\App\Imports\ProjetImport;
use Modules\Core\Services\ContextState;

class BaseProjetController extends AdminController
{
    protected $projetService;
    protected $filiereService;
    protected $formateurService;
    protected $sessionFormationService;

    public function __construct(ProjetService $projetService, FiliereService $filiereService, FormateurService $formateurService, SessionFormationService $sessionFormationService) {
        parent::__construct();
        $this->service  =  $projetService;
        $this->projetService = $projetService;
        $this->filiereService = $filiereService;
        $this->formateurService = $formateurService;
        $this->sessionFormationService = $sessionFormationService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('projet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('projet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.projet.formateur_id') == null){
           $this->viewState->init('filter.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        // scopeDataByRole
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.sessionFormation.filiere.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.filiere.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
        }

         // Extraire les paramètres de recherche, pagination, filtres
        $projets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'projets_search',
                $this->viewState->get("filter.projet.projets_search")
            )],
            $request->except(['projets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->projetService->prepareDataForIndexView($projets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationProjet::projet._index', $projet_compact_value)->render();
            }else{
                return view($projet_partialViewName, $projet_compact_value)->render();
            }
        }

        return view('PkgCreationProjet::projet.index', $projet_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.sessionFormation.filiere.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.filiere.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
        }
        $itemProjet = $this->projetService->createInstance();
        

        $sessionFormations = $this->sessionFormationService->all();
        $filieres = $this->filiereService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('bulkEdit' ,'itemProjet', 'filieres', 'formateurs', 'sessionFormations'));
        }
        return view('PkgCreationProjet::projet.create', compact('bulkEdit' ,'itemProjet', 'filieres', 'formateurs', 'sessionFormations'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $projet_ids = $request->input('ids', []);

        if (!is_array($projet_ids) || count($projet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.sessionFormation.filiere.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.filiere.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
        }
         $itemProjet = $this->projetService->find($projet_ids[0]);
         
 
        $sessionFormations = $this->sessionFormationService->getAllForSelect($itemProjet->sessionFormation);
        $filieres = $this->filiereService->getAllForSelect($itemProjet->filiere);
        $formateurs = $this->formateurService->getAllForSelect($itemProjet->formateur);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemProjet = $this->projetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('bulkEdit', 'projet_ids', 'itemProjet', 'filieres', 'formateurs', 'sessionFormations'));
        }
        return view('PkgCreationProjet::projet.bulk-edit', compact('bulkEdit', 'projet_ids', 'itemProjet', 'filieres', 'formateurs', 'sessionFormations'));
    }
    /**
     */
    public function store(ProjetRequest $request) {
        $validatedData = $request->validated();
        $projet = $this->projetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $projet,
                'modelName' => __('PkgCreationProjet::projet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $projet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('projets.edit', ['projet' => $projet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $projet,
                'modelName' => __('PkgCreationProjet::projet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('projet.show_' . $id);

        $itemProjet = $this->projetService->edit($id);
        $this->authorize('view', $itemProjet);


        $this->viewState->set('scope.mobilisationUa.projet_id', $id);
        

        $mobilisationUaService =  new MobilisationUaService();
        $mobilisationUas_view_data = $mobilisationUaService->prepareDataForIndexView();
        extract($mobilisationUas_view_data);

        $this->viewState->set('scope.tache.projet_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        $this->viewState->set('scope.livrable.projet_id', $id);
        

        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        $this->viewState->set('scope.resource.projet_id', $id);
        

        $resourceService =  new ResourceService();
        $resources_view_data = $resourceService->prepareDataForIndexView();
        extract($resources_view_data);

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._show', array_merge(compact('itemProjet'),$mobilisationUa_compact_value, $tache_compact_value, $livrable_compact_value, $resource_compact_value));
        }

        return view('PkgCreationProjet::projet.show', array_merge(compact('itemProjet'),$mobilisationUa_compact_value, $tache_compact_value, $livrable_compact_value, $resource_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('projet.edit_' . $id);

        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.sessionFormation.filiere.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.filiere.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
        }

        $itemProjet = $this->projetService->edit($id);
        $this->authorize('edit', $itemProjet);


        $sessionFormations = $this->sessionFormationService->getAllForSelect($itemProjet->sessionFormation);
        $filieres = $this->filiereService->getAllForSelect($itemProjet->filiere);
        $formateurs = $this->formateurService->getAllForSelect($itemProjet->formateur);


        $this->viewState->set('scope.affectationProjet.projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemProjet->getNestedValue('formateur_id');
        $key = 'scope.groupe.formateurs.formateur_id';
        $this->viewState->set($key, $value);

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.mobilisationUa.projet_id', $id);
        

        $mobilisationUaService =  new MobilisationUaService();
        $mobilisationUas_view_data = $mobilisationUaService->prepareDataForIndexView();
        extract($mobilisationUas_view_data);

        $this->viewState->set('scope.tache.projet_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        $this->viewState->set('scope.livrable.projet_id', $id);
        

        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        $this->viewState->set('scope.resource.projet_id', $id);
        

        $resourceService =  new ResourceService();
        $resources_view_data = $resourceService->prepareDataForIndexView();
        extract($resources_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._edit', array_merge(compact('bulkEdit' , 'itemProjet','filieres', 'formateurs', 'sessionFormations'),$affectationProjet_compact_value, $mobilisationUa_compact_value, $tache_compact_value, $livrable_compact_value, $resource_compact_value));
        }

        return view('PkgCreationProjet::projet.edit', array_merge(compact('bulkEdit' ,'itemProjet','filieres', 'formateurs', 'sessionFormations'),$affectationProjet_compact_value, $mobilisationUa_compact_value, $tache_compact_value, $livrable_compact_value, $resource_compact_value));


    }
    /**
     */
    public function update(ProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $projet = $this->projetService->find($id);
        $this->authorize('update', $projet);

        $validatedData = $request->validated();
        $projet = $this->projetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $projet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('projets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')
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
            'projet_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('projet_ids', []);
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
        $form         = new \Modules\PkgCreationProjet\App\Requests\ProjetRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->projetService->find($id);
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
        $projet = $this->projetService->find($id);
        $this->authorize('delete', $projet);

        $projet = $this->projetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('projets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $projet_ids = $request->input('ids', []);
        if (!is_array($projet_ids) || count($projet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($projet_ids as $id) {
            $entity = $this->projetService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $projet = $this->projetService->find($id);
            $this->authorize('delete', $projet);
            $this->projetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($projet_ids) . ' éléments',
            'modelName' => __('PkgCreationProjet::projet.plural')
        ]));
    }

    public function export($format)
    {
        $projets_data = $this->projetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ProjetExport($projets_data,'csv'), 'projet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ProjetExport($projets_data,'xlsx'), 'projet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('projets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('projets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::projet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getProjets()
    {
        $projets = $this->projetService->all();
        return response()->json($projets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Projet) par ID, en format JSON.
     */
    public function getProjet(Request $request, $id)
    {
        try {
            $projet = $this->projetService->find($id);
            return response()->json($projet);
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
        $updatedProjet = $this->projetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedProjet],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
    }
    
    public function clonerProjet(Request $request, string $id) {
        $projet = $this->projetService->clonerProjet($id);
        if ($request->ajax()) {
            $message = "Le projet a été cloné avec succès.";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('Projet.index')->with(
            'success',
            "Le projet a été cloné avec succès."
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
        $projetRequest = new ProjetRequest();
        $fullRules = $projetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:projets,id'];
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
        $itemProjet = Projet::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemProjet, $field);
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
        $itemProjet = Projet::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemProjet);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemProjet, $changes);

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