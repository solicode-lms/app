<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluateurService;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgApprenants\Services\SousGroupeService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgRealisationTache\Services\TacheAffectationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\AffectationProjetRequest;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationProjets\App\Exports\AffectationProjetExport;
use Modules\PkgRealisationProjets\App\Imports\AffectationProjetImport;
use Modules\Core\Services\ContextState;

class BaseAffectationProjetController extends AdminController
{
    protected $affectationProjetService;
    protected $evaluateurService;
    protected $anneeFormationService;
    protected $groupeService;
    protected $projetService;
    protected $sousGroupeService;

    public function __construct(AffectationProjetService $affectationProjetService, EvaluateurService $evaluateurService, AnneeFormationService $anneeFormationService, GroupeService $groupeService, ProjetService $projetService, SousGroupeService $sousGroupeService) {
        parent::__construct();
        $this->service  =  $affectationProjetService;
        $this->affectationProjetService = $affectationProjetService;
        $this->evaluateurService = $evaluateurService;
        $this->anneeFormationService = $anneeFormationService;
        $this->groupeService = $groupeService;
        $this->projetService = $projetService;
        $this->sousGroupeService = $sousGroupeService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('affectationProjet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('affectationProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.affectationProjet.projet.formateur_id') == null){
           $this->viewState->init('scope.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        // scopeDataByRole
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.groupe.formateurs.formateur_id'  , $this->sessionState->get('formateur_id'));
        }

         // Extraire les paramètres de recherche, pagination, filtres
        $affectationProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'affectationProjets_search',
                $this->viewState->get("filter.affectationProjet.affectationProjets_search")
            )],
            $request->except(['affectationProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->affectationProjetService->prepareDataForIndexView($affectationProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationProjets::affectationProjet._index', $affectationProjet_compact_value)->render();
            }else{
                return view($affectationProjet_partialViewName, $affectationProjet_compact_value)->render();
            }
        }

        return view('PkgRealisationProjets::affectationProjet.index', $affectationProjet_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.groupe.formateurs.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        $itemAffectationProjet = $this->affectationProjetService->createInstance();
        

        $projets = $this->projetService->all();
        $groupes = $this->groupeService->all();
        $sousGroupes = $this->sousGroupeService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $evaluateurs = $this->evaluateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._fields', compact('bulkEdit' ,'itemAffectationProjet', 'evaluateurs', 'anneeFormations', 'groupes', 'projets', 'sousGroupes'));
        }
        return view('PkgRealisationProjets::affectationProjet.create', compact('bulkEdit' ,'itemAffectationProjet', 'evaluateurs', 'anneeFormations', 'groupes', 'projets', 'sousGroupes'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $affectationProjet_ids = $request->input('ids', []);

        if (!is_array($affectationProjet_ids) || count($affectationProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.groupe.formateurs.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
         $itemAffectationProjet = $this->affectationProjetService->find($affectationProjet_ids[0]);
         
 
        $projets = $this->projetService->getAllForSelect($itemAffectationProjet->projet);
        $groupes = $this->groupeService->getAllForSelect($itemAffectationProjet->groupe);
        $sousGroupes = $this->sousGroupeService->getAllForSelect($itemAffectationProjet->sousGroupe);
        $anneeFormations = $this->anneeFormationService->getAllForSelect($itemAffectationProjet->anneeFormation);
        $evaluateurs = $this->evaluateurService->getAllForSelect($itemAffectationProjet->evaluateurs);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemAffectationProjet = $this->affectationProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._fields', compact('bulkEdit', 'affectationProjet_ids', 'itemAffectationProjet', 'evaluateurs', 'anneeFormations', 'groupes', 'projets', 'sousGroupes'));
        }
        return view('PkgRealisationProjets::affectationProjet.bulk-edit', compact('bulkEdit', 'affectationProjet_ids', 'itemAffectationProjet', 'evaluateurs', 'anneeFormations', 'groupes', 'projets', 'sousGroupes'));
    }
    /**
     */
    public function store(AffectationProjetRequest $request) {
        $validatedData = $request->validated();
        $affectationProjet = $this->affectationProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' => __('PkgRealisationProjets::affectationProjet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $affectationProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('affectationProjets.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' => __('PkgRealisationProjets::affectationProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('affectationProjet.show_' . $id);

        $itemAffectationProjet = $this->affectationProjetService->edit($id);
        $this->authorize('view', $itemAffectationProjet);


        $this->viewState->set('scope.realisationProjet.affectation_projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemAffectationProjet->getNestedValue('projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $realisationProjetService =  new RealisationProjetService();
        $realisationProjets_view_data = $realisationProjetService->prepareDataForIndexView();
        extract($realisationProjets_view_data);

        $this->viewState->set('scope.tacheAffectation.affectation_projet_id', $id);
        

        $tacheAffectationService =  new TacheAffectationService();
        $tacheAffectations_view_data = $tacheAffectationService->prepareDataForIndexView();
        extract($tacheAffectations_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._show', array_merge(compact('itemAffectationProjet'),$realisationProjet_compact_value, $tacheAffectation_compact_value));
        }

        return view('PkgRealisationProjets::affectationProjet.show', array_merge(compact('itemAffectationProjet'),$realisationProjet_compact_value, $tacheAffectation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('affectationProjet.edit_' . $id);

        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.groupe.formateurs.formateur_id'  , $this->sessionState->get('formateur_id'));
        }

        $itemAffectationProjet = $this->affectationProjetService->edit($id);
        $this->authorize('edit', $itemAffectationProjet);


        $projets = $this->projetService->getAllForSelect($itemAffectationProjet->projet);
        $groupes = $this->groupeService->getAllForSelect($itemAffectationProjet->groupe);
        $sousGroupes = $this->sousGroupeService->getAllForSelect($itemAffectationProjet->sousGroupe);
        $anneeFormations = $this->anneeFormationService->getAllForSelect($itemAffectationProjet->anneeFormation);
        $evaluateurs = $this->evaluateurService->getAllForSelect($itemAffectationProjet->evaluateurs);


        $this->viewState->set('scope.realisationProjet.affectation_projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemAffectationProjet->getNestedValue('projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $realisationProjetService =  new RealisationProjetService();
        $realisationProjets_view_data = $realisationProjetService->prepareDataForIndexView();
        extract($realisationProjets_view_data);

        $this->viewState->set('scope.tacheAffectation.affectation_projet_id', $id);
        

        $tacheAffectationService =  new TacheAffectationService();
        $tacheAffectations_view_data = $tacheAffectationService->prepareDataForIndexView();
        extract($tacheAffectations_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._edit', array_merge(compact('bulkEdit' , 'itemAffectationProjet','evaluateurs', 'anneeFormations', 'groupes', 'projets', 'sousGroupes'),$realisationProjet_compact_value, $tacheAffectation_compact_value));
        }

        return view('PkgRealisationProjets::affectationProjet.edit', array_merge(compact('bulkEdit' ,'itemAffectationProjet','evaluateurs', 'anneeFormations', 'groupes', 'projets', 'sousGroupes'),$realisationProjet_compact_value, $tacheAffectation_compact_value));


    }
    /**
     */
    public function update(AffectationProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $affectationProjet = $this->affectationProjetService->find($id);
        $this->authorize('update', $affectationProjet);

        $validatedData = $request->validated();
        $affectationProjet = $this->affectationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $affectationProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('affectationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')
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
            'affectationProjet_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('affectationProjet_ids', []);
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
        $form         = new \Modules\PkgRealisationProjets\App\Requests\AffectationProjetRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->affectationProjetService->find($id);
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
        $affectationProjet = $this->affectationProjetService->find($id);
        $this->authorize('delete', $affectationProjet);

        $affectationProjet = $this->affectationProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('affectationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $affectationProjet_ids = $request->input('ids', []);
        if (!is_array($affectationProjet_ids) || count($affectationProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($affectationProjet_ids as $id) {
            $entity = $this->affectationProjetService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $affectationProjet = $this->affectationProjetService->find($id);
            $this->authorize('delete', $affectationProjet);
            $this->affectationProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($affectationProjet_ids) . ' éléments',
            'modelName' => __('PkgRealisationProjets::affectationProjet.plural')
        ]));
    }

    public function export($format)
    {
        $affectationProjets_data = $this->affectationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new AffectationProjetExport($affectationProjets_data,'csv'), 'affectationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new AffectationProjetExport($affectationProjets_data,'xlsx'), 'affectationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new AffectationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('affectationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('affectationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::affectationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAffectationProjets()
    {
        $affectationProjets = $this->affectationProjetService->all();
        return response()->json($affectationProjets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (AffectationProjet) par ID, en format JSON.
     */
    public function getAffectationProjet(Request $request, $id)
    {
        try {
            $affectationProjet = $this->affectationProjetService->find($id);
            return response()->json($affectationProjet);
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
        $updatedAffectationProjet = $this->affectationProjetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedAffectationProjet],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
    }
    
    public function exportPV(Request $request, string $id) {
        $affectationProjet = $this->affectationProjetService->exportPV($id);
        if ($request->ajax()) {
            $message = "Le fichier Excel a été généré avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('AffectationProjet.index')->with(
            'success',
            "Le fichier Excel a été généré avec succès"
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
        $affectationProjetRequest = new AffectationProjetRequest();
        $fullRules = $affectationProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:affectation_projets,id'];
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
        $itemAffectationProjet = AffectationProjet::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemAffectationProjet, $field);
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
        $itemAffectationProjet = AffectationProjet::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemAffectationProjet);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemAffectationProjet, $changes);

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