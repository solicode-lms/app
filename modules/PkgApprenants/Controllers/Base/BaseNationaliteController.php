<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\NationaliteService;
use Modules\PkgApprenants\Services\ApprenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\NationaliteRequest;
use Modules\PkgApprenants\Models\Nationalite;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\App\Exports\NationaliteExport;
use Modules\PkgApprenants\App\Imports\NationaliteImport;
use Modules\Core\Services\ContextState;

class BaseNationaliteController extends AdminController
{
    protected $nationaliteService;

    public function __construct(NationaliteService $nationaliteService) {
        parent::__construct();
        $this->service  =  $nationaliteService;
        $this->nationaliteService = $nationaliteService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('nationalite.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('nationalite');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $nationalites_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'nationalites_search',
                $this->viewState->get("filter.nationalite.nationalites_search")
            )],
            $request->except(['nationalites_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->nationaliteService->prepareDataForIndexView($nationalites_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprenants::nationalite._index', $nationalite_compact_value)->render();
            }else{
                return view($nationalite_partialViewName, $nationalite_compact_value)->render();
            }
        }

        return view('PkgApprenants::nationalite.index', $nationalite_compact_value);
    }
    /**
     */
    public function create() {


        $itemNationalite = $this->nationaliteService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprenants::nationalite._fields', compact('bulkEdit' ,'itemNationalite'));
        }
        return view('PkgApprenants::nationalite.create', compact('bulkEdit' ,'itemNationalite'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $nationalite_ids = $request->input('ids', []);

        if (!is_array($nationalite_ids) || count($nationalite_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemNationalite = $this->nationaliteService->find($nationalite_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemNationalite = $this->nationaliteService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprenants::nationalite._fields', compact('bulkEdit', 'nationalite_ids', 'itemNationalite'));
        }
        return view('PkgApprenants::nationalite.bulk-edit', compact('bulkEdit', 'nationalite_ids', 'itemNationalite'));
    }
    /**
     */
    public function store(NationaliteRequest $request) {
        $validatedData = $request->validated();
        $nationalite = $this->nationaliteService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $nationalite,
                'modelName' => __('PkgApprenants::nationalite.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $nationalite->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('nationalites.edit', ['nationalite' => $nationalite->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $nationalite,
                'modelName' => __('PkgApprenants::nationalite.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('nationalite.show_' . $id);

        $itemNationalite = $this->nationaliteService->edit($id);


        $this->viewState->set('scope.apprenant.nationalite_id', $id);
        

        $apprenantService =  new ApprenantService();
        $apprenants_view_data = $apprenantService->prepareDataForIndexView();
        extract($apprenants_view_data);

        if (request()->ajax()) {
            return view('PkgApprenants::nationalite._show', array_merge(compact('itemNationalite'),$apprenant_compact_value));
        }

        return view('PkgApprenants::nationalite.show', array_merge(compact('itemNationalite'),$apprenant_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('nationalite.edit_' . $id);


        $itemNationalite = $this->nationaliteService->edit($id);




        $this->viewState->set('scope.apprenant.nationalite_id', $id);
        

        $apprenantService =  new ApprenantService();
        $apprenants_view_data = $apprenantService->prepareDataForIndexView();
        extract($apprenants_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprenants::nationalite._edit', array_merge(compact('bulkEdit' , 'itemNationalite',),$apprenant_compact_value));
        }

        return view('PkgApprenants::nationalite.edit', array_merge(compact('bulkEdit' ,'itemNationalite',),$apprenant_compact_value));


    }
    /**
     */
    public function update(NationaliteRequest $request, string $id) {

        $validatedData = $request->validated();
        $nationalite = $this->nationaliteService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgApprenants::nationalite.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $nationalite->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('nationalites.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgApprenants::nationalite.singular')
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
            'nationalite_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('nationalite_ids', []);
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
        $form         = new \Modules\PkgApprenants\App\Requests\NationaliteRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->nationaliteService->find($id);
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

        $nationalite = $this->nationaliteService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgApprenants::nationalite.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('nationalites.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgApprenants::nationalite.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $nationalite_ids = $request->input('ids', []);
        if (!is_array($nationalite_ids) || count($nationalite_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($nationalite_ids as $id) {
            $entity = $this->nationaliteService->find($id);
            $this->nationaliteService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($nationalite_ids) . ' éléments',
            'modelName' => __('PkgApprenants::nationalite.plural')
        ]));
    }

    public function export($format)
    {
        $nationalites_data = $this->nationaliteService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NationaliteExport($nationalites_data,'csv'), 'nationalite_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NationaliteExport($nationalites_data,'xlsx'), 'nationalite_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NationaliteImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('nationalites.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('nationalites.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::nationalite.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNationalites()
    {
        $nationalites = $this->nationaliteService->all();
        return response()->json($nationalites);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Nationalite) par ID, en format JSON.
     */
    public function getNationalite(Request $request, $id)
    {
        try {
            $nationalite = $this->nationaliteService->find($id);
            return response()->json($nationalite);
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
        $updatedNationalite = $this->nationaliteService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedNationalite],
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
        $nationaliteRequest = new NationaliteRequest();
        $fullRules = $nationaliteRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:nationalites,id'];
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
        $itemNationalite = Nationalite::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemNationalite, $field);
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
        $itemNationalite = Nationalite::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemNationalite);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemNationalite, $changes);

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