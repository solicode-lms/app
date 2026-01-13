<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationTache\Controllers\Base;
use Modules\PkgCreationTache\Services\PhaseProjetService;
use Modules\PkgCreationTache\Services\TacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationTache\App\Requests\PhaseProjetRequest;
use Modules\PkgCreationTache\Models\PhaseProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationTache\App\Exports\PhaseProjetExport;
use Modules\PkgCreationTache\App\Imports\PhaseProjetImport;
use Modules\Core\Services\ContextState;

class BasePhaseProjetController extends AdminController
{
    protected $phaseProjetService;

    public function __construct(PhaseProjetService $phaseProjetService) {
        parent::__construct();
        $this->service  =  $phaseProjetService;
        $this->phaseProjetService = $phaseProjetService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('phaseProjet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('phaseProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $phaseProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'phaseProjets_search',
                $this->viewState->get("filter.phaseProjet.phaseProjets_search")
            )],
            $request->except(['phaseProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->phaseProjetService->prepareDataForIndexView($phaseProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationTache::phaseProjet._index', $phaseProjet_compact_value)->render();
            }else{
                return view($phaseProjet_partialViewName, $phaseProjet_compact_value)->render();
            }
        }

        return view('PkgCreationTache::phaseProjet.index', $phaseProjet_compact_value);
    }
    /**
     */
    public function create() {


        $itemPhaseProjet = $this->phaseProjetService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationTache::phaseProjet._fields', compact('bulkEdit' ,'itemPhaseProjet'));
        }
        return view('PkgCreationTache::phaseProjet.create', compact('bulkEdit' ,'itemPhaseProjet'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $phaseProjet_ids = $request->input('ids', []);

        if (!is_array($phaseProjet_ids) || count($phaseProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemPhaseProjet = $this->phaseProjetService->find($phaseProjet_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemPhaseProjet = $this->phaseProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationTache::phaseProjet._fields', compact('bulkEdit', 'phaseProjet_ids', 'itemPhaseProjet'));
        }
        return view('PkgCreationTache::phaseProjet.bulk-edit', compact('bulkEdit', 'phaseProjet_ids', 'itemPhaseProjet'));
    }
    /**
     */
    public function store(PhaseProjetRequest $request) {
        $validatedData = $request->validated();
        $phaseProjet = $this->phaseProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $phaseProjet,
                'modelName' => __('PkgCreationTache::phaseProjet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $phaseProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('phaseProjets.edit', ['phaseProjet' => $phaseProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $phaseProjet,
                'modelName' => __('PkgCreationTache::phaseProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('phaseProjet.show_' . $id);

        $itemPhaseProjet = $this->phaseProjetService->edit($id);


        $this->viewState->set('scope.tache.phase_projet_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        if (request()->ajax()) {
            return view('PkgCreationTache::phaseProjet._show', array_merge(compact('itemPhaseProjet'),$tache_compact_value));
        }

        return view('PkgCreationTache::phaseProjet.show', array_merge(compact('itemPhaseProjet'),$tache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('phaseProjet.edit_' . $id);


        $itemPhaseProjet = $this->phaseProjetService->edit($id);




        $this->viewState->set('scope.tache.phase_projet_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationTache::phaseProjet._edit', array_merge(compact('bulkEdit' , 'itemPhaseProjet',),$tache_compact_value));
        }

        return view('PkgCreationTache::phaseProjet.edit', array_merge(compact('bulkEdit' ,'itemPhaseProjet',),$tache_compact_value));


    }
    /**
     */
    public function update(PhaseProjetRequest $request, string $id) {

        $validatedData = $request->validated();
        $phaseProjet = $this->phaseProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $phaseProjet,
                'modelName' =>  __('PkgCreationTache::phaseProjet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $phaseProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('phaseProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $phaseProjet,
                'modelName' =>  __('PkgCreationTache::phaseProjet.singular')
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
            'phaseProjet_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('phaseProjet_ids', []);
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
        $form         = new \Modules\PkgCreationTache\App\Requests\PhaseProjetRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->phaseProjetService->find($id);
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

        $phaseProjet = $this->phaseProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $phaseProjet,
                'modelName' =>  __('PkgCreationTache::phaseProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('phaseProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $phaseProjet,
                'modelName' =>  __('PkgCreationTache::phaseProjet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $phaseProjet_ids = $request->input('ids', []);
        if (!is_array($phaseProjet_ids) || count($phaseProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($phaseProjet_ids as $id) {
            $entity = $this->phaseProjetService->find($id);
            $this->phaseProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($phaseProjet_ids) . ' éléments',
            'modelName' => __('PkgCreationTache::phaseProjet.plural')
        ]));
    }

    public function export($format)
    {
        $phaseProjets_data = $this->phaseProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new PhaseProjetExport($phaseProjets_data,'csv'), 'phaseProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new PhaseProjetExport($phaseProjets_data,'xlsx'), 'phaseProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new PhaseProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('phaseProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('phaseProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationTache::phaseProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getPhaseProjets()
    {
        $phaseProjets = $this->phaseProjetService->all();
        return response()->json($phaseProjets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (PhaseProjet) par ID, en format JSON.
     */
    public function getPhaseProjet(Request $request, $id)
    {
        try {
            $phaseProjet = $this->phaseProjetService->find($id);
            return response()->json($phaseProjet);
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
        $updatedPhaseProjet = $this->phaseProjetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedPhaseProjet],
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
        $phaseProjetRequest = new PhaseProjetRequest();
        $fullRules = $phaseProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:phase_projets,id'];
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
        $itemPhaseProjet = PhaseProjet::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemPhaseProjet, $field);
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
        $itemPhaseProjet = PhaseProjet::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemPhaseProjet);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemPhaseProjet, $changes);

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