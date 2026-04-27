<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\LabelProjetService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\Core\Services\SysColorService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgCreationTache\Services\TacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\LabelProjetRequest;
use Modules\PkgCreationProjet\Models\LabelProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\App\Exports\LabelProjetExport;
use Modules\PkgCreationProjet\App\Imports\LabelProjetImport;
use Modules\Core\Services\ContextState;

class BaseLabelProjetController extends AdminController
{
    protected $labelProjetService;
    protected $projetService;
    protected $sysColorService;
    protected $realisationTacheService;
    protected $tacheService;

    public function __construct(LabelProjetService $labelProjetService, ProjetService $projetService, SysColorService $sysColorService, RealisationTacheService $realisationTacheService, TacheService $tacheService) {
        parent::__construct();
        $this->service  =  $labelProjetService;
        $this->labelProjetService = $labelProjetService;
        $this->projetService = $projetService;
        $this->sysColorService = $sysColorService;
        $this->realisationTacheService = $realisationTacheService;
        $this->tacheService = $tacheService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('labelProjet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('labelProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $labelProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'labelProjets_search',
                $this->viewState->get("filter.labelProjet.labelProjets_search")
            )],
            $request->except(['labelProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->labelProjetService->prepareDataForIndexView($labelProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationProjet::labelProjet._index', $labelProjet_compact_value)->render();
            }else{
                return view($labelProjet_partialViewName, $labelProjet_compact_value)->render();
            }
        }

        return view('PkgCreationProjet::labelProjet.index', $labelProjet_compact_value);
    }
    /**
     */
    public function create() {


        $itemLabelProjet = $this->labelProjetService->createInstance();
 

        $projets = $this->projetService->all();
        $sysColors = $this->sysColorService->all();
        $realisationTaches = $this->realisationTacheService->all();
        $taches = $this->tacheService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationProjet::labelProjet._fields', compact('bulkEdit' ,'itemLabelProjet', 'realisationTaches', 'taches', 'projets', 'sysColors'));
        }
        return view('PkgCreationProjet::labelProjet.create', compact('bulkEdit' ,'itemLabelProjet', 'realisationTaches', 'taches', 'projets', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $labelProjet_ids = $request->input('ids', []);

        if (!is_array($labelProjet_ids) || count($labelProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemLabelProjet = $this->labelProjetService->find($labelProjet_ids[0]);
         
 
        $projets = $this->projetService->getAllForSelect($itemLabelProjet->projet);
        $sysColors = $this->sysColorService->getAllForSelect($itemLabelProjet->sysColor);
        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemLabelProjet->realisationTaches);
        $taches = $this->tacheService->getAllForSelect($itemLabelProjet->taches);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemLabelProjet = $this->labelProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationProjet::labelProjet._fields', compact('bulkEdit', 'labelProjet_ids', 'itemLabelProjet', 'realisationTaches', 'taches', 'projets', 'sysColors'));
        }
        return view('PkgCreationProjet::labelProjet.bulk-edit', compact('bulkEdit', 'labelProjet_ids', 'itemLabelProjet', 'realisationTaches', 'taches', 'projets', 'sysColors'));
    }
    /**
     */
    public function store(LabelProjetRequest $request) {
        $validatedData = $request->validated();
        $labelProjet = $this->labelProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $labelProjet,
                'modelName' => __('PkgCreationProjet::labelProjet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $labelProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('labelProjets.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $labelProjet,
                'modelName' => __('PkgCreationProjet::labelProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('labelProjet.show_' . $id);

        $itemLabelProjet = $this->labelProjetService->edit($id);


        if (request()->ajax()) {
            return view('PkgCreationProjet::labelProjet._show', array_merge(compact('itemLabelProjet'),));
        }

        return view('PkgCreationProjet::labelProjet.show', array_merge(compact('itemLabelProjet'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('labelProjet.edit_' . $id);


        $itemLabelProjet = $this->labelProjetService->edit($id);


        $projets = $this->projetService->getAllForSelect($itemLabelProjet->projet);
        $sysColors = $this->sysColorService->getAllForSelect($itemLabelProjet->sysColor);
        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemLabelProjet->realisationTaches);
        $taches = $this->tacheService->getAllForSelect($itemLabelProjet->taches);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationProjet::labelProjet._fields', array_merge(compact('bulkEdit' , 'itemLabelProjet','realisationTaches', 'taches', 'projets', 'sysColors'),));
        }

        return view('PkgCreationProjet::labelProjet.edit', array_merge(compact('bulkEdit' ,'itemLabelProjet','realisationTaches', 'taches', 'projets', 'sysColors'),));


    }
    /**
     */
    public function update(LabelProjetRequest $request, string $id) {

        $validatedData = $request->validated();
        $labelProjet = $this->labelProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $labelProjet,
                'modelName' =>  __('PkgCreationProjet::labelProjet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $labelProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('labelProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $labelProjet,
                'modelName' =>  __('PkgCreationProjet::labelProjet.singular')
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
            'labelProjet_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('labelProjet_ids', []);
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
        $form         = new \Modules\PkgCreationProjet\App\Requests\LabelProjetRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->labelProjetService->find($id);
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

        $labelProjet = $this->labelProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $labelProjet,
                'modelName' =>  __('PkgCreationProjet::labelProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('labelProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $labelProjet,
                'modelName' =>  __('PkgCreationProjet::labelProjet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $labelProjet_ids = $request->input('ids', []);
        if (!is_array($labelProjet_ids) || count($labelProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($labelProjet_ids as $id) {
            $entity = $this->labelProjetService->find($id);
            $this->labelProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($labelProjet_ids) . ' éléments',
            'modelName' => __('PkgCreationProjet::labelProjet.plural')
        ]));
    }

    public function export($format)
    {
        $labelProjets_data = $this->labelProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new LabelProjetExport($labelProjets_data,'csv'), 'labelProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new LabelProjetExport($labelProjets_data,'xlsx'), 'labelProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new LabelProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('labelProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('labelProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::labelProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLabelProjets()
    {
        $labelProjets = $this->labelProjetService->all();
        return response()->json($labelProjets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (LabelProjet) par ID, en format JSON.
     */
    public function getLabelProjet(Request $request, $id)
    {
        try {
            $labelProjet = $this->labelProjetService->find($id);
            return response()->json($labelProjet);
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
        $updatedLabelProjet = $this->labelProjetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedLabelProjet],
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
        $labelProjetRequest = new LabelProjetRequest();
        $fullRules = $labelProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:label_projets,id'];
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
        $itemLabelProjet = LabelProjet::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemLabelProjet, $field);
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
        $itemLabelProjet = LabelProjet::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemLabelProjet);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemLabelProjet, $changes);

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