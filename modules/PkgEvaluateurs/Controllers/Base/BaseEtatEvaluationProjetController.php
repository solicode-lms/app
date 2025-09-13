<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgEvaluateurs\Controllers\Base;
use Modules\PkgEvaluateurs\Services\EtatEvaluationProjetService;
use Modules\Core\Services\SysColorService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgEvaluateurs\App\Requests\EtatEvaluationProjetRequest;
use Modules\PkgEvaluateurs\Models\EtatEvaluationProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgEvaluateurs\App\Exports\EtatEvaluationProjetExport;
use Modules\PkgEvaluateurs\App\Imports\EtatEvaluationProjetImport;
use Modules\Core\Services\ContextState;

class BaseEtatEvaluationProjetController extends AdminController
{
    protected $etatEvaluationProjetService;
    protected $sysColorService;

    public function __construct(EtatEvaluationProjetService $etatEvaluationProjetService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $etatEvaluationProjetService;
        $this->etatEvaluationProjetService = $etatEvaluationProjetService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('etatEvaluationProjet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('etatEvaluationProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $etatEvaluationProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatEvaluationProjets_search',
                $this->viewState->get("filter.etatEvaluationProjet.etatEvaluationProjets_search")
            )],
            $request->except(['etatEvaluationProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatEvaluationProjetService->prepareDataForIndexView($etatEvaluationProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgEvaluateurs::etatEvaluationProjet._index', $etatEvaluationProjet_compact_value)->render();
            }else{
                return view($etatEvaluationProjet_partialViewName, $etatEvaluationProjet_compact_value)->render();
            }
        }

        return view('PkgEvaluateurs::etatEvaluationProjet.index', $etatEvaluationProjet_compact_value);
    }
    /**
     */
    public function create() {


        $itemEtatEvaluationProjet = $this->etatEvaluationProjetService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgEvaluateurs::etatEvaluationProjet._fields', compact('bulkEdit' ,'itemEtatEvaluationProjet', 'sysColors'));
        }
        return view('PkgEvaluateurs::etatEvaluationProjet.create', compact('bulkEdit' ,'itemEtatEvaluationProjet', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatEvaluationProjet_ids = $request->input('ids', []);

        if (!is_array($etatEvaluationProjet_ids) || count($etatEvaluationProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEtatEvaluationProjet = $this->etatEvaluationProjetService->find($etatEvaluationProjet_ids[0]);
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemEtatEvaluationProjet->sysColor);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatEvaluationProjet = $this->etatEvaluationProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgEvaluateurs::etatEvaluationProjet._fields', compact('bulkEdit', 'etatEvaluationProjet_ids', 'itemEtatEvaluationProjet', 'sysColors'));
        }
        return view('PkgEvaluateurs::etatEvaluationProjet.bulk-edit', compact('bulkEdit', 'etatEvaluationProjet_ids', 'itemEtatEvaluationProjet', 'sysColors'));
    }
    /**
     */
    public function store(EtatEvaluationProjetRequest $request) {
        $validatedData = $request->validated();
        $etatEvaluationProjet = $this->etatEvaluationProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatEvaluationProjet,
                'modelName' => __('PkgEvaluateurs::etatEvaluationProjet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatEvaluationProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('etatEvaluationProjets.edit', ['etatEvaluationProjet' => $etatEvaluationProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatEvaluationProjet,
                'modelName' => __('PkgEvaluateurs::etatEvaluationProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatEvaluationProjet.show_' . $id);

        $itemEtatEvaluationProjet = $this->etatEvaluationProjetService->edit($id);


        $this->viewState->set('scope.evaluationRealisationProjet.etat_evaluation_projet_id', $id);
        

        $evaluationRealisationProjetService =  new EvaluationRealisationProjetService();
        $evaluationRealisationProjets_view_data = $evaluationRealisationProjetService->prepareDataForIndexView();
        extract($evaluationRealisationProjets_view_data);

        if (request()->ajax()) {
            return view('PkgEvaluateurs::etatEvaluationProjet._show', array_merge(compact('itemEtatEvaluationProjet'),$evaluationRealisationProjet_compact_value));
        }

        return view('PkgEvaluateurs::etatEvaluationProjet.show', array_merge(compact('itemEtatEvaluationProjet'),$evaluationRealisationProjet_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatEvaluationProjet.edit_' . $id);


        $itemEtatEvaluationProjet = $this->etatEvaluationProjetService->edit($id);


        $sysColors = $this->sysColorService->getAllForSelect($itemEtatEvaluationProjet->sysColor);


        $this->viewState->set('scope.evaluationRealisationProjet.etat_evaluation_projet_id', $id);
        

        $evaluationRealisationProjetService =  new EvaluationRealisationProjetService();
        $evaluationRealisationProjets_view_data = $evaluationRealisationProjetService->prepareDataForIndexView();
        extract($evaluationRealisationProjets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgEvaluateurs::etatEvaluationProjet._edit', array_merge(compact('bulkEdit' , 'itemEtatEvaluationProjet','sysColors'),$evaluationRealisationProjet_compact_value));
        }

        return view('PkgEvaluateurs::etatEvaluationProjet.edit', array_merge(compact('bulkEdit' ,'itemEtatEvaluationProjet','sysColors'),$evaluationRealisationProjet_compact_value));


    }
    /**
     */
    public function update(EtatEvaluationProjetRequest $request, string $id) {

        $validatedData = $request->validated();
        $etatEvaluationProjet = $this->etatEvaluationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatEvaluationProjet,
                'modelName' =>  __('PkgEvaluateurs::etatEvaluationProjet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatEvaluationProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('etatEvaluationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatEvaluationProjet,
                'modelName' =>  __('PkgEvaluateurs::etatEvaluationProjet.singular')
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
            'etatEvaluationProjet_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('etatEvaluationProjet_ids', []);
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
        $form         = new \Modules\PkgEvaluateurs\App\Requests\EtatEvaluationProjetRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->etatEvaluationProjetService->find($id);
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

        $etatEvaluationProjet = $this->etatEvaluationProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatEvaluationProjet,
                'modelName' =>  __('PkgEvaluateurs::etatEvaluationProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('etatEvaluationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatEvaluationProjet,
                'modelName' =>  __('PkgEvaluateurs::etatEvaluationProjet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatEvaluationProjet_ids = $request->input('ids', []);
        if (!is_array($etatEvaluationProjet_ids) || count($etatEvaluationProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatEvaluationProjet_ids as $id) {
            $entity = $this->etatEvaluationProjetService->find($id);
            $this->etatEvaluationProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatEvaluationProjet_ids) . ' éléments',
            'modelName' => __('PkgEvaluateurs::etatEvaluationProjet.plural')
        ]));
    }

    public function export($format)
    {
        $etatEvaluationProjets_data = $this->etatEvaluationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatEvaluationProjetExport($etatEvaluationProjets_data,'csv'), 'etatEvaluationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatEvaluationProjetExport($etatEvaluationProjets_data,'xlsx'), 'etatEvaluationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatEvaluationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatEvaluationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatEvaluationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgEvaluateurs::etatEvaluationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatEvaluationProjets()
    {
        $etatEvaluationProjets = $this->etatEvaluationProjetService->all();
        return response()->json($etatEvaluationProjets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EtatEvaluationProjet) par ID, en format JSON.
     */
    public function getEtatEvaluationProjet(Request $request, $id)
    {
        try {
            $etatEvaluationProjet = $this->etatEvaluationProjetService->find($id);
            return response()->json($etatEvaluationProjet);
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
        $updatedEtatEvaluationProjet = $this->etatEvaluationProjetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEtatEvaluationProjet],
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
        $etatEvaluationProjetRequest = new EtatEvaluationProjetRequest();
        $fullRules = $etatEvaluationProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_evaluation_projets,id'];
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
        $itemEtatEvaluationProjet = EtatEvaluationProjet::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEtatEvaluationProjet, $field);
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
        $itemEtatEvaluationProjet = EtatEvaluationProjet::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEtatEvaluationProjet);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEtatEvaluationProjet, $changes);

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