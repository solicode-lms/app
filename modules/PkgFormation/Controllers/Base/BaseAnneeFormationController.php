<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgSessions\Services\SessionFormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\AnneeFormationRequest;
use Modules\PkgFormation\Models\AnneeFormation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgFormation\App\Exports\AnneeFormationExport;
use Modules\PkgFormation\App\Imports\AnneeFormationImport;
use Modules\Core\Services\ContextState;

class BaseAnneeFormationController extends AdminController
{
    protected $anneeFormationService;

    public function __construct(AnneeFormationService $anneeFormationService) {
        parent::__construct();
        $this->service  =  $anneeFormationService;
        $this->anneeFormationService = $anneeFormationService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('anneeFormation.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('anneeFormation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $anneeFormations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'anneeFormations_search',
                $this->viewState->get("filter.anneeFormation.anneeFormations_search")
            )],
            $request->except(['anneeFormations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->anneeFormationService->prepareDataForIndexView($anneeFormations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgFormation::anneeFormation._index', $anneeFormation_compact_value)->render();
            }else{
                return view($anneeFormation_partialViewName, $anneeFormation_compact_value)->render();
            }
        }

        return view('PkgFormation::anneeFormation.index', $anneeFormation_compact_value);
    }
    /**
     */
    public function create() {


        $itemAnneeFormation = $this->anneeFormationService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._fields', compact('bulkEdit' ,'itemAnneeFormation'));
        }
        return view('PkgFormation::anneeFormation.create', compact('bulkEdit' ,'itemAnneeFormation'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $anneeFormation_ids = $request->input('ids', []);

        if (!is_array($anneeFormation_ids) || count($anneeFormation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemAnneeFormation = $this->anneeFormationService->find($anneeFormation_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemAnneeFormation = $this->anneeFormationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._fields', compact('bulkEdit', 'anneeFormation_ids', 'itemAnneeFormation'));
        }
        return view('PkgFormation::anneeFormation.bulk-edit', compact('bulkEdit', 'anneeFormation_ids', 'itemAnneeFormation'));
    }
    /**
     */
    public function store(AnneeFormationRequest $request) {
        $validatedData = $request->validated();
        $anneeFormation = $this->anneeFormationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' => __('PkgFormation::anneeFormation.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $anneeFormation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('anneeFormations.edit', ['anneeFormation' => $anneeFormation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' => __('PkgFormation::anneeFormation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('anneeFormation.show_' . $id);

        $itemAnneeFormation = $this->anneeFormationService->edit($id);


        $this->viewState->set('scope.affectationProjet.annee_formation_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.groupe.annee_formation_id', $id);
        

        $groupeService =  new GroupeService();
        $groupes_view_data = $groupeService->prepareDataForIndexView();
        extract($groupes_view_data);

        $this->viewState->set('scope.sessionFormation.annee_formation_id', $id);
        

        $sessionFormationService =  new SessionFormationService();
        $sessionFormations_view_data = $sessionFormationService->prepareDataForIndexView();
        extract($sessionFormations_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._show', array_merge(compact('itemAnneeFormation'),$affectationProjet_compact_value, $groupe_compact_value, $sessionFormation_compact_value));
        }

        return view('PkgFormation::anneeFormation.show', array_merge(compact('itemAnneeFormation'),$affectationProjet_compact_value, $groupe_compact_value, $sessionFormation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('anneeFormation.edit_' . $id);


        $itemAnneeFormation = $this->anneeFormationService->edit($id);




        $this->viewState->set('scope.affectationProjet.annee_formation_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.groupe.annee_formation_id', $id);
        

        $groupeService =  new GroupeService();
        $groupes_view_data = $groupeService->prepareDataForIndexView();
        extract($groupes_view_data);

        $this->viewState->set('scope.sessionFormation.annee_formation_id', $id);
        

        $sessionFormationService =  new SessionFormationService();
        $sessionFormations_view_data = $sessionFormationService->prepareDataForIndexView();
        extract($sessionFormations_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._edit', array_merge(compact('bulkEdit' , 'itemAnneeFormation',),$affectationProjet_compact_value, $groupe_compact_value, $sessionFormation_compact_value));
        }

        return view('PkgFormation::anneeFormation.edit', array_merge(compact('bulkEdit' ,'itemAnneeFormation',),$affectationProjet_compact_value, $groupe_compact_value, $sessionFormation_compact_value));


    }
    /**
     */
    public function update(AnneeFormationRequest $request, string $id) {

        $validatedData = $request->validated();
        $anneeFormation = $this->anneeFormationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $anneeFormation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('anneeFormations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')
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
            'anneeFormation_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('anneeFormation_ids', []);
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
        $form         = new \Modules\PkgFormation\App\Requests\AnneeFormationRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->anneeFormationService->find($id);
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

        $anneeFormation = $this->anneeFormationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('anneeFormations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $anneeFormation_ids = $request->input('ids', []);
        if (!is_array($anneeFormation_ids) || count($anneeFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($anneeFormation_ids as $id) {
            $entity = $this->anneeFormationService->find($id);
            $this->anneeFormationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($anneeFormation_ids) . ' éléments',
            'modelName' => __('PkgFormation::anneeFormation.plural')
        ]));
    }

    public function export($format)
    {
        $anneeFormations_data = $this->anneeFormationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new AnneeFormationExport($anneeFormations_data,'csv'), 'anneeFormation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new AnneeFormationExport($anneeFormations_data,'xlsx'), 'anneeFormation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new AnneeFormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('anneeFormations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('anneeFormations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::anneeFormation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAnneeFormations()
    {
        $anneeFormations = $this->anneeFormationService->all();
        return response()->json($anneeFormations);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (AnneeFormation) par ID, en format JSON.
     */
    public function getAnneeFormation(Request $request, $id)
    {
        try {
            $anneeFormation = $this->anneeFormationService->find($id);
            return response()->json($anneeFormation);
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
        $updatedAnneeFormation = $this->anneeFormationService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedAnneeFormation],
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
        $anneeFormationRequest = new AnneeFormationRequest();
        $fullRules = $anneeFormationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:annee_formations,id'];
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
        $itemAnneeFormation = AnneeFormation::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemAnneeFormation, $field);
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
        $itemAnneeFormation = AnneeFormation::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemAnneeFormation);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemAnneeFormation, $changes);

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