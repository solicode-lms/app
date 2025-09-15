<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\EtatRealisationChapitreService;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\RealisationChapitreRequest;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\RealisationChapitreExport;
use Modules\PkgApprentissage\App\Imports\RealisationChapitreImport;
use Modules\Core\Services\ContextState;

class BaseRealisationChapitreController extends AdminController
{
    protected $realisationChapitreService;
    protected $etatRealisationChapitreService;
    protected $chapitreService;
    protected $realisationTacheService;
    protected $realisationUaService;

    public function __construct(RealisationChapitreService $realisationChapitreService, EtatRealisationChapitreService $etatRealisationChapitreService, ChapitreService $chapitreService, RealisationTacheService $realisationTacheService, RealisationUaService $realisationUaService) {
        parent::__construct();
        $this->service  =  $realisationChapitreService;
        $this->realisationChapitreService = $realisationChapitreService;
        $this->etatRealisationChapitreService = $etatRealisationChapitreService;
        $this->chapitreService = $chapitreService;
        $this->realisationTacheService = $realisationTacheService;
        $this->realisationUaService = $realisationUaService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationChapitre.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationChapitre');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.realisationChapitre.RealisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id') == null){
           $this->viewState->init('scope.realisationChapitre.RealisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('scope.realisationChapitre.RealisationUa.RealisationMicroCompetence.Apprenant_id') == null){
           $this->viewState->init('scope.realisationChapitre.RealisationUa.RealisationMicroCompetence.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $realisationChapitres_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationChapitres_search',
                $this->viewState->get("filter.realisationChapitre.realisationChapitres_search")
            )],
            $request->except(['realisationChapitres_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationChapitreService->prepareDataForIndexView($realisationChapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::realisationChapitre._index', $realisationChapitre_compact_value)->render();
            }else{
                return view($realisationChapitre_partialViewName, $realisationChapitre_compact_value)->render();
            }
        }

        return view('PkgApprentissage::realisationChapitre.index', $realisationChapitre_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationChapitre.RealisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationChapitre.RealisationUa.RealisationMicroCompetence.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemRealisationChapitre = $this->realisationChapitreService->createInstance();
        

        $chapitres = $this->chapitreService->all();
        $etatRealisationChapitres = $this->etatRealisationChapitreService->all();
        $realisationUas = $this->realisationUaService->all();
        $realisationTaches = $this->realisationTacheService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationChapitre._fields', compact('bulkEdit' ,'itemRealisationChapitre', 'etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationChapitre.create', compact('bulkEdit' ,'itemRealisationChapitre', 'etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationChapitre_ids = $request->input('ids', []);

        if (!is_array($realisationChapitre_ids) || count($realisationChapitre_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationChapitre.RealisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationChapitre.RealisationUa.RealisationMicroCompetence.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
 
         $itemRealisationChapitre = $this->realisationChapitreService->find($realisationChapitre_ids[0]);
         
 
        $chapitres = $this->chapitreService->getAllForSelect($itemRealisationChapitre->chapitre);
        $etatRealisationChapitres = $this->etatRealisationChapitreService->getAllForSelect($itemRealisationChapitre->etatRealisationChapitre);
        $realisationUas = $this->realisationUaService->getAllForSelect($itemRealisationChapitre->realisationUa);
        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemRealisationChapitre->realisationTache);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationChapitre = $this->realisationChapitreService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationChapitre._fields', compact('bulkEdit', 'realisationChapitre_ids', 'itemRealisationChapitre', 'etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationChapitre.bulk-edit', compact('bulkEdit', 'realisationChapitre_ids', 'itemRealisationChapitre', 'etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'));
    }
    /**
     */
    public function store(RealisationChapitreRequest $request) {
        $validatedData = $request->validated();
        $realisationChapitre = $this->realisationChapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' => __('PkgApprentissage::realisationChapitre.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationChapitre->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('realisationChapitres.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' => __('PkgApprentissage::realisationChapitre.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationChapitre.show_' . $id);

        $itemRealisationChapitre = $this->realisationChapitreService->edit($id);
        $this->authorize('view', $itemRealisationChapitre);


        if (request()->ajax()) {
            return view('PkgApprentissage::realisationChapitre._show', array_merge(compact('itemRealisationChapitre'),));
        }

        return view('PkgApprentissage::realisationChapitre.show', array_merge(compact('itemRealisationChapitre'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationChapitre.edit_' . $id);


        $itemRealisationChapitre = $this->realisationChapitreService->edit($id);
        $this->authorize('edit', $itemRealisationChapitre);


        $chapitres = $this->chapitreService->getAllForSelect($itemRealisationChapitre->chapitre);
        $etatRealisationChapitres = $this->etatRealisationChapitreService->getAllForSelect($itemRealisationChapitre->etatRealisationChapitre);
        $realisationUas = $this->realisationUaService->getAllForSelect($itemRealisationChapitre->realisationUa);
        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemRealisationChapitre->realisationTache);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationChapitre._fields', array_merge(compact('bulkEdit' , 'itemRealisationChapitre','etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'),));
        }

        return view('PkgApprentissage::realisationChapitre.edit', array_merge(compact('bulkEdit' ,'itemRealisationChapitre','etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'),));


    }
    /**
     */
    public function update(RealisationChapitreRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationChapitre = $this->realisationChapitreService->find($id);
        $this->authorize('update', $realisationChapitre);

        $validatedData = $request->validated();
        $realisationChapitre = $this->realisationChapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgApprentissage::realisationChapitre.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationChapitre->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgApprentissage::realisationChapitre.singular')
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
            'realisationChapitre_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('realisationChapitre_ids', []);
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
        $form         = new \Modules\PkgApprentissage\App\Requests\RealisationChapitreRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->realisationChapitreService->find($id);
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
        $realisationChapitre = $this->realisationChapitreService->find($id);
        $this->authorize('delete', $realisationChapitre);

        $realisationChapitre = $this->realisationChapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgApprentissage::realisationChapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgApprentissage::realisationChapitre.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationChapitre_ids = $request->input('ids', []);
        if (!is_array($realisationChapitre_ids) || count($realisationChapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationChapitre_ids as $id) {
            $entity = $this->realisationChapitreService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $realisationChapitre = $this->realisationChapitreService->find($id);
            $this->authorize('delete', $realisationChapitre);
            $this->realisationChapitreService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationChapitre_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::realisationChapitre.plural')
        ]));
    }

    public function export($format)
    {
        $realisationChapitres_data = $this->realisationChapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationChapitreExport($realisationChapitres_data,'csv'), 'realisationChapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationChapitreExport($realisationChapitres_data,'xlsx'), 'realisationChapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationChapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::realisationChapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationChapitres()
    {
        $realisationChapitres = $this->realisationChapitreService->all();
        return response()->json($realisationChapitres);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (RealisationChapitre) par ID, en format JSON.
     */
    public function getRealisationChapitre(Request $request, $id)
    {
        try {
            $realisationChapitre = $this->realisationChapitreService->find($id);
            return response()->json($realisationChapitre);
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
        $updatedRealisationChapitre = $this->realisationChapitreService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedRealisationChapitre],
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
        $realisationChapitreRequest = new RealisationChapitreRequest();
        $fullRules = $realisationChapitreRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_chapitres,id'];
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
        $itemRealisationChapitre = RealisationChapitre::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemRealisationChapitre, $field);
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
        $itemRealisationChapitre = RealisationChapitre::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemRealisationChapitre);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemRealisationChapitre, $changes);

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