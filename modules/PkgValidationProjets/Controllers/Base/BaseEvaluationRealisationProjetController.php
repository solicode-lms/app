<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgValidationProjets\Controllers\Base;
use Modules\PkgValidationProjets\Services\EvaluationRealisationProjetService;
use Modules\PkgValidationProjets\Services\EtatEvaluationProjetService;
use Modules\PkgValidationProjets\Services\EvaluateurService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgValidationProjets\Services\EvaluationRealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgValidationProjets\App\Requests\EvaluationRealisationProjetRequest;
use Modules\PkgValidationProjets\Models\EvaluationRealisationProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgValidationProjets\App\Exports\EvaluationRealisationProjetExport;
use Modules\PkgValidationProjets\App\Imports\EvaluationRealisationProjetImport;
use Modules\Core\Services\ContextState;

class BaseEvaluationRealisationProjetController extends AdminController
{
    protected $evaluationRealisationProjetService;
    protected $etatEvaluationProjetService;
    protected $evaluateurService;
    protected $realisationProjetService;

    public function __construct(EvaluationRealisationProjetService $evaluationRealisationProjetService, EtatEvaluationProjetService $etatEvaluationProjetService, EvaluateurService $evaluateurService, RealisationProjetService $realisationProjetService) {
        parent::__construct();
        $this->service  =  $evaluationRealisationProjetService;
        $this->evaluationRealisationProjetService = $evaluationRealisationProjetService;
        $this->etatEvaluationProjetService = $etatEvaluationProjetService;
        $this->evaluateurService = $evaluateurService;
        $this->realisationProjetService = $realisationProjetService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('evaluationRealisationProjet.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('evaluationRealisationProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.evaluationRealisationProjet.RealisationProjet.AffectationProjet.Projet.Formateur_id') == null){
           $this->viewState->init('scope.evaluationRealisationProjet.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('evaluateur') && $this->viewState->get('scope.evaluationRealisationProjet.RealisationProjet.AffectationProjet.Evaluateurs_id') == null){
           $this->viewState->init('scope.evaluationRealisationProjet.RealisationProjet.AffectationProjet.Evaluateurs_id'  , $this->sessionState->get('evaluateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $evaluationRealisationProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'evaluationRealisationProjets_search',
                $this->viewState->get("filter.evaluationRealisationProjet.evaluationRealisationProjets_search")
            )],
            $request->except(['evaluationRealisationProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->evaluationRealisationProjetService->prepareDataForIndexView($evaluationRealisationProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgValidationProjets::evaluationRealisationProjet._index', $evaluationRealisationProjet_compact_value)->render();
            }else{
                return view($evaluationRealisationProjet_partialViewName, $evaluationRealisationProjet_compact_value)->render();
            }
        }

        return view('PkgValidationProjets::evaluationRealisationProjet.index', $evaluationRealisationProjet_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.evaluationRealisationProjet.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('evaluateur')){
           $this->viewState->set('scope_form.evaluationRealisationProjet.RealisationProjet.AffectationProjet.Evaluateurs_id'  , $this->sessionState->get('evaluateur_id'));
        }


        $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->createInstance();
        

        $realisationProjets = $this->realisationProjetService->all();
        $evaluateurs = $this->evaluateurService->all();
        $etatEvaluationProjets = $this->etatEvaluationProjetService->all();

        if (request()->ajax()) {
            return view('PkgValidationProjets::evaluationRealisationProjet._fields', compact('itemEvaluationRealisationProjet', 'etatEvaluationProjets', 'evaluateurs', 'realisationProjets'));
        }
        return view('PkgValidationProjets::evaluationRealisationProjet.create', compact('itemEvaluationRealisationProjet', 'etatEvaluationProjets', 'evaluateurs', 'realisationProjets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $evaluationRealisationProjet_ids = $request->input('ids', []);

        if (!is_array($evaluationRealisationProjet_ids) || count($evaluationRealisationProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.evaluationRealisationProjet.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('evaluateur')){
           $this->viewState->set('scope_form.evaluationRealisationProjet.RealisationProjet.AffectationProjet.Evaluateurs_id'  , $this->sessionState->get('evaluateur_id'));
        }
 
         $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->find($evaluationRealisationProjet_ids[0]);
         
 
        $realisationProjets = $this->realisationProjetService->all();
        $evaluateurs = $this->evaluateurService->all();
        $etatEvaluationProjets = $this->etatEvaluationProjetService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgValidationProjets::evaluationRealisationProjet._fields', compact('bulkEdit', 'evaluationRealisationProjet_ids', 'itemEvaluationRealisationProjet', 'etatEvaluationProjets', 'evaluateurs', 'realisationProjets'));
        }
        return view('PkgValidationProjets::evaluationRealisationProjet.bulk-edit', compact('bulkEdit', 'evaluationRealisationProjet_ids', 'itemEvaluationRealisationProjet', 'etatEvaluationProjets', 'evaluateurs', 'realisationProjets'));
    }
    /**
     */
    public function store(EvaluationRealisationProjetRequest $request) {
        $validatedData = $request->validated();
        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' => __('PkgValidationProjets::evaluationRealisationProjet.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $evaluationRealisationProjet->id]
            );
        }

        return redirect()->route('evaluationRealisationProjets.edit',['evaluationRealisationProjet' => $evaluationRealisationProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' => __('PkgValidationProjets::evaluationRealisationProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('evaluationRealisationProjet.show_' . $id);

        $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->edit($id);
        $this->authorize('view', $itemEvaluationRealisationProjet);


        $this->viewState->set('scope.evaluationRealisationTache.evaluation_realisation_projet_id', $id);
        

        $evaluationRealisationTacheService =  new EvaluationRealisationTacheService();
        $evaluationRealisationTaches_view_data = $evaluationRealisationTacheService->prepareDataForIndexView();
        extract($evaluationRealisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgValidationProjets::evaluationRealisationProjet._show', array_merge(compact('itemEvaluationRealisationProjet'),$evaluationRealisationTache_compact_value));
        }

        return view('PkgValidationProjets::evaluationRealisationProjet.show', array_merge(compact('itemEvaluationRealisationProjet'),$evaluationRealisationTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('evaluationRealisationProjet.edit_' . $id);


        $itemEvaluationRealisationProjet = $this->evaluationRealisationProjetService->edit($id);
        $this->authorize('edit', $itemEvaluationRealisationProjet);


        $realisationProjets = $this->realisationProjetService->all();
        $evaluateurs = $this->evaluateurService->all();
        $etatEvaluationProjets = $this->etatEvaluationProjetService->all();


        $this->viewState->set('scope.evaluationRealisationTache.evaluation_realisation_projet_id', $id);
        

        $evaluationRealisationTacheService =  new EvaluationRealisationTacheService();
        $evaluationRealisationTaches_view_data = $evaluationRealisationTacheService->prepareDataForIndexView();
        extract($evaluationRealisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgValidationProjets::evaluationRealisationProjet._edit', array_merge(compact('itemEvaluationRealisationProjet','etatEvaluationProjets', 'evaluateurs', 'realisationProjets'),$evaluationRealisationTache_compact_value));
        }

        return view('PkgValidationProjets::evaluationRealisationProjet.edit', array_merge(compact('itemEvaluationRealisationProjet','etatEvaluationProjets', 'evaluateurs', 'realisationProjets'),$evaluationRealisationTache_compact_value));


    }
    /**
     */
    public function update(EvaluationRealisationProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->find($id);
        $this->authorize('update', $evaluationRealisationProjet);

        $validatedData = $request->validated();
        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' =>  __('PkgValidationProjets::evaluationRealisationProjet.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $evaluationRealisationProjet->id]
            );
        }

        return redirect()->route('evaluationRealisationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' =>  __('PkgValidationProjets::evaluationRealisationProjet.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $evaluationRealisationProjet_ids = $request->input('evaluationRealisationProjet_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($evaluationRealisationProjet_ids) || count($evaluationRealisationProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($evaluationRealisationProjet_ids as $id) {
            $entity = $this->evaluationRealisationProjetService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->evaluationRealisationProjetService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->evaluationRealisationProjetService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->find($id);
        $this->authorize('delete', $evaluationRealisationProjet);

        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' =>  __('PkgValidationProjets::evaluationRealisationProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('evaluationRealisationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $evaluationRealisationProjet,
                'modelName' =>  __('PkgValidationProjets::evaluationRealisationProjet.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $evaluationRealisationProjet_ids = $request->input('ids', []);
        if (!is_array($evaluationRealisationProjet_ids) || count($evaluationRealisationProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($evaluationRealisationProjet_ids as $id) {
            $entity = $this->evaluationRealisationProjetService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $evaluationRealisationProjet = $this->evaluationRealisationProjetService->find($id);
            $this->authorize('delete', $evaluationRealisationProjet);
            $this->evaluationRealisationProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($evaluationRealisationProjet_ids) . ' éléments',
            'modelName' => __('PkgValidationProjets::evaluationRealisationProjet.plural')
        ]));
    }

    public function export($format)
    {
        $evaluationRealisationProjets_data = $this->evaluationRealisationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EvaluationRealisationProjetExport($evaluationRealisationProjets_data,'csv'), 'evaluationRealisationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EvaluationRealisationProjetExport($evaluationRealisationProjets_data,'xlsx'), 'evaluationRealisationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EvaluationRealisationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('evaluationRealisationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('evaluationRealisationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgValidationProjets::evaluationRealisationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEvaluationRealisationProjets()
    {
        $evaluationRealisationProjets = $this->evaluationRealisationProjetService->all();
        return response()->json($evaluationRealisationProjets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $evaluationRealisationProjet = $this->evaluationRealisationProjetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEvaluationRealisationProjet = $this->evaluationRealisationProjetService->dataCalcul($evaluationRealisationProjet);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEvaluationRealisationProjet
        ]);
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
        $evaluationRealisationProjetRequest = new EvaluationRealisationProjetRequest();
        $fullRules = $evaluationRealisationProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:evaluation_realisation_projets,id'];
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