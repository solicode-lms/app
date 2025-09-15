<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Controllers\Base;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgRealisationTache\Services\TacheAffectationService;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;
use Modules\PkgRealisationTache\Services\HistoriqueRealisationTacheService;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\RealisationUaProjetService;
use Modules\PkgApprentissage\Services\RealisationUaPrototypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationTache\App\Requests\RealisationTacheRequest;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\App\Exports\RealisationTacheExport;
use Modules\PkgRealisationTache\App\Imports\RealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseRealisationTacheController extends AdminController
{
    protected $realisationTacheService;
    protected $etatRealisationTacheService;
    protected $realisationProjetService;
    protected $tacheAffectationService;
    protected $tacheService;

    public function __construct(RealisationTacheService $realisationTacheService, EtatRealisationTacheService $etatRealisationTacheService, RealisationProjetService $realisationProjetService, TacheAffectationService $tacheAffectationService, TacheService $tacheService) {
        parent::__construct();
        $this->service  =  $realisationTacheService;
        $this->realisationTacheService = $realisationTacheService;
        $this->etatRealisationTacheService = $etatRealisationTacheService;
        $this->realisationProjetService = $realisationProjetService;
        $this->tacheAffectationService = $tacheAffectationService;
        $this->tacheService = $tacheService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationTache.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id') == null){
           $this->viewState->init('filter.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('filter.realisationTache.RealisationProjet.Apprenant_id') == null){
           $this->viewState->init('filter.realisationTache.RealisationProjet.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $realisationTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationTaches_search',
                $this->viewState->get("filter.realisationTache.realisationTaches_search")
            )],
            $request->except(['realisationTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationTacheService->prepareDataForIndexView($realisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationTache::realisationTache._index', $realisationTache_compact_value)->render();
            }else{
                return view($realisationTache_partialViewName, $realisationTache_compact_value)->render();
            }
        }

        return view('PkgRealisationTache::realisationTache.index', $realisationTache_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationTache.RealisationProjet.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemRealisationTache = $this->realisationTacheService->createInstance();
        
        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);

        $taches = $this->tacheService->all();
        $etatRealisationTaches = $this->etatRealisationTacheService->all();
        $realisationProjets = $this->realisationProjetService->all();
        $tacheAffectations = $this->tacheAffectationService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationTache::realisationTache._fields', compact('bulkEdit' ,'itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'tacheAffectations', 'taches'));
        }
        return view('PkgRealisationTache::realisationTache.create', compact('bulkEdit' ,'itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'tacheAffectations', 'taches'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationTache_ids = $request->input('ids', []);

        if (!is_array($realisationTache_ids) || count($realisationTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationTache.RealisationProjet.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
 
         $itemRealisationTache = $this->realisationTacheService->find($realisationTache_ids[0]);
         
        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);
 
        $taches = $this->tacheService->getAllForSelect($itemRealisationTache->tache);
        $etatRealisationTaches = $this->etatRealisationTacheService->getAllForSelect($itemRealisationTache->etatRealisationTache);
        $realisationProjets = $this->realisationProjetService->getAllForSelect($itemRealisationTache->realisationProjet);
        $tacheAffectations = $this->tacheAffectationService->getAllForSelect($itemRealisationTache->tacheAffectation);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationTache = $this->realisationTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationTache::realisationTache._fields', compact('bulkEdit', 'realisationTache_ids', 'itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'tacheAffectations', 'taches'));
        }
        return view('PkgRealisationTache::realisationTache.bulk-edit', compact('bulkEdit', 'realisationTache_ids', 'itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'tacheAffectations', 'taches'));
    }
    /**
     */
    public function store(RealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $realisationTache = $this->realisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationTache,
                'modelName' => __('PkgRealisationTache::realisationTache.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('realisationTaches.edit', ['realisationTache' => $realisationTache->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationTache,
                'modelName' => __('PkgRealisationTache::realisationTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationTache.show_' . $id);

        $itemRealisationTache = $this->realisationTacheService->edit($id);
        $this->authorize('view', $itemRealisationTache);


        $this->viewState->set('scope.evaluationRealisationTache.realisation_tache_id', $id);
        

        $evaluationRealisationTacheService =  new EvaluationRealisationTacheService();
        $evaluationRealisationTaches_view_data = $evaluationRealisationTacheService->prepareDataForIndexView();
        extract($evaluationRealisationTaches_view_data);

        $this->viewState->set('scope.historiqueRealisationTache.realisation_tache_id', $id);
        

        $historiqueRealisationTacheService =  new HistoriqueRealisationTacheService();
        $historiqueRealisationTaches_view_data = $historiqueRealisationTacheService->prepareDataForIndexView();
        extract($historiqueRealisationTaches_view_data);

        $this->viewState->set('scope.realisationChapitre.realisation_tache_id', $id);
        

        $realisationChapitreService =  new RealisationChapitreService();
        $realisationChapitres_view_data = $realisationChapitreService->prepareDataForIndexView();
        extract($realisationChapitres_view_data);

        $this->viewState->set('scope.realisationUaProjet.realisation_tache_id', $id);
        

        $realisationUaProjetService =  new RealisationUaProjetService();
        $realisationUaProjets_view_data = $realisationUaProjetService->prepareDataForIndexView();
        extract($realisationUaProjets_view_data);

        $this->viewState->set('scope.realisationUaPrototype.realisation_tache_id', $id);
        

        $realisationUaPrototypeService =  new RealisationUaPrototypeService();
        $realisationUaPrototypes_view_data = $realisationUaPrototypeService->prepareDataForIndexView();
        extract($realisationUaPrototypes_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationTache::realisationTache._show', array_merge(compact('itemRealisationTache'),$evaluationRealisationTache_compact_value, $historiqueRealisationTache_compact_value, $realisationChapitre_compact_value, $realisationUaProjet_compact_value, $realisationUaPrototype_compact_value));
        }

        return view('PkgRealisationTache::realisationTache.show', array_merge(compact('itemRealisationTache'),$evaluationRealisationTache_compact_value, $historiqueRealisationTache_compact_value, $realisationChapitre_compact_value, $realisationUaProjet_compact_value, $realisationUaPrototype_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationTache.edit_' . $id);


        $itemRealisationTache = $this->realisationTacheService->edit($id);
        $this->authorize('edit', $itemRealisationTache);

        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);

        $taches = $this->tacheService->getAllForSelect($itemRealisationTache->tache);
        $etatRealisationTaches = $this->etatRealisationTacheService->getAllForSelect($itemRealisationTache->etatRealisationTache);
        $realisationProjets = $this->realisationProjetService->getAllForSelect($itemRealisationTache->realisationProjet);
        $tacheAffectations = $this->tacheAffectationService->getAllForSelect($itemRealisationTache->tacheAffectation);


        $this->viewState->set('scope.evaluationRealisationTache.realisation_tache_id', $id);
        

        $evaluationRealisationTacheService =  new EvaluationRealisationTacheService();
        $evaluationRealisationTaches_view_data = $evaluationRealisationTacheService->prepareDataForIndexView();
        extract($evaluationRealisationTaches_view_data);

        $this->viewState->set('scope.historiqueRealisationTache.realisation_tache_id', $id);
        

        $historiqueRealisationTacheService =  new HistoriqueRealisationTacheService();
        $historiqueRealisationTaches_view_data = $historiqueRealisationTacheService->prepareDataForIndexView();
        extract($historiqueRealisationTaches_view_data);

        $this->viewState->set('scope.realisationChapitre.realisation_tache_id', $id);
        

        $realisationChapitreService =  new RealisationChapitreService();
        $realisationChapitres_view_data = $realisationChapitreService->prepareDataForIndexView();
        extract($realisationChapitres_view_data);

        $this->viewState->set('scope.realisationUaProjet.realisation_tache_id', $id);
        

        $realisationUaProjetService =  new RealisationUaProjetService();
        $realisationUaProjets_view_data = $realisationUaProjetService->prepareDataForIndexView();
        extract($realisationUaProjets_view_data);

        $this->viewState->set('scope.realisationUaPrototype.realisation_tache_id', $id);
        

        $realisationUaPrototypeService =  new RealisationUaPrototypeService();
        $realisationUaPrototypes_view_data = $realisationUaPrototypeService->prepareDataForIndexView();
        extract($realisationUaPrototypes_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgRealisationTache::realisationTache._edit', array_merge(compact('bulkEdit' , 'itemRealisationTache','etatRealisationTaches', 'realisationProjets', 'tacheAffectations', 'taches'),$evaluationRealisationTache_compact_value, $historiqueRealisationTache_compact_value, $realisationChapitre_compact_value, $realisationUaProjet_compact_value, $realisationUaPrototype_compact_value));
        }

        return view('PkgRealisationTache::realisationTache.edit', array_merge(compact('bulkEdit' ,'itemRealisationTache','etatRealisationTaches', 'realisationProjets', 'tacheAffectations', 'taches'),$evaluationRealisationTache_compact_value, $historiqueRealisationTache_compact_value, $realisationChapitre_compact_value, $realisationUaProjet_compact_value, $realisationUaPrototype_compact_value));


    }
    /**
     */
    public function update(RealisationTacheRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationTache = $this->realisationTacheService->find($id);
        $this->authorize('update', $realisationTache);

        $validatedData = $request->validated();
        $realisationTache = $this->realisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationTache,
                'modelName' =>  __('PkgRealisationTache::realisationTache.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('realisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationTache,
                'modelName' =>  __('PkgRealisationTache::realisationTache.singular')
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
            'realisationTache_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('realisationTache_ids', []);
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
        $form         = new \Modules\PkgRealisationTache\App\Requests\RealisationTacheRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->realisationTacheService->find($id);
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
        $realisationTache = $this->realisationTacheService->find($id);
        $this->authorize('delete', $realisationTache);

        $realisationTache = $this->realisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationTache,
                'modelName' =>  __('PkgRealisationTache::realisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('realisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationTache,
                'modelName' =>  __('PkgRealisationTache::realisationTache.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationTache_ids = $request->input('ids', []);
        if (!is_array($realisationTache_ids) || count($realisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationTache_ids as $id) {
            $entity = $this->realisationTacheService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $realisationTache = $this->realisationTacheService->find($id);
            $this->authorize('delete', $realisationTache);
            $this->realisationTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationTache_ids) . ' éléments',
            'modelName' => __('PkgRealisationTache::realisationTache.plural')
        ]));
    }

    public function export($format)
    {
        $realisationTaches_data = $this->realisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationTacheExport($realisationTaches_data,'csv'), 'realisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationTacheExport($realisationTaches_data,'xlsx'), 'realisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationTache::realisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationTaches()
    {
        $realisationTaches = $this->realisationTacheService->all();
        return response()->json($realisationTaches);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (RealisationTache) par ID, en format JSON.
     */
    public function getRealisationTache(Request $request, $id)
    {
        try {
            $realisationTache = $this->realisationTacheService->find($id);
            return response()->json($realisationTache);
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
        $updatedRealisationTache = $this->realisationTacheService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedRealisationTache],
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
        $realisationTacheRequest = new RealisationTacheRequest();
        $fullRules = $realisationTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_taches,id'];
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
        $itemRealisationTache = RealisationTache::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);

        $data = $this->service->buildFieldMeta($itemRealisationTache, $field);
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
        $itemRealisationTache = RealisationTache::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);

        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemRealisationTache);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemRealisationTache, $changes);

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