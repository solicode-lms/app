<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\ChapitreRequest;
use Modules\PkgCompetences\Models\Chapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\App\Exports\ChapitreExport;
use Modules\PkgCompetences\App\Imports\ChapitreImport;
use Modules\Core\Services\ContextState;

class BaseChapitreController extends AdminController
{
    protected $chapitreService;
    protected $formateurService;
    protected $uniteApprentissageService;

    public function __construct(ChapitreService $chapitreService, FormateurService $formateurService, UniteApprentissageService $uniteApprentissageService) {
        parent::__construct();
        $this->service  =  $chapitreService;
        $this->chapitreService = $chapitreService;
        $this->formateurService = $formateurService;
        $this->uniteApprentissageService = $uniteApprentissageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('chapitre.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('chapitre');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $chapitres_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'chapitres_search',
                $this->viewState->get("filter.chapitre.chapitres_search")
            )],
            $request->except(['chapitres_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->chapitreService->prepareDataForIndexView($chapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::chapitre._index', $chapitre_compact_value)->render();
            }else{
                return view($chapitre_partialViewName, $chapitre_compact_value)->render();
            }
        }

        return view('PkgCompetences::chapitre.index', $chapitre_compact_value);
    }
    /**
     */
    public function create() {


        $itemChapitre = $this->chapitreService->createInstance();
        

        $uniteApprentissages = $this->uniteApprentissageService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::chapitre._fields', compact('bulkEdit' ,'itemChapitre', 'formateurs', 'uniteApprentissages'));
        }
        return view('PkgCompetences::chapitre.create', compact('bulkEdit' ,'itemChapitre', 'formateurs', 'uniteApprentissages'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $chapitre_ids = $request->input('ids', []);

        if (!is_array($chapitre_ids) || count($chapitre_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemChapitre = $this->chapitreService->find($chapitre_ids[0]);
         
 
        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemChapitre->uniteApprentissage);
        $formateurs = $this->formateurService->getAllForSelect($itemChapitre->formateur);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemChapitre = $this->chapitreService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::chapitre._fields', compact('bulkEdit', 'chapitre_ids', 'itemChapitre', 'formateurs', 'uniteApprentissages'));
        }
        return view('PkgCompetences::chapitre.bulk-edit', compact('bulkEdit', 'chapitre_ids', 'itemChapitre', 'formateurs', 'uniteApprentissages'));
    }
    /**
     */
    public function store(ChapitreRequest $request) {
        $validatedData = $request->validated();
        $chapitre = $this->chapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $chapitre,
                'modelName' => __('PkgCompetences::chapitre.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $chapitre->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('chapitres.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $chapitre,
                'modelName' => __('PkgCompetences::chapitre.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('chapitre.show_' . $id);

        $itemChapitre = $this->chapitreService->edit($id);


        if (request()->ajax()) {
            return view('PkgCompetences::chapitre._show', array_merge(compact('itemChapitre'),));
        }

        return view('PkgCompetences::chapitre.show', array_merge(compact('itemChapitre'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('chapitre.edit_' . $id);


        $itemChapitre = $this->chapitreService->edit($id);


        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemChapitre->uniteApprentissage);
        $formateurs = $this->formateurService->getAllForSelect($itemChapitre->formateur);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::chapitre._fields', array_merge(compact('bulkEdit' , 'itemChapitre','formateurs', 'uniteApprentissages'),));
        }

        return view('PkgCompetences::chapitre.edit', array_merge(compact('bulkEdit' ,'itemChapitre','formateurs', 'uniteApprentissages'),));


    }
    /**
     */
    public function update(ChapitreRequest $request, string $id) {

        $validatedData = $request->validated();
        $chapitre = $this->chapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgCompetences::chapitre.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $chapitre->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('chapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgCompetences::chapitre.singular')
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
            'chapitre_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('chapitre_ids', []);
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
        $form         = new \Modules\PkgCompetences\App\Requests\ChapitreRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->chapitreService->find($id);
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

        $chapitre = $this->chapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgCompetences::chapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('chapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgCompetences::chapitre.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $chapitre_ids = $request->input('ids', []);
        if (!is_array($chapitre_ids) || count($chapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($chapitre_ids as $id) {
            $entity = $this->chapitreService->find($id);
            $this->chapitreService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($chapitre_ids) . ' éléments',
            'modelName' => __('PkgCompetences::chapitre.plural')
        ]));
    }

    public function export($format)
    {
        $chapitres_data = $this->chapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ChapitreExport($chapitres_data,'csv'), 'chapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ChapitreExport($chapitres_data,'xlsx'), 'chapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('chapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('chapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::chapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getChapitres()
    {
        $chapitres = $this->chapitreService->all();
        return response()->json($chapitres);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Chapitre) par ID, en format JSON.
     */
    public function getChapitre(Request $request, $id)
    {
        try {
            $chapitre = $this->chapitreService->find($id);
            return response()->json($chapitre);
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
        $updatedChapitre = $this->chapitreService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedChapitre],
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
        $chapitreRequest = new ChapitreRequest();
        $fullRules = $chapitreRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:chapitres,id'];
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
        $itemChapitre = Chapitre::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemChapitre, $field);
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
        $itemChapitre = Chapitre::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemChapitre);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemChapitre, $changes);

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