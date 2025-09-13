<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgApprenants\Services\SousGroupeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\GroupeRequest;
use Modules\PkgApprenants\Models\Groupe;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\App\Exports\GroupeExport;
use Modules\PkgApprenants\App\Imports\GroupeImport;
use Modules\Core\Services\ContextState;

class BaseGroupeController extends AdminController
{
    protected $groupeService;
    protected $apprenantService;
    protected $formateurService;
    protected $anneeFormationService;
    protected $filiereService;

    public function __construct(GroupeService $groupeService, ApprenantService $apprenantService, FormateurService $formateurService, AnneeFormationService $anneeFormationService, FiliereService $filiereService) {
        parent::__construct();
        $this->service  =  $groupeService;
        $this->groupeService = $groupeService;
        $this->apprenantService = $apprenantService;
        $this->formateurService = $formateurService;
        $this->anneeFormationService = $anneeFormationService;
        $this->filiereService = $filiereService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('groupe.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('groupe');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $groupes_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'groupes_search',
                $this->viewState->get("filter.groupe.groupes_search")
            )],
            $request->except(['groupes_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->groupeService->prepareDataForIndexView($groupes_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprenants::groupe._index', $groupe_compact_value)->render();
            }else{
                return view($groupe_partialViewName, $groupe_compact_value)->render();
            }
        }

        return view('PkgApprenants::groupe.index', $groupe_compact_value);
    }
    /**
     */
    public function create() {


        $itemGroupe = $this->groupeService->createInstance();
        

        $filieres = $this->filiereService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $apprenants = $this->apprenantService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprenants::groupe._fields', compact('bulkEdit' ,'itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres'));
        }
        return view('PkgApprenants::groupe.create', compact('bulkEdit' ,'itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $groupe_ids = $request->input('ids', []);

        if (!is_array($groupe_ids) || count($groupe_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemGroupe = $this->groupeService->find($groupe_ids[0]);
         
 
        $filieres = $this->filiereService->getAllForSelect($itemGroupe->filiere);
        $anneeFormations = $this->anneeFormationService->getAllForSelect($itemGroupe->anneeFormation);
        $apprenants = $this->apprenantService->getAllForSelect($itemGroupe->apprenants);
        $formateurs = $this->formateurService->getAllForSelect($itemGroupe->formateurs);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemGroupe = $this->groupeService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprenants::groupe._fields', compact('bulkEdit', 'groupe_ids', 'itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres'));
        }
        return view('PkgApprenants::groupe.bulk-edit', compact('bulkEdit', 'groupe_ids', 'itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres'));
    }
    /**
     */
    public function store(GroupeRequest $request) {
        $validatedData = $request->validated();
        $groupe = $this->groupeService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $groupe,
                'modelName' => __('PkgApprenants::groupe.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $groupe->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('groupes.edit', ['groupe' => $groupe->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $groupe,
                'modelName' => __('PkgApprenants::groupe.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('groupe.show_' . $id);

        $itemGroupe = $this->groupeService->edit($id);


        $this->viewState->set('scope.affectationProjet.groupe_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.sousGroupe.groupe_id', $id);
        

        $sousGroupeService =  new SousGroupeService();
        $sousGroupes_view_data = $sousGroupeService->prepareDataForIndexView();
        extract($sousGroupes_view_data);

        if (request()->ajax()) {
            return view('PkgApprenants::groupe._show', array_merge(compact('itemGroupe'),$affectationProjet_compact_value, $sousGroupe_compact_value));
        }

        return view('PkgApprenants::groupe.show', array_merge(compact('itemGroupe'),$affectationProjet_compact_value, $sousGroupe_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('groupe.edit_' . $id);


        $itemGroupe = $this->groupeService->edit($id);


        $filieres = $this->filiereService->getAllForSelect($itemGroupe->filiere);
        $anneeFormations = $this->anneeFormationService->getAllForSelect($itemGroupe->anneeFormation);
        $apprenants = $this->apprenantService->getAllForSelect($itemGroupe->apprenants);
        $formateurs = $this->formateurService->getAllForSelect($itemGroupe->formateurs);


        $this->viewState->set('scope.affectationProjet.groupe_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.sousGroupe.groupe_id', $id);
        

        $sousGroupeService =  new SousGroupeService();
        $sousGroupes_view_data = $sousGroupeService->prepareDataForIndexView();
        extract($sousGroupes_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprenants::groupe._edit', array_merge(compact('bulkEdit' , 'itemGroupe','apprenants', 'formateurs', 'anneeFormations', 'filieres'),$affectationProjet_compact_value, $sousGroupe_compact_value));
        }

        return view('PkgApprenants::groupe.edit', array_merge(compact('bulkEdit' ,'itemGroupe','apprenants', 'formateurs', 'anneeFormations', 'filieres'),$affectationProjet_compact_value, $sousGroupe_compact_value));


    }
    /**
     */
    public function update(GroupeRequest $request, string $id) {

        $validatedData = $request->validated();
        $groupe = $this->groupeService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $groupe->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')
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
            'groupe_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('groupe_ids', []);
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
        $form         = new \Modules\PkgApprenants\App\Requests\GroupeRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->groupeService->find($id);
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

        $groupe = $this->groupeService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $groupe_ids = $request->input('ids', []);
        if (!is_array($groupe_ids) || count($groupe_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($groupe_ids as $id) {
            $entity = $this->groupeService->find($id);
            $this->groupeService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($groupe_ids) . ' éléments',
            'modelName' => __('PkgApprenants::groupe.plural')
        ]));
    }

    public function export($format)
    {
        $groupes_data = $this->groupeService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new GroupeExport($groupes_data,'csv'), 'groupe_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new GroupeExport($groupes_data,'xlsx'), 'groupe_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new GroupeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('groupes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('groupes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::groupe.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getGroupes()
    {
        $groupes = $this->groupeService->all();
        return response()->json($groupes);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Groupe) par ID, en format JSON.
     */
    public function getGroupe(Request $request, $id)
    {
        try {
            $groupe = $this->groupeService->find($id);
            return response()->json($groupe);
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
        $updatedGroupe = $this->groupeService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedGroupe],
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
        $groupeRequest = new GroupeRequest();
        $fullRules = $groupeRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:groupes,id'];
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
        $itemGroupe = Groupe::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemGroupe, $field);
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
        $itemGroupe = Groupe::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemGroupe);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemGroupe, $changes);

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