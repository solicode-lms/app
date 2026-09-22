<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Controllers\Base;
use Modules\PkgQcm\Services\RealisationQcmService;
use Modules\PkgQcm\Services\AffectationQcmProjetService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgQcm\Services\EtatRealisationQcmService;
use Modules\PkgQcm\Services\QcmService;
use Modules\PkgQcm\Services\ReponseQcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgQcm\App\Requests\RealisationQcmRequest;
use Modules\PkgQcm\Models\RealisationQcm;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\App\Exports\RealisationQcmExport;
use Modules\PkgQcm\App\Imports\RealisationQcmImport;
use Modules\Core\Services\ContextState;

class BaseRealisationQcmController extends AdminController
{
    protected $realisationQcmService;
    protected $affectationQcmProjetService;
    protected $apprenantService;
    protected $etatRealisationQcmService;
    protected $qcmService;

    public function __construct(RealisationQcmService $realisationQcmService, AffectationQcmProjetService $affectationQcmProjetService, ApprenantService $apprenantService, EtatRealisationQcmService $etatRealisationQcmService, QcmService $qcmService) {
        parent::__construct();
        $this->service  =  $realisationQcmService;
        $this->realisationQcmService = $realisationQcmService;
        $this->affectationQcmProjetService = $affectationQcmProjetService;
        $this->apprenantService = $apprenantService;
        $this->etatRealisationQcmService = $etatRealisationQcmService;
        $this->qcmService = $qcmService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationQcm.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationQcm');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $realisationQcms_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationQcms_search',
                $this->viewState->get("filter.realisationQcm.realisationQcms_search")
            )],
            $request->except(['realisationQcms_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationQcmService->prepareDataForIndexView($realisationQcms_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgQcm::realisationQcm._index', $realisationQcm_compact_value)->render();
            }else{
                return view($realisationQcm_partialViewName, $realisationQcm_compact_value)->render();
            }
        }

        return view('PkgQcm::realisationQcm.index', $realisationQcm_compact_value);
    }
    /**
     */
    public function create() {


        // scopeDataByRole
        $itemRealisationQcm = $this->realisationQcmService->createInstance();
 

        $affectationQcmProjets = $this->affectationQcmProjetService->all();
        $etatRealisationQcms = $this->etatRealisationQcmService->all();
        $qcms = $this->qcmService->all();
        $apprenants = $this->apprenantService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgQcm::realisationQcm._fields', compact('bulkEdit' ,'itemRealisationQcm', 'affectationQcmProjets', 'etatRealisationQcms', 'qcms', 'apprenants'));
        }
        return view('PkgQcm::realisationQcm.create', compact('bulkEdit' ,'itemRealisationQcm', 'affectationQcmProjets', 'etatRealisationQcms', 'qcms', 'apprenants'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationQcm_ids = $request->input('ids', []);

        if (!is_array($realisationQcm_ids) || count($realisationQcm_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRealisationQcm = $this->realisationQcmService->find($realisationQcm_ids[0]);
         
 
        $affectationQcmProjets = $this->affectationQcmProjetService->getAllForSelect($itemRealisationQcm->affectationQcmProjet);
        $etatRealisationQcms = $this->etatRealisationQcmService->getAllForSelect($itemRealisationQcm->etatRealisationQcm);
        $qcms = $this->qcmService->getAllForSelect($itemRealisationQcm->qcm);
        $apprenants = $this->apprenantService->getAllForSelect($itemRealisationQcm->apprenant);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationQcm = $this->realisationQcmService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgQcm::realisationQcm._fields', compact('bulkEdit', 'realisationQcm_ids', 'itemRealisationQcm', 'affectationQcmProjets', 'etatRealisationQcms', 'qcms', 'apprenants'));
        }
        return view('PkgQcm::realisationQcm.bulk-edit', compact('bulkEdit', 'realisationQcm_ids', 'itemRealisationQcm', 'affectationQcmProjets', 'etatRealisationQcms', 'qcms', 'apprenants'));
    }
    /**
     */
    public function store(RealisationQcmRequest $request) {
        $validatedData = $request->validated();
        $realisationQcm = $this->realisationQcmService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationQcm,
                'modelName' => __('PkgQcm::realisationQcm.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationQcm->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('realisationQcms.edit', ['realisationQcm' => $realisationQcm->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationQcm,
                'modelName' => __('PkgQcm::realisationQcm.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationQcm.show_' . $id);

        $itemRealisationQcm = $this->realisationQcmService->edit($id);


        $this->viewState->set('scope.reponseQcm.realisation_qcm_id', $id);
        

        $reponseQcmService =  new ReponseQcmService();
        $reponseQcms_view_data = $reponseQcmService->prepareDataForIndexView();
        extract($reponseQcms_view_data);

        if (request()->ajax()) {
            return view('PkgQcm::realisationQcm._show', array_merge(compact('itemRealisationQcm'),$reponseQcm_compact_value));
        }

        return view('PkgQcm::realisationQcm.show', array_merge(compact('itemRealisationQcm'),$reponseQcm_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationQcm.edit_' . $id);


        $itemRealisationQcm = $this->realisationQcmService->edit($id);


        $affectationQcmProjets = $this->affectationQcmProjetService->getAllForSelect($itemRealisationQcm->affectationQcmProjet);
        $etatRealisationQcms = $this->etatRealisationQcmService->getAllForSelect($itemRealisationQcm->etatRealisationQcm);
        $qcms = $this->qcmService->getAllForSelect($itemRealisationQcm->qcm);
        $apprenants = $this->apprenantService->getAllForSelect($itemRealisationQcm->apprenant);


        $this->viewState->set('scope.reponseQcm.realisation_qcm_id', $id);
        

        $reponseQcmService =  new ReponseQcmService();
        $reponseQcms_view_data = $reponseQcmService->prepareDataForIndexView();
        extract($reponseQcms_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgQcm::realisationQcm._edit', array_merge(compact('bulkEdit' , 'itemRealisationQcm','affectationQcmProjets', 'etatRealisationQcms', 'qcms', 'apprenants'),$reponseQcm_compact_value));
        }

        return view('PkgQcm::realisationQcm.edit', array_merge(compact('bulkEdit' ,'itemRealisationQcm','affectationQcmProjets', 'etatRealisationQcms', 'qcms', 'apprenants'),$reponseQcm_compact_value));


    }
    /**
     */
    public function update(RealisationQcmRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationQcm = $this->realisationQcmService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationQcm,
                'modelName' =>  __('PkgQcm::realisationQcm.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationQcm->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('realisationQcms.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationQcm,
                'modelName' =>  __('PkgQcm::realisationQcm.singular')
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
            'realisationQcm_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('realisationQcm_ids', []);
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
        $form         = new \Modules\PkgQcm\App\Requests\RealisationQcmRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->realisationQcmService->find($id);
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

        $realisationQcm = $this->realisationQcmService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationQcm,
                'modelName' =>  __('PkgQcm::realisationQcm.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('realisationQcms.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationQcm,
                'modelName' =>  __('PkgQcm::realisationQcm.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationQcm_ids = $request->input('ids', []);
        if (!is_array($realisationQcm_ids) || count($realisationQcm_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationQcm_ids as $id) {
            $entity = $this->realisationQcmService->find($id);
            $this->realisationQcmService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationQcm_ids) . ' éléments',
            'modelName' => __('PkgQcm::realisationQcm.plural')
        ]));
    }

    public function export($format)
    {
        $realisationQcms_data = $this->realisationQcmService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationQcmExport($realisationQcms_data,'csv'), 'realisationQcm_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationQcmExport($realisationQcms_data,'xlsx'), 'realisationQcm_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationQcmImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationQcms.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationQcms.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgQcm::realisationQcm.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationQcms()
    {
        $realisationQcms = $this->realisationQcmService->all();
        return response()->json($realisationQcms);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (RealisationQcm) par ID, en format JSON.
     */
    public function getRealisationQcm(Request $request, $id)
    {
        try {
            $realisationQcm = $this->realisationQcmService->find($id);
            return response()->json($realisationQcm);
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
        $updatedRealisationQcm = $this->realisationQcmService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedRealisationQcm],
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
        $realisationQcmRequest = new RealisationQcmRequest();
        $fullRules = $realisationQcmRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_qcms,id'];
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
        $itemRealisationQcm = RealisationQcm::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemRealisationQcm, $field);
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
        $itemRealisationQcm = RealisationQcm::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemRealisationQcm);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemRealisationQcm, $changes);

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