<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\CritereEvaluationService;
use Modules\PkgCompetences\Services\PhaseEvaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\CritereEvaluationRequest;
use Modules\PkgCompetences\Models\CritereEvaluation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\CritereEvaluationExport;
use Modules\PkgCompetences\App\Imports\CritereEvaluationImport;
use Modules\Core\Services\ContextState;

class BaseCritereEvaluationController extends AdminController
{
    protected $critereEvaluationService;
    protected $phaseEvaluationService;

    public function __construct(CritereEvaluationService $critereEvaluationService, PhaseEvaluationService $phaseEvaluationService) {
        parent::__construct();
        $this->service  =  $critereEvaluationService;
        $this->critereEvaluationService = $critereEvaluationService;
        $this->phaseEvaluationService = $phaseEvaluationService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('critereEvaluation.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('critereEvaluation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $critereEvaluations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'critereEvaluations_search',
                $this->viewState->get("filter.critereEvaluation.critereEvaluations_search")
            )],
            $request->except(['critereEvaluations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->critereEvaluationService->prepareDataForIndexView($critereEvaluations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::critereEvaluation._index', $critereEvaluation_compact_value)->render();
            }else{
                return view($critereEvaluation_partialViewName, $critereEvaluation_compact_value)->render();
            }
        }

        return view('PkgCompetences::critereEvaluation.index', $critereEvaluation_compact_value);
    }
    /**
     */
    public function create() {


        $itemCritereEvaluation = $this->critereEvaluationService->createInstance();
        

        $phaseEvaluations = $this->phaseEvaluationService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::critereEvaluation._fields', compact('bulkEdit' ,'itemCritereEvaluation', 'phaseEvaluations'));
        }
        return view('PkgCompetences::critereEvaluation.create', compact('bulkEdit' ,'itemCritereEvaluation', 'phaseEvaluations'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $critereEvaluation_ids = $request->input('ids', []);

        if (!is_array($critereEvaluation_ids) || count($critereEvaluation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemCritereEvaluation = $this->critereEvaluationService->find($critereEvaluation_ids[0]);
         
 
        $phaseEvaluations = $this->phaseEvaluationService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemCritereEvaluation = $this->critereEvaluationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::critereEvaluation._fields', compact('bulkEdit', 'critereEvaluation_ids', 'itemCritereEvaluation', 'phaseEvaluations'));
        }
        return view('PkgCompetences::critereEvaluation.bulk-edit', compact('bulkEdit', 'critereEvaluation_ids', 'itemCritereEvaluation', 'phaseEvaluations'));
    }
    /**
     */
    public function store(CritereEvaluationRequest $request) {
        $validatedData = $request->validated();
        $critereEvaluation = $this->critereEvaluationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' => __('PkgCompetences::critereEvaluation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $critereEvaluation->id]
            );
        }

        return redirect()->route('critereEvaluations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' => __('PkgCompetences::critereEvaluation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('critereEvaluation.show_' . $id);

        $itemCritereEvaluation = $this->critereEvaluationService->edit($id);


        if (request()->ajax()) {
            return view('PkgCompetences::critereEvaluation._show', array_merge(compact('itemCritereEvaluation'),));
        }

        return view('PkgCompetences::critereEvaluation.show', array_merge(compact('itemCritereEvaluation'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('critereEvaluation.edit_' . $id);


        $itemCritereEvaluation = $this->critereEvaluationService->edit($id);


        $phaseEvaluations = $this->phaseEvaluationService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::critereEvaluation._fields', array_merge(compact('bulkEdit' , 'itemCritereEvaluation','phaseEvaluations'),));
        }

        return view('PkgCompetences::critereEvaluation.edit', array_merge(compact('bulkEdit' ,'itemCritereEvaluation','phaseEvaluations'),));


    }
    /**
     */
    public function update(CritereEvaluationRequest $request, string $id) {

        $validatedData = $request->validated();
        $critereEvaluation = $this->critereEvaluationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' =>  __('PkgCompetences::critereEvaluation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $critereEvaluation->id]
            );
        }

        return redirect()->route('critereEvaluations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' =>  __('PkgCompetences::critereEvaluation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $critereEvaluation_ids = $request->input('critereEvaluation_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($critereEvaluation_ids) || count($critereEvaluation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($critereEvaluation_ids as $id) {
            $entity = $this->critereEvaluationService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->critereEvaluationService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->critereEvaluationService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $critereEvaluation = $this->critereEvaluationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' =>  __('PkgCompetences::critereEvaluation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('critereEvaluations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $critereEvaluation,
                'modelName' =>  __('PkgCompetences::critereEvaluation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $critereEvaluation_ids = $request->input('ids', []);
        if (!is_array($critereEvaluation_ids) || count($critereEvaluation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($critereEvaluation_ids as $id) {
            $entity = $this->critereEvaluationService->find($id);
            $this->critereEvaluationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($critereEvaluation_ids) . ' éléments',
            'modelName' => __('PkgCompetences::critereEvaluation.plural')
        ]));
    }

    public function export($format)
    {
        $critereEvaluations_data = $this->critereEvaluationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new CritereEvaluationExport($critereEvaluations_data,'csv'), 'critereEvaluation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new CritereEvaluationExport($critereEvaluations_data,'xlsx'), 'critereEvaluation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new CritereEvaluationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('critereEvaluations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('critereEvaluations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::critereEvaluation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCritereEvaluations()
    {
        $critereEvaluations = $this->critereEvaluationService->all();
        return response()->json($critereEvaluations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $critereEvaluation = $this->critereEvaluationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedCritereEvaluation = $this->critereEvaluationService->dataCalcul($critereEvaluation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedCritereEvaluation
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
        $critereEvaluationRequest = new CritereEvaluationRequest();
        $fullRules = $critereEvaluationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:critere_evaluations,id'];
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