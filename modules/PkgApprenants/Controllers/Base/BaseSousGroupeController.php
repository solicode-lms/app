<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\SousGroupeService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\SousGroupeRequest;
use Modules\PkgApprenants\Models\SousGroupe;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\App\Exports\SousGroupeExport;
use Modules\PkgApprenants\App\Imports\SousGroupeImport;
use Modules\Core\Services\ContextState;

class BaseSousGroupeController extends AdminController
{
    protected $sousGroupeService;
    protected $apprenantService;
    protected $groupeService;

    public function __construct(SousGroupeService $sousGroupeService, ApprenantService $apprenantService, GroupeService $groupeService) {
        parent::__construct();
        $this->service  =  $sousGroupeService;
        $this->sousGroupeService = $sousGroupeService;
        $this->apprenantService = $apprenantService;
        $this->groupeService = $groupeService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('sousGroupe.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('sousGroupe');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $sousGroupes_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'sousGroupes_search',
                $this->viewState->get("filter.sousGroupe.sousGroupes_search")
            )],
            $request->except(['sousGroupes_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->sousGroupeService->prepareDataForIndexView($sousGroupes_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprenants::sousGroupe._index', $sousGroupe_compact_value)->render();
            }else{
                return view($sousGroupe_partialViewName, $sousGroupe_compact_value)->render();
            }
        }

        return view('PkgApprenants::sousGroupe.index', $sousGroupe_compact_value);
    }
    /**
     */
    public function create() {


        $itemSousGroupe = $this->sousGroupeService->createInstance();
        

        $groupes = $this->groupeService->all();
        $apprenants = $this->apprenantService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprenants::sousGroupe._fields', compact('bulkEdit' ,'itemSousGroupe', 'apprenants', 'groupes'));
        }
        return view('PkgApprenants::sousGroupe.create', compact('bulkEdit' ,'itemSousGroupe', 'apprenants', 'groupes'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $sousGroupe_ids = $request->input('ids', []);

        if (!is_array($sousGroupe_ids) || count($sousGroupe_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemSousGroupe = $this->sousGroupeService->find($sousGroupe_ids[0]);
         
 
        $groupes = $this->groupeService->getAllForSelect($itemSousGroupe->groupe);
        $apprenants = $this->apprenantService->getAllForSelect($itemSousGroupe->apprenants);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemSousGroupe = $this->sousGroupeService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprenants::sousGroupe._fields', compact('bulkEdit', 'sousGroupe_ids', 'itemSousGroupe', 'apprenants', 'groupes'));
        }
        return view('PkgApprenants::sousGroupe.bulk-edit', compact('bulkEdit', 'sousGroupe_ids', 'itemSousGroupe', 'apprenants', 'groupes'));
    }
    /**
     */
    public function store(SousGroupeRequest $request) {
        $validatedData = $request->validated();
        $sousGroupe = $this->sousGroupeService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sousGroupe,
                'modelName' => __('PkgApprenants::sousGroupe.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sousGroupe->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('sousGroupes.edit', ['sousGroupe' => $sousGroupe->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sousGroupe,
                'modelName' => __('PkgApprenants::sousGroupe.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('sousGroupe.show_' . $id);

        $itemSousGroupe = $this->sousGroupeService->edit($id);


        $this->viewState->set('scope.affectationProjet.sous_groupe_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        if (request()->ajax()) {
            return view('PkgApprenants::sousGroupe._show', array_merge(compact('itemSousGroupe'),$affectationProjet_compact_value));
        }

        return view('PkgApprenants::sousGroupe.show', array_merge(compact('itemSousGroupe'),$affectationProjet_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('sousGroupe.edit_' . $id);


        $itemSousGroupe = $this->sousGroupeService->edit($id);


        $groupes = $this->groupeService->getAllForSelect($itemSousGroupe->groupe);
        $apprenants = $this->apprenantService->getAllForSelect($itemSousGroupe->apprenants);


        $this->viewState->set('scope.affectationProjet.sous_groupe_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprenants::sousGroupe._edit', array_merge(compact('bulkEdit' , 'itemSousGroupe','apprenants', 'groupes'),$affectationProjet_compact_value));
        }

        return view('PkgApprenants::sousGroupe.edit', array_merge(compact('bulkEdit' ,'itemSousGroupe','apprenants', 'groupes'),$affectationProjet_compact_value));


    }
    /**
     */
    public function update(SousGroupeRequest $request, string $id) {

        $validatedData = $request->validated();
        $sousGroupe = $this->sousGroupeService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sousGroupe,
                'modelName' =>  __('PkgApprenants::sousGroupe.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sousGroupe->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('sousGroupes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sousGroupe,
                'modelName' =>  __('PkgApprenants::sousGroupe.singular')
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
            'sousGroupe_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('sousGroupe_ids', []);
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
        $form         = new \Modules\PkgApprenants\App\Requests\SousGroupeRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->sousGroupeService->find($id);
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

        $sousGroupe = $this->sousGroupeService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sousGroupe,
                'modelName' =>  __('PkgApprenants::sousGroupe.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('sousGroupes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sousGroupe,
                'modelName' =>  __('PkgApprenants::sousGroupe.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $sousGroupe_ids = $request->input('ids', []);
        if (!is_array($sousGroupe_ids) || count($sousGroupe_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($sousGroupe_ids as $id) {
            $entity = $this->sousGroupeService->find($id);
            $this->sousGroupeService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($sousGroupe_ids) . ' éléments',
            'modelName' => __('PkgApprenants::sousGroupe.plural')
        ]));
    }

    public function export($format)
    {
        $sousGroupes_data = $this->sousGroupeService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SousGroupeExport($sousGroupes_data,'csv'), 'sousGroupe_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SousGroupeExport($sousGroupes_data,'xlsx'), 'sousGroupe_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SousGroupeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sousGroupes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sousGroupes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::sousGroupe.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSousGroupes()
    {
        $sousGroupes = $this->sousGroupeService->all();
        return response()->json($sousGroupes);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (SousGroupe) par ID, en format JSON.
     */
    public function getSousGroupe(Request $request, $id)
    {
        try {
            $sousGroupe = $this->sousGroupeService->find($id);
            return response()->json($sousGroupe);
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
        $updatedSousGroupe = $this->sousGroupeService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedSousGroupe],
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
        $sousGroupeRequest = new SousGroupeRequest();
        $fullRules = $sousGroupeRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:sous_groupes,id'];
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
        $itemSousGroupe = SousGroupe::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemSousGroupe, $field);
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
        $itemSousGroupe = SousGroupe::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemSousGroupe);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemSousGroupe, $changes);

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