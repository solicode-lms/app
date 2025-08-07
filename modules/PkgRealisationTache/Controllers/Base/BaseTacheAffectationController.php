<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Controllers\Base;
use Modules\PkgRealisationTache\Services\TacheAffectationService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationTache\App\Requests\TacheAffectationRequest;
use Modules\PkgRealisationTache\Models\TacheAffectation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationTache\App\Exports\TacheAffectationExport;
use Modules\PkgRealisationTache\App\Imports\TacheAffectationImport;
use Modules\Core\Services\ContextState;

class BaseTacheAffectationController extends AdminController
{
    protected $tacheAffectationService;
    protected $affectationProjetService;
    protected $tacheService;

    public function __construct(TacheAffectationService $tacheAffectationService, AffectationProjetService $affectationProjetService, TacheService $tacheService) {
        parent::__construct();
        $this->service  =  $tacheAffectationService;
        $this->tacheAffectationService = $tacheAffectationService;
        $this->affectationProjetService = $affectationProjetService;
        $this->tacheService = $tacheService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('tacheAffectation.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('tacheAffectation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $tacheAffectations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'tacheAffectations_search',
                $this->viewState->get("filter.tacheAffectation.tacheAffectations_search")
            )],
            $request->except(['tacheAffectations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->tacheAffectationService->prepareDataForIndexView($tacheAffectations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationTache::tacheAffectation._index', $tacheAffectation_compact_value)->render();
            }else{
                return view($tacheAffectation_partialViewName, $tacheAffectation_compact_value)->render();
            }
        }

        return view('PkgRealisationTache::tacheAffectation.index', $tacheAffectation_compact_value);
    }
    /**
     */
    public function create() {


        $itemTacheAffectation = $this->tacheAffectationService->createInstance();
        

        $taches = $this->tacheService->all();
        $affectationProjets = $this->affectationProjetService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationTache::tacheAffectation._fields', compact('bulkEdit' ,'itemTacheAffectation', 'affectationProjets', 'taches'));
        }
        return view('PkgRealisationTache::tacheAffectation.create', compact('bulkEdit' ,'itemTacheAffectation', 'affectationProjets', 'taches'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $tacheAffectation_ids = $request->input('ids', []);

        if (!is_array($tacheAffectation_ids) || count($tacheAffectation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemTacheAffectation = $this->tacheAffectationService->find($tacheAffectation_ids[0]);
         
 
        $taches = $this->tacheService->all();
        $affectationProjets = $this->affectationProjetService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemTacheAffectation = $this->tacheAffectationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationTache::tacheAffectation._fields', compact('bulkEdit', 'tacheAffectation_ids', 'itemTacheAffectation', 'affectationProjets', 'taches'));
        }
        return view('PkgRealisationTache::tacheAffectation.bulk-edit', compact('bulkEdit', 'tacheAffectation_ids', 'itemTacheAffectation', 'affectationProjets', 'taches'));
    }
    /**
     */
    public function store(TacheAffectationRequest $request) {
        $validatedData = $request->validated();
        $tacheAffectation = $this->tacheAffectationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $tacheAffectation,
                'modelName' => __('PkgRealisationTache::tacheAffectation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $tacheAffectation->id]
            );
        }

        return redirect()->route('tacheAffectations.edit',['tacheAffectation' => $tacheAffectation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $tacheAffectation,
                'modelName' => __('PkgRealisationTache::tacheAffectation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('tacheAffectation.show_' . $id);

        $itemTacheAffectation = $this->tacheAffectationService->edit($id);


        $this->viewState->set('scope.realisationTache.tache_affectation_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_view_data = $realisationTacheService->prepareDataForIndexView();
        extract($realisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationTache::tacheAffectation._show', array_merge(compact('itemTacheAffectation'),$realisationTache_compact_value));
        }

        return view('PkgRealisationTache::tacheAffectation.show', array_merge(compact('itemTacheAffectation'),$realisationTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('tacheAffectation.edit_' . $id);


        $itemTacheAffectation = $this->tacheAffectationService->edit($id);


        $taches = $this->tacheService->all();
        $affectationProjets = $this->affectationProjetService->all();


        $this->viewState->set('scope.realisationTache.tache_affectation_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_view_data = $realisationTacheService->prepareDataForIndexView();
        extract($realisationTaches_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgRealisationTache::tacheAffectation._edit', array_merge(compact('bulkEdit' , 'itemTacheAffectation','affectationProjets', 'taches'),$realisationTache_compact_value));
        }

        return view('PkgRealisationTache::tacheAffectation.edit', array_merge(compact('bulkEdit' ,'itemTacheAffectation','affectationProjets', 'taches'),$realisationTache_compact_value));


    }
    /**
     */
    public function update(TacheAffectationRequest $request, string $id) {

        $validatedData = $request->validated();
        $tacheAffectation = $this->tacheAffectationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $tacheAffectation,
                'modelName' =>  __('PkgRealisationTache::tacheAffectation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $tacheAffectation->id]
            );
        }

        return redirect()->route('tacheAffectations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $tacheAffectation,
                'modelName' =>  __('PkgRealisationTache::tacheAffectation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $tacheAffectation_ids = $request->input('tacheAffectation_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($tacheAffectation_ids) || count($tacheAffectation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($tacheAffectation_ids as $id) {
            $entity = $this->tacheAffectationService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->tacheAffectationService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->tacheAffectationService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $tacheAffectation = $this->tacheAffectationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $tacheAffectation,
                'modelName' =>  __('PkgRealisationTache::tacheAffectation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('tacheAffectations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $tacheAffectation,
                'modelName' =>  __('PkgRealisationTache::tacheAffectation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $tacheAffectation_ids = $request->input('ids', []);
        if (!is_array($tacheAffectation_ids) || count($tacheAffectation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($tacheAffectation_ids as $id) {
            $entity = $this->tacheAffectationService->find($id);
            $this->tacheAffectationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($tacheAffectation_ids) . ' éléments',
            'modelName' => __('PkgRealisationTache::tacheAffectation.plural')
        ]));
    }

    public function export($format)
    {
        $tacheAffectations_data = $this->tacheAffectationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new TacheAffectationExport($tacheAffectations_data,'csv'), 'tacheAffectation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new TacheAffectationExport($tacheAffectations_data,'xlsx'), 'tacheAffectation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new TacheAffectationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('tacheAffectations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('tacheAffectations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationTache::tacheAffectation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTacheAffectations()
    {
        $tacheAffectations = $this->tacheAffectationService->all();
        return response()->json($tacheAffectations);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (TacheAffectation) par ID, en format JSON.
     */
    public function getTacheAffectation(Request $request, $id)
    {
        try {
            $tacheAffectation = $this->tacheAffectationService->find($id);
            return response()->json($tacheAffectation);
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
        $updatedTacheAffectation = $this->tacheAffectationService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedTacheAffectation
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
        $tacheAffectationRequest = new TacheAffectationRequest();
        $fullRules = $tacheAffectationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:tache_affectations,id'];
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