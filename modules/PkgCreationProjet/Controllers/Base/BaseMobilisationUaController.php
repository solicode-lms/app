<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\MobilisationUaService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\MobilisationUaRequest;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\App\Exports\MobilisationUaExport;
use Modules\PkgCreationProjet\App\Imports\MobilisationUaImport;
use Modules\Core\Services\ContextState;

class BaseMobilisationUaController extends AdminController
{
    protected $mobilisationUaService;
    protected $projetService;
    protected $uniteApprentissageService;

    public function __construct(MobilisationUaService $mobilisationUaService, ProjetService $projetService, UniteApprentissageService $uniteApprentissageService) {
        parent::__construct();
        $this->service  =  $mobilisationUaService;
        $this->mobilisationUaService = $mobilisationUaService;
        $this->projetService = $projetService;
        $this->uniteApprentissageService = $uniteApprentissageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('mobilisationUa.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('mobilisationUa');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $mobilisationUas_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'mobilisationUas_search',
                $this->viewState->get("filter.mobilisationUa.mobilisationUas_search")
            )],
            $request->except(['mobilisationUas_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->mobilisationUaService->prepareDataForIndexView($mobilisationUas_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationProjet::mobilisationUa._index', $mobilisationUa_compact_value)->render();
            }else{
                return view($mobilisationUa_partialViewName, $mobilisationUa_compact_value)->render();
            }
        }

        return view('PkgCreationProjet::mobilisationUa.index', $mobilisationUa_compact_value);
    }
    /**
     */
    public function create() {


        $itemMobilisationUa = $this->mobilisationUaService->createInstance();
 
        // scopeDataInEditContext
        $value = $itemMobilisationUa->getNestedValue('projet.filiere_id');
        $key = 'scope.uniteApprentissage.microCompetence.competence.module.filiere_id';
        $this->viewState->set($key, $value);

        $uniteApprentissages = $this->uniteApprentissageService->all();
        $projets = $this->projetService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationProjet::mobilisationUa._fields', compact('bulkEdit' ,'itemMobilisationUa', 'projets', 'uniteApprentissages'));
        }
        return view('PkgCreationProjet::mobilisationUa.create', compact('bulkEdit' ,'itemMobilisationUa', 'projets', 'uniteApprentissages'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $mobilisationUa_ids = $request->input('ids', []);

        if (!is_array($mobilisationUa_ids) || count($mobilisationUa_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemMobilisationUa = $this->mobilisationUaService->find($mobilisationUa_ids[0]);
         
        // scopeDataInEditContext
        $value = $itemMobilisationUa->getNestedValue('projet.filiere_id');
        $key = 'scope.uniteApprentissage.microCompetence.competence.module.filiere_id';
        $this->viewState->set($key, $value);
 
        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemMobilisationUa->uniteApprentissage);
        $projets = $this->projetService->getAllForSelect($itemMobilisationUa->projet);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemMobilisationUa = $this->mobilisationUaService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationProjet::mobilisationUa._fields', compact('bulkEdit', 'mobilisationUa_ids', 'itemMobilisationUa', 'projets', 'uniteApprentissages'));
        }
        return view('PkgCreationProjet::mobilisationUa.bulk-edit', compact('bulkEdit', 'mobilisationUa_ids', 'itemMobilisationUa', 'projets', 'uniteApprentissages'));
    }
    /**
     */
    public function store(MobilisationUaRequest $request) {
        $validatedData = $request->validated();
        $mobilisationUa = $this->mobilisationUaService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' => __('PkgCreationProjet::mobilisationUa.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $mobilisationUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('mobilisationUas.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' => __('PkgCreationProjet::mobilisationUa.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('mobilisationUa.show_' . $id);

        $itemMobilisationUa = $this->mobilisationUaService->edit($id);


        if (request()->ajax()) {
            return view('PkgCreationProjet::mobilisationUa._show', array_merge(compact('itemMobilisationUa'),));
        }

        return view('PkgCreationProjet::mobilisationUa.show', array_merge(compact('itemMobilisationUa'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('mobilisationUa.edit_' . $id);


        $itemMobilisationUa = $this->mobilisationUaService->edit($id);

        // scopeDataInEditContext
        $value = $itemMobilisationUa->getNestedValue('projet.filiere_id');
        $key = 'scope.uniteApprentissage.microCompetence.competence.module.filiere_id';
        $this->viewState->set($key, $value);

        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemMobilisationUa->uniteApprentissage);
        $projets = $this->projetService->getAllForSelect($itemMobilisationUa->projet);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationProjet::mobilisationUa._fields', array_merge(compact('bulkEdit' , 'itemMobilisationUa','projets', 'uniteApprentissages'),));
        }

        return view('PkgCreationProjet::mobilisationUa.edit', array_merge(compact('bulkEdit' ,'itemMobilisationUa','projets', 'uniteApprentissages'),));


    }
    /**
     */
    public function update(MobilisationUaRequest $request, string $id) {

        $validatedData = $request->validated();
        $mobilisationUa = $this->mobilisationUaService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' =>  __('PkgCreationProjet::mobilisationUa.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $mobilisationUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('mobilisationUas.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' =>  __('PkgCreationProjet::mobilisationUa.singular')
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
            'mobilisationUa_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('mobilisationUa_ids', []);
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
        $form         = new \Modules\PkgCreationProjet\App\Requests\MobilisationUaRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->mobilisationUaService->find($id);
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

        $mobilisationUa = $this->mobilisationUaService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' =>  __('PkgCreationProjet::mobilisationUa.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('mobilisationUas.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' =>  __('PkgCreationProjet::mobilisationUa.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $mobilisationUa_ids = $request->input('ids', []);
        if (!is_array($mobilisationUa_ids) || count($mobilisationUa_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($mobilisationUa_ids as $id) {
            $entity = $this->mobilisationUaService->find($id);
            $this->mobilisationUaService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($mobilisationUa_ids) . ' éléments',
            'modelName' => __('PkgCreationProjet::mobilisationUa.plural')
        ]));
    }

    public function export($format)
    {
        $mobilisationUas_data = $this->mobilisationUaService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new MobilisationUaExport($mobilisationUas_data,'csv'), 'mobilisationUa_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new MobilisationUaExport($mobilisationUas_data,'xlsx'), 'mobilisationUa_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new MobilisationUaImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('mobilisationUas.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('mobilisationUas.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::mobilisationUa.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getMobilisationUas()
    {
        $mobilisationUas = $this->mobilisationUaService->all();
        return response()->json($mobilisationUas);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (MobilisationUa) par ID, en format JSON.
     */
    public function getMobilisationUa(Request $request, $id)
    {
        try {
            $mobilisationUa = $this->mobilisationUaService->find($id);
            return response()->json($mobilisationUa);
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
        $updatedMobilisationUa = $this->mobilisationUaService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedMobilisationUa],
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
        $mobilisationUaRequest = new MobilisationUaRequest();
        $fullRules = $mobilisationUaRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:mobilisation_uas,id'];
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
        $itemMobilisationUa = MobilisationUa::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemMobilisationUa->getNestedValue('projet.filiere_id');
        $key = 'scope.uniteApprentissage.microCompetence.competence.module.filiere_id';
        $this->viewState->set($key, $value);

        $data = $this->service->buildFieldMeta($itemMobilisationUa, $field);
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
        $itemMobilisationUa = MobilisationUa::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemMobilisationUa->getNestedValue('projet.filiere_id');
        $key = 'scope.uniteApprentissage.microCompetence.competence.module.filiere_id';
        $this->viewState->set($key, $value);

        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemMobilisationUa);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemMobilisationUa, $changes);

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