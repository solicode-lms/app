<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgValidationProjets\Controllers\Base;
use Modules\PkgValidationProjets\Services\EvaluateurService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgValidationProjets\Services\EvaluationRealisationProjetService;
use Modules\PkgValidationProjets\Services\EvaluationRealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgValidationProjets\App\Requests\EvaluateurRequest;
use Modules\PkgValidationProjets\Models\Evaluateur;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgValidationProjets\App\Exports\EvaluateurExport;
use Modules\PkgValidationProjets\App\Imports\EvaluateurImport;
use Modules\Core\Services\ContextState;

class BaseEvaluateurController extends AdminController
{
    protected $evaluateurService;
    protected $affectationProjetService;
    protected $userService;

    public function __construct(EvaluateurService $evaluateurService, AffectationProjetService $affectationProjetService, UserService $userService) {
        parent::__construct();
        $this->service  =  $evaluateurService;
        $this->evaluateurService = $evaluateurService;
        $this->affectationProjetService = $affectationProjetService;
        $this->userService = $userService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('evaluateur.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('evaluateur');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $evaluateurs_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'evaluateurs_search',
                $this->viewState->get("filter.evaluateur.evaluateurs_search")
            )],
            $request->except(['evaluateurs_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->evaluateurService->prepareDataForIndexView($evaluateurs_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgValidationProjets::evaluateur._index', $evaluateur_compact_value)->render();
            }else{
                return view($evaluateur_partialViewName, $evaluateur_compact_value)->render();
            }
        }

        return view('PkgValidationProjets::evaluateur.index', $evaluateur_compact_value);
    }
    /**
     */
    public function create() {


        $itemEvaluateur = $this->evaluateurService->createInstance();
        

        $users = $this->userService->all();
        $affectationProjets = $this->affectationProjetService->all();

        if (request()->ajax()) {
            return view('PkgValidationProjets::evaluateur._fields', compact('itemEvaluateur', 'affectationProjets', 'users'));
        }
        return view('PkgValidationProjets::evaluateur.create', compact('itemEvaluateur', 'affectationProjets', 'users'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $evaluateur_ids = $request->input('ids', []);

        if (!is_array($evaluateur_ids) || count($evaluateur_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEvaluateur = $this->evaluateurService->find($evaluateur_ids[0]);
         
 
        $users = $this->userService->all();
        $affectationProjets = $this->affectationProjetService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEvaluateur = $this->evaluateurService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgValidationProjets::evaluateur._fields', compact('bulkEdit', 'evaluateur_ids', 'itemEvaluateur', 'affectationProjets', 'users'));
        }
        return view('PkgValidationProjets::evaluateur.bulk-edit', compact('bulkEdit', 'evaluateur_ids', 'itemEvaluateur', 'affectationProjets', 'users'));
    }
    /**
     */
    public function store(EvaluateurRequest $request) {
        $validatedData = $request->validated();
        $evaluateur = $this->evaluateurService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $evaluateur,
                'modelName' => __('PkgValidationProjets::evaluateur.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $evaluateur->id]
            );
        }

        return redirect()->route('evaluateurs.edit',['evaluateur' => $evaluateur->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $evaluateur,
                'modelName' => __('PkgValidationProjets::evaluateur.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('evaluateur.show_' . $id);

        $itemEvaluateur = $this->evaluateurService->edit($id);


        $this->viewState->set('scope.evaluationRealisationProjet.evaluateur_id', $id);
        

        $evaluationRealisationProjetService =  new EvaluationRealisationProjetService();
        $evaluationRealisationProjets_view_data = $evaluationRealisationProjetService->prepareDataForIndexView();
        extract($evaluationRealisationProjets_view_data);

        $this->viewState->set('scope.evaluationRealisationTache.evaluateur_id', $id);
        

        $evaluationRealisationTacheService =  new EvaluationRealisationTacheService();
        $evaluationRealisationTaches_view_data = $evaluationRealisationTacheService->prepareDataForIndexView();
        extract($evaluationRealisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgValidationProjets::evaluateur._show', array_merge(compact('itemEvaluateur'),$evaluationRealisationProjet_compact_value, $evaluationRealisationTache_compact_value));
        }

        return view('PkgValidationProjets::evaluateur.show', array_merge(compact('itemEvaluateur'),$evaluationRealisationProjet_compact_value, $evaluationRealisationTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('evaluateur.edit_' . $id);


        $itemEvaluateur = $this->evaluateurService->edit($id);


        $users = $this->userService->all();
        $affectationProjets = $this->affectationProjetService->all();


        $this->viewState->set('scope.evaluationRealisationProjet.evaluateur_id', $id);
        

        $evaluationRealisationProjetService =  new EvaluationRealisationProjetService();
        $evaluationRealisationProjets_view_data = $evaluationRealisationProjetService->prepareDataForIndexView();
        extract($evaluationRealisationProjets_view_data);

        if (request()->ajax()) {
            return view('PkgValidationProjets::evaluateur._edit', array_merge(compact('itemEvaluateur','affectationProjets', 'users'),$evaluationRealisationProjet_compact_value));
        }

        return view('PkgValidationProjets::evaluateur.edit', array_merge(compact('itemEvaluateur','affectationProjets', 'users'),$evaluationRealisationProjet_compact_value));


    }
    /**
     */
    public function update(EvaluateurRequest $request, string $id) {

        $validatedData = $request->validated();
        $evaluateur = $this->evaluateurService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $evaluateur,
                'modelName' =>  __('PkgValidationProjets::evaluateur.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $evaluateur->id]
            );
        }

        return redirect()->route('evaluateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $evaluateur,
                'modelName' =>  __('PkgValidationProjets::evaluateur.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $evaluateur_ids = $request->input('evaluateur_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($evaluateur_ids) || count($evaluateur_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($evaluateur_ids as $id) {
            $entity = $this->evaluateurService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->evaluateurService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->evaluateurService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $evaluateur = $this->evaluateurService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluateur,
                'modelName' =>  __('PkgValidationProjets::evaluateur.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('evaluateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluateur,
                'modelName' =>  __('PkgValidationProjets::evaluateur.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $evaluateur_ids = $request->input('ids', []);
        if (!is_array($evaluateur_ids) || count($evaluateur_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($evaluateur_ids as $id) {
            $entity = $this->evaluateurService->find($id);
            $this->evaluateurService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($evaluateur_ids) . ' éléments',
            'modelName' => __('PkgValidationProjets::evaluateur.plural')
        ]));
    }

    public function export($format)
    {
        $evaluateurs_data = $this->evaluateurService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EvaluateurExport($evaluateurs_data,'csv'), 'evaluateur_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EvaluateurExport($evaluateurs_data,'xlsx'), 'evaluateur_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EvaluateurImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('evaluateurs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('evaluateurs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgValidationProjets::evaluateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEvaluateurs()
    {
        $evaluateurs = $this->evaluateurService->all();
        return response()->json($evaluateurs);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $evaluateur = $this->evaluateurService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEvaluateur = $this->evaluateurService->dataCalcul($evaluateur);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEvaluateur
        ]);
    }
    
    public function initPassword(Request $request, string $id) {
        $evaluateur = $this->evaluateurService->initPassword($id);
        if ($request->ajax()) {
            $message = "Le mot de passe a été modifier avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('Evaluateur.index')->with(
            'success',
            "Le mot de passe a été modifier avec succès"
        );
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
        $evaluateurRequest = new EvaluateurRequest();
        $fullRules = $evaluateurRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:evaluateurs,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}