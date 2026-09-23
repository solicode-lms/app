<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgQcm\Controllers\Base;
use Modules\PkgQcm\Services\EtatRealisationQcmService;
use Modules\Core\Services\SysColorService;
use Modules\PkgQcm\Services\RealisationQcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgQcm\App\Requests\EtatRealisationQcmRequest;
use Modules\PkgQcm\Models\EtatRealisationQcm;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\App\Exports\EtatRealisationQcmExport;
use Modules\PkgQcm\App\Imports\EtatRealisationQcmImport;
use Modules\Core\Services\ContextState;

class BaseEtatRealisationQcmController extends AdminController
{
    protected $etatRealisationQcmService;
    protected $sysColorService;

    public function __construct(EtatRealisationQcmService $etatRealisationQcmService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $etatRealisationQcmService;
        $this->etatRealisationQcmService = $etatRealisationQcmService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('etatRealisationQcm.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('etatRealisationQcm');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $etatRealisationQcms_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatRealisationQcms_search',
                $this->viewState->get("filter.etatRealisationQcm.etatRealisationQcms_search")
            )],
            $request->except(['etatRealisationQcms_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatRealisationQcmService->prepareDataForIndexView($etatRealisationQcms_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgQcm::etatRealisationQcm._index', $etatRealisationQcm_compact_value)->render();
            }else{
                return view($etatRealisationQcm_partialViewName, $etatRealisationQcm_compact_value)->render();
            }
        }

        return view('PkgQcm::etatRealisationQcm.index', $etatRealisationQcm_compact_value);
    }
    /**
     */
    public function create() {


        // scopeDataByRole
        $itemEtatRealisationQcm = $this->etatRealisationQcmService->createInstance();
 

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgQcm::etatRealisationQcm._fields', compact('bulkEdit' ,'itemEtatRealisationQcm', 'sysColors'));
        }
        return view('PkgQcm::etatRealisationQcm.create', compact('bulkEdit' ,'itemEtatRealisationQcm', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatRealisationQcm_ids = $request->input('ids', []);

        if (!is_array($etatRealisationQcm_ids) || count($etatRealisationQcm_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEtatRealisationQcm = $this->etatRealisationQcmService->find($etatRealisationQcm_ids[0]);
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationQcm->sysColor);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatRealisationQcm = $this->etatRealisationQcmService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgQcm::etatRealisationQcm._fields', compact('bulkEdit', 'etatRealisationQcm_ids', 'itemEtatRealisationQcm', 'sysColors'));
        }
        return view('PkgQcm::etatRealisationQcm.bulk-edit', compact('bulkEdit', 'etatRealisationQcm_ids', 'itemEtatRealisationQcm', 'sysColors'));
    }
    /**
     */
    public function store(EtatRealisationQcmRequest $request) {
        $validatedData = $request->validated();
        $etatRealisationQcm = $this->etatRealisationQcmService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationQcm,
                'modelName' => __('PkgQcm::etatRealisationQcm.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationQcm->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('etatRealisationQcms.edit', ['etatRealisationQcm' => $etatRealisationQcm->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationQcm,
                'modelName' => __('PkgQcm::etatRealisationQcm.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatRealisationQcm.show_' . $id);

        $itemEtatRealisationQcm = $this->etatRealisationQcmService->edit($id);


        $this->viewState->set('scope.realisationQcm.etat_realisation_qcm_id', $id);
        

        $realisationQcmService =  new RealisationQcmService();
        $realisationQcms_view_data = $realisationQcmService->prepareDataForIndexView();
        extract($realisationQcms_view_data);

        if (request()->ajax()) {
            return view('PkgQcm::etatRealisationQcm._show', array_merge(compact('itemEtatRealisationQcm'),$realisationQcm_compact_value));
        }

        return view('PkgQcm::etatRealisationQcm.show', array_merge(compact('itemEtatRealisationQcm'),$realisationQcm_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatRealisationQcm.edit_' . $id);


        $itemEtatRealisationQcm = $this->etatRealisationQcmService->edit($id);


        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationQcm->sysColor);


        $this->viewState->set('scope.realisationQcm.etat_realisation_qcm_id', $id);
        

        $realisationQcmService =  new RealisationQcmService();
        $realisationQcms_view_data = $realisationQcmService->prepareDataForIndexView();
        extract($realisationQcms_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgQcm::etatRealisationQcm._edit', array_merge(compact('bulkEdit' , 'itemEtatRealisationQcm','sysColors'),$realisationQcm_compact_value));
        }

        return view('PkgQcm::etatRealisationQcm.edit', array_merge(compact('bulkEdit' ,'itemEtatRealisationQcm','sysColors'),$realisationQcm_compact_value));


    }
    /**
     */
    public function update(EtatRealisationQcmRequest $request, string $id) {

        $validatedData = $request->validated();
        $etatRealisationQcm = $this->etatRealisationQcmService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationQcm,
                'modelName' =>  __('PkgQcm::etatRealisationQcm.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationQcm->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('etatRealisationQcms.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationQcm,
                'modelName' =>  __('PkgQcm::etatRealisationQcm.singular')
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
            'etatRealisationQcm_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('etatRealisationQcm_ids', []);
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
        $form         = new \Modules\PkgQcm\App\Requests\EtatRealisationQcmRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->etatRealisationQcmService->find($id);
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

        $etatRealisationQcm = $this->etatRealisationQcmService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationQcm,
                'modelName' =>  __('PkgQcm::etatRealisationQcm.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('etatRealisationQcms.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationQcm,
                'modelName' =>  __('PkgQcm::etatRealisationQcm.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatRealisationQcm_ids = $request->input('ids', []);
        if (!is_array($etatRealisationQcm_ids) || count($etatRealisationQcm_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatRealisationQcm_ids as $id) {
            $entity = $this->etatRealisationQcmService->find($id);
            $this->etatRealisationQcmService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatRealisationQcm_ids) . ' éléments',
            'modelName' => __('PkgQcm::etatRealisationQcm.plural')
        ]));
    }

    public function export($format)
    {
        $etatRealisationQcms_data = $this->etatRealisationQcmService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatRealisationQcmExport($etatRealisationQcms_data,'csv'), 'etatRealisationQcm_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatRealisationQcmExport($etatRealisationQcms_data,'xlsx'), 'etatRealisationQcm_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatRealisationQcmImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatRealisationQcms.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatRealisationQcms.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgQcm::etatRealisationQcm.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatRealisationQcms()
    {
        $etatRealisationQcms = $this->etatRealisationQcmService->all();
        return response()->json($etatRealisationQcms);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EtatRealisationQcm) par ID, en format JSON.
     */
    public function getEtatRealisationQcm(Request $request, $id)
    {
        try {
            $etatRealisationQcm = $this->etatRealisationQcmService->find($id);
            return response()->json($etatRealisationQcm);
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
        $updatedEtatRealisationQcm = $this->etatRealisationQcmService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEtatRealisationQcm],
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
        $etatRealisationQcmRequest = new EtatRealisationQcmRequest();
        $fullRules = $etatRealisationQcmRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_realisation_qcms,id'];
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
        $itemEtatRealisationQcm = EtatRealisationQcm::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEtatRealisationQcm, $field);
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
        $itemEtatRealisationQcm = EtatRealisationQcm::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEtatRealisationQcm);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEtatRealisationQcm, $changes);

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