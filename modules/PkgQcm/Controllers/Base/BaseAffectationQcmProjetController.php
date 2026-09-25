<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Controllers\Base;
use Modules\PkgQcm\Services\AffectationQcmProjetService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgQcm\Services\QcmService;
use Modules\PkgQcm\Services\RealisationQcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgQcm\App\Requests\AffectationQcmProjetRequest;
use Modules\PkgQcm\Models\AffectationQcmProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\App\Exports\AffectationQcmProjetExport;
use Modules\PkgQcm\App\Imports\AffectationQcmProjetImport;
use Modules\Core\Services\ContextState;

class BaseAffectationQcmProjetController extends AdminController
{
    protected $affectationQcmProjetService;
    protected $affectationProjetService;
    protected $qcmService;

    public function __construct(AffectationQcmProjetService $affectationQcmProjetService, AffectationProjetService $affectationProjetService, QcmService $qcmService) {
        parent::__construct();
        $this->service  =  $affectationQcmProjetService;
        $this->affectationQcmProjetService = $affectationQcmProjetService;
        $this->affectationProjetService = $affectationProjetService;
        $this->qcmService = $qcmService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('affectationQcmProjet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('affectationQcmProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $affectationQcmProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'affectationQcmProjets_search',
                $this->viewState->get("filter.affectationQcmProjet.affectationQcmProjets_search")
            )],
            $request->except(['affectationQcmProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->affectationQcmProjetService->prepareDataForIndexView($affectationQcmProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgQcm::affectationQcmProjet._index', $affectationQcmProjet_compact_value)->render();
            }else{
                return view($affectationQcmProjet_partialViewName, $affectationQcmProjet_compact_value)->render();
            }
        }

        return view('PkgQcm::affectationQcmProjet.index', $affectationQcmProjet_compact_value);
    }
    /**
     */
    public function create() {


        // scopeDataByRole
        $itemAffectationQcmProjet = $this->affectationQcmProjetService->createInstance();
 
        // scopeDataInEditContext
        $value = $itemAffectationQcmProjet->getNestedValue('qcm.formateur_id');
        $key = 'scope.affectationProjet.projet.formateur_id';
        $this->viewState->set($key, $value);

        $qcms = $this->qcmService->all();
        $affectationProjets = $this->affectationProjetService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgQcm::affectationQcmProjet._fields', compact('bulkEdit' ,'itemAffectationQcmProjet', 'qcms', 'affectationProjets'));
        }
        return view('PkgQcm::affectationQcmProjet.create', compact('bulkEdit' ,'itemAffectationQcmProjet', 'qcms', 'affectationProjets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $affectationQcmProjet_ids = $request->input('ids', []);

        if (!is_array($affectationQcmProjet_ids) || count($affectationQcmProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemAffectationQcmProjet = $this->affectationQcmProjetService->find($affectationQcmProjet_ids[0]);
         
        // scopeDataInEditContext
        $value = $itemAffectationQcmProjet->getNestedValue('qcm.formateur_id');
        $key = 'scope.affectationProjet.projet.formateur_id';
        $this->viewState->set($key, $value);
 
        $qcms = $this->qcmService->getAllForSelect($itemAffectationQcmProjet->qcm);
        $affectationProjets = $this->affectationProjetService->getAllForSelect($itemAffectationQcmProjet->affectationProjet);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemAffectationQcmProjet = $this->affectationQcmProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgQcm::affectationQcmProjet._fields', compact('bulkEdit', 'affectationQcmProjet_ids', 'itemAffectationQcmProjet', 'qcms', 'affectationProjets'));
        }
        return view('PkgQcm::affectationQcmProjet.bulk-edit', compact('bulkEdit', 'affectationQcmProjet_ids', 'itemAffectationQcmProjet', 'qcms', 'affectationProjets'));
    }
    /**
     */
    public function store(AffectationQcmProjetRequest $request) {
        $validatedData = $request->validated();
        $affectationQcmProjet = $this->affectationQcmProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $affectationQcmProjet,
                'modelName' => __('PkgQcm::affectationQcmProjet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $affectationQcmProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('affectationQcmProjets.edit', ['affectationQcmProjet' => $affectationQcmProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $affectationQcmProjet,
                'modelName' => __('PkgQcm::affectationQcmProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('affectationQcmProjet.show_' . $id);

        $itemAffectationQcmProjet = $this->affectationQcmProjetService->edit($id);


        $this->viewState->set('scope.realisationQcm.affectation_qcm_projet_id', $id);
        

        $realisationQcmService =  new RealisationQcmService();
        $realisationQcms_view_data = $realisationQcmService->prepareDataForIndexView();
        extract($realisationQcms_view_data);

        if (request()->ajax()) {
            return view('PkgQcm::affectationQcmProjet._show', array_merge(compact('itemAffectationQcmProjet'),$realisationQcm_compact_value));
        }

        return view('PkgQcm::affectationQcmProjet.show', array_merge(compact('itemAffectationQcmProjet'),$realisationQcm_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('affectationQcmProjet.edit_' . $id);


        $itemAffectationQcmProjet = $this->affectationQcmProjetService->edit($id);

        // scopeDataInEditContext
        $value = $itemAffectationQcmProjet->getNestedValue('qcm.formateur_id');
        $key = 'scope.affectationProjet.projet.formateur_id';
        $this->viewState->set($key, $value);

        $qcms = $this->qcmService->getAllForSelect($itemAffectationQcmProjet->qcm);
        $affectationProjets = $this->affectationProjetService->getAllForSelect($itemAffectationQcmProjet->affectationProjet);


        $this->viewState->set('scope.realisationQcm.affectation_qcm_projet_id', $id);
        

        $realisationQcmService =  new RealisationQcmService();
        $realisationQcms_view_data = $realisationQcmService->prepareDataForIndexView();
        extract($realisationQcms_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgQcm::affectationQcmProjet._edit', array_merge(compact('bulkEdit' , 'itemAffectationQcmProjet','qcms', 'affectationProjets'),$realisationQcm_compact_value));
        }

        return view('PkgQcm::affectationQcmProjet.edit', array_merge(compact('bulkEdit' ,'itemAffectationQcmProjet','qcms', 'affectationProjets'),$realisationQcm_compact_value));


    }
    /**
     */
    public function update(AffectationQcmProjetRequest $request, string $id) {

        $validatedData = $request->validated();
        $affectationQcmProjet = $this->affectationQcmProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $affectationQcmProjet,
                'modelName' =>  __('PkgQcm::affectationQcmProjet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $affectationQcmProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('affectationQcmProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $affectationQcmProjet,
                'modelName' =>  __('PkgQcm::affectationQcmProjet.singular')
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
            'affectationQcmProjet_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('affectationQcmProjet_ids', []);
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
        $form         = new \Modules\PkgQcm\App\Requests\AffectationQcmProjetRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->affectationQcmProjetService->find($id);
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

        $affectationQcmProjet = $this->affectationQcmProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $affectationQcmProjet,
                'modelName' =>  __('PkgQcm::affectationQcmProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('affectationQcmProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $affectationQcmProjet,
                'modelName' =>  __('PkgQcm::affectationQcmProjet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $affectationQcmProjet_ids = $request->input('ids', []);
        if (!is_array($affectationQcmProjet_ids) || count($affectationQcmProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($affectationQcmProjet_ids as $id) {
            $entity = $this->affectationQcmProjetService->find($id);
            $this->affectationQcmProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($affectationQcmProjet_ids) . ' éléments',
            'modelName' => __('PkgQcm::affectationQcmProjet.plural')
        ]));
    }

    public function export($format)
    {
        $affectationQcmProjets_data = $this->affectationQcmProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new AffectationQcmProjetExport($affectationQcmProjets_data,'csv'), 'affectationQcmProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new AffectationQcmProjetExport($affectationQcmProjets_data,'xlsx'), 'affectationQcmProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new AffectationQcmProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('affectationQcmProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('affectationQcmProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgQcm::affectationQcmProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAffectationQcmProjets()
    {
        $affectationQcmProjets = $this->affectationQcmProjetService->all();
        return response()->json($affectationQcmProjets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (AffectationQcmProjet) par ID, en format JSON.
     */
    public function getAffectationQcmProjet(Request $request, $id)
    {
        try {
            $affectationQcmProjet = $this->affectationQcmProjetService->find($id);
            return response()->json($affectationQcmProjet);
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
        $updatedAffectationQcmProjet = $this->affectationQcmProjetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedAffectationQcmProjet],
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
        $affectationQcmProjetRequest = new AffectationQcmProjetRequest();
        $fullRules = $affectationQcmProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:affectation_qcm_projets,id'];
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
        $itemAffectationQcmProjet = AffectationQcmProjet::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemAffectationQcmProjet->getNestedValue('qcm.formateur_id');
        $key = 'scope.affectationProjet.projet.formateur_id';
        $this->viewState->set($key, $value);

        $data = $this->service->buildFieldMeta($itemAffectationQcmProjet, $field);
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
        $itemAffectationQcmProjet = AffectationQcmProjet::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemAffectationQcmProjet->getNestedValue('qcm.formateur_id');
        $key = 'scope.affectationProjet.projet.formateur_id';
        $this->viewState->set($key, $value);

        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemAffectationQcmProjet);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemAffectationQcmProjet, $changes);

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