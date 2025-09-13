<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\Core\Services\SysColorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\EtatsRealisationProjetRequest;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationProjets\App\Exports\EtatsRealisationProjetExport;
use Modules\PkgRealisationProjets\App\Imports\EtatsRealisationProjetImport;
use Modules\Core\Services\ContextState;

class BaseEtatsRealisationProjetController extends AdminController
{
    protected $etatsRealisationProjetService;
    protected $sysColorService;

    public function __construct(EtatsRealisationProjetService $etatsRealisationProjetService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $etatsRealisationProjetService;
        $this->etatsRealisationProjetService = $etatsRealisationProjetService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('etatsRealisationProjet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('etatsRealisationProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser



         // Extraire les paramètres de recherche, pagination, filtres
        $etatsRealisationProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatsRealisationProjets_search',
                $this->viewState->get("filter.etatsRealisationProjet.etatsRealisationProjets_search")
            )],
            $request->except(['etatsRealisationProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatsRealisationProjetService->prepareDataForIndexView($etatsRealisationProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationProjets::etatsRealisationProjet._index', $etatsRealisationProjet_compact_value)->render();
            }else{
                return view($etatsRealisationProjet_partialViewName, $etatsRealisationProjet_compact_value)->render();
            }
        }

        return view('PkgRealisationProjets::etatsRealisationProjet.index', $etatsRealisationProjet_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser


        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._fields', compact('bulkEdit' ,'itemEtatsRealisationProjet', 'sysColors'));
        }
        return view('PkgRealisationProjets::etatsRealisationProjet.create', compact('bulkEdit' ,'itemEtatsRealisationProjet', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatsRealisationProjet_ids = $request->input('ids', []);

        if (!is_array($etatsRealisationProjet_ids) || count($etatsRealisationProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
 
         $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->find($etatsRealisationProjet_ids[0]);
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemEtatsRealisationProjet->sysColor);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._fields', compact('bulkEdit', 'etatsRealisationProjet_ids', 'itemEtatsRealisationProjet', 'sysColors'));
        }
        return view('PkgRealisationProjets::etatsRealisationProjet.bulk-edit', compact('bulkEdit', 'etatsRealisationProjet_ids', 'itemEtatsRealisationProjet', 'sysColors'));
    }
    /**
     */
    public function store(EtatsRealisationProjetRequest $request) {
        $validatedData = $request->validated();
        $etatsRealisationProjet = $this->etatsRealisationProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' => __('PkgRealisationProjets::etatsRealisationProjet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatsRealisationProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' => __('PkgRealisationProjets::etatsRealisationProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatsRealisationProjet.show_' . $id);

        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->edit($id);
        $this->authorize('view', $itemEtatsRealisationProjet);


        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._show', array_merge(compact('itemEtatsRealisationProjet'),));
        }

        return view('PkgRealisationProjets::etatsRealisationProjet.show', array_merge(compact('itemEtatsRealisationProjet'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatsRealisationProjet.edit_' . $id);


        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->edit($id);
        $this->authorize('edit', $itemEtatsRealisationProjet);


        $sysColors = $this->sysColorService->getAllForSelect($itemEtatsRealisationProjet->sysColor);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._fields', array_merge(compact('bulkEdit' , 'itemEtatsRealisationProjet','sysColors'),));
        }

        return view('PkgRealisationProjets::etatsRealisationProjet.edit', array_merge(compact('bulkEdit' ,'itemEtatsRealisationProjet','sysColors'),));


    }
    /**
     */
    public function update(EtatsRealisationProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatsRealisationProjet = $this->etatsRealisationProjetService->find($id);
        $this->authorize('update', $etatsRealisationProjet);

        $validatedData = $request->validated();
        $etatsRealisationProjet = $this->etatsRealisationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatsRealisationProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')
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
            'etatsRealisationProjet_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('etatsRealisationProjet_ids', []);
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
        $form         = new \Modules\PkgRealisationProjets\App\Requests\EtatsRealisationProjetRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->etatsRealisationProjetService->find($id);
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
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatsRealisationProjet = $this->etatsRealisationProjetService->find($id);
        $this->authorize('delete', $etatsRealisationProjet);

        $etatsRealisationProjet = $this->etatsRealisationProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatsRealisationProjet_ids = $request->input('ids', []);
        if (!is_array($etatsRealisationProjet_ids) || count($etatsRealisationProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatsRealisationProjet_ids as $id) {
            $entity = $this->etatsRealisationProjetService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $etatsRealisationProjet = $this->etatsRealisationProjetService->find($id);
            $this->authorize('delete', $etatsRealisationProjet);
            $this->etatsRealisationProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatsRealisationProjet_ids) . ' éléments',
            'modelName' => __('PkgRealisationProjets::etatsRealisationProjet.plural')
        ]));
    }

    public function export($format)
    {
        $etatsRealisationProjets_data = $this->etatsRealisationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatsRealisationProjetExport($etatsRealisationProjets_data,'csv'), 'etatsRealisationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatsRealisationProjetExport($etatsRealisationProjets_data,'xlsx'), 'etatsRealisationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatsRealisationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatsRealisationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::etatsRealisationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatsRealisationProjets()
    {
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();
        return response()->json($etatsRealisationProjets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EtatsRealisationProjet) par ID, en format JSON.
     */
    public function getEtatsRealisationProjet(Request $request, $id)
    {
        try {
            $etatsRealisationProjet = $this->etatsRealisationProjetService->find($id);
            return response()->json($etatsRealisationProjet);
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
        $updatedEtatsRealisationProjet = $this->etatsRealisationProjetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEtatsRealisationProjet],
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
        $etatsRealisationProjetRequest = new EtatsRealisationProjetRequest();
        $fullRules = $etatsRealisationProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etats_realisation_projets,id'];
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
        $itemEtatsRealisationProjet = EtatsRealisationProjet::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEtatsRealisationProjet, $field);
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
        $itemEtatsRealisationProjet = EtatsRealisationProjet::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEtatsRealisationProjet);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEtatsRealisationProjet, $changes);

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