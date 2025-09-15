<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\EtatRealisationChapitreService;
use Modules\Core\Services\SysColorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\EtatRealisationChapitreRequest;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\EtatRealisationChapitreExport;
use Modules\PkgApprentissage\App\Imports\EtatRealisationChapitreImport;
use Modules\Core\Services\ContextState;

class BaseEtatRealisationChapitreController extends AdminController
{
    protected $etatRealisationChapitreService;
    protected $sysColorService;

    public function __construct(EtatRealisationChapitreService $etatRealisationChapitreService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $etatRealisationChapitreService;
        $this->etatRealisationChapitreService = $etatRealisationChapitreService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('etatRealisationChapitre.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('etatRealisationChapitre');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $etatRealisationChapitres_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatRealisationChapitres_search',
                $this->viewState->get("filter.etatRealisationChapitre.etatRealisationChapitres_search")
            )],
            $request->except(['etatRealisationChapitres_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatRealisationChapitreService->prepareDataForIndexView($etatRealisationChapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::etatRealisationChapitre._index', $etatRealisationChapitre_compact_value)->render();
            }else{
                return view($etatRealisationChapitre_partialViewName, $etatRealisationChapitre_compact_value)->render();
            }
        }

        return view('PkgApprentissage::etatRealisationChapitre.index', $etatRealisationChapitre_compact_value);
    }
    /**
     */
    public function create() {


        $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationChapitre._fields', compact('bulkEdit' ,'itemEtatRealisationChapitre', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationChapitre.create', compact('bulkEdit' ,'itemEtatRealisationChapitre', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatRealisationChapitre_ids = $request->input('ids', []);

        if (!is_array($etatRealisationChapitre_ids) || count($etatRealisationChapitre_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->find($etatRealisationChapitre_ids[0]);
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationChapitre->sysColor);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationChapitre._fields', compact('bulkEdit', 'etatRealisationChapitre_ids', 'itemEtatRealisationChapitre', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationChapitre.bulk-edit', compact('bulkEdit', 'etatRealisationChapitre_ids', 'itemEtatRealisationChapitre', 'sysColors'));
    }
    /**
     */
    public function store(EtatRealisationChapitreRequest $request) {
        $validatedData = $request->validated();
        $etatRealisationChapitre = $this->etatRealisationChapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' => __('PkgApprentissage::etatRealisationChapitre.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationChapitre->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('etatRealisationChapitres.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' => __('PkgApprentissage::etatRealisationChapitre.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatRealisationChapitre.show_' . $id);

        $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->edit($id);


        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationChapitre._show', array_merge(compact('itemEtatRealisationChapitre'),));
        }

        return view('PkgApprentissage::etatRealisationChapitre.show', array_merge(compact('itemEtatRealisationChapitre'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatRealisationChapitre.edit_' . $id);


        $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->edit($id);


        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationChapitre->sysColor);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationChapitre._fields', array_merge(compact('bulkEdit' , 'itemEtatRealisationChapitre','sysColors'),));
        }

        return view('PkgApprentissage::etatRealisationChapitre.edit', array_merge(compact('bulkEdit' ,'itemEtatRealisationChapitre','sysColors'),));


    }
    /**
     */
    public function update(EtatRealisationChapitreRequest $request, string $id) {

        $validatedData = $request->validated();
        $etatRealisationChapitre = $this->etatRealisationChapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' =>  __('PkgApprentissage::etatRealisationChapitre.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationChapitre->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('etatRealisationChapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' =>  __('PkgApprentissage::etatRealisationChapitre.singular')
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
            'etatRealisationChapitre_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('etatRealisationChapitre_ids', []);
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
        $form         = new \Modules\PkgApprentissage\App\Requests\EtatRealisationChapitreRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->etatRealisationChapitreService->find($id);
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

        $etatRealisationChapitre = $this->etatRealisationChapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' =>  __('PkgApprentissage::etatRealisationChapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('etatRealisationChapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' =>  __('PkgApprentissage::etatRealisationChapitre.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatRealisationChapitre_ids = $request->input('ids', []);
        if (!is_array($etatRealisationChapitre_ids) || count($etatRealisationChapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatRealisationChapitre_ids as $id) {
            $entity = $this->etatRealisationChapitreService->find($id);
            $this->etatRealisationChapitreService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatRealisationChapitre_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::etatRealisationChapitre.plural')
        ]));
    }

    public function export($format)
    {
        $etatRealisationChapitres_data = $this->etatRealisationChapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatRealisationChapitreExport($etatRealisationChapitres_data,'csv'), 'etatRealisationChapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatRealisationChapitreExport($etatRealisationChapitres_data,'xlsx'), 'etatRealisationChapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatRealisationChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatRealisationChapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatRealisationChapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::etatRealisationChapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatRealisationChapitres()
    {
        $etatRealisationChapitres = $this->etatRealisationChapitreService->all();
        return response()->json($etatRealisationChapitres);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EtatRealisationChapitre) par ID, en format JSON.
     */
    public function getEtatRealisationChapitre(Request $request, $id)
    {
        try {
            $etatRealisationChapitre = $this->etatRealisationChapitreService->find($id);
            return response()->json($etatRealisationChapitre);
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
        $updatedEtatRealisationChapitre = $this->etatRealisationChapitreService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEtatRealisationChapitre],
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
        $etatRealisationChapitreRequest = new EtatRealisationChapitreRequest();
        $fullRules = $etatRealisationChapitreRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_realisation_chapitres,id'];
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
        $itemEtatRealisationChapitre = EtatRealisationChapitre::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEtatRealisationChapitre, $field);
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
        $itemEtatRealisationChapitre = EtatRealisationChapitre::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEtatRealisationChapitre);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEtatRealisationChapitre, $changes);

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