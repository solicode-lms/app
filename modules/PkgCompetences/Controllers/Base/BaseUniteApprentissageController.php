<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Modules\PkgCompetences\Services\MicroCompetenceService;
use Modules\PkgSessions\Services\AlignementUaService;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgCompetences\Services\CritereEvaluationService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgCreationProjet\Services\MobilisationUaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\UniteApprentissageRequest;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\UniteApprentissageExport;
use Modules\PkgCompetences\App\Imports\UniteApprentissageImport;
use Modules\Core\Services\ContextState;

class BaseUniteApprentissageController extends AdminController
{
    protected $uniteApprentissageService;
    protected $microCompetenceService;

    public function __construct(UniteApprentissageService $uniteApprentissageService, MicroCompetenceService $microCompetenceService) {
        parent::__construct();
        $this->service  =  $uniteApprentissageService;
        $this->uniteApprentissageService = $uniteApprentissageService;
        $this->microCompetenceService = $microCompetenceService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('uniteApprentissage.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('uniteApprentissage');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $uniteApprentissages_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'uniteApprentissages_search',
                $this->viewState->get("filter.uniteApprentissage.uniteApprentissages_search")
            )],
            $request->except(['uniteApprentissages_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->uniteApprentissageService->prepareDataForIndexView($uniteApprentissages_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::uniteApprentissage._index', $uniteApprentissage_compact_value)->render();
            }else{
                return view($uniteApprentissage_partialViewName, $uniteApprentissage_compact_value)->render();
            }
        }

        return view('PkgCompetences::uniteApprentissage.index', $uniteApprentissage_compact_value);
    }
    /**
     */
    public function create() {


        $itemUniteApprentissage = $this->uniteApprentissageService->createInstance();
        

        $microCompetences = $this->microCompetenceService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::uniteApprentissage._fields', compact('bulkEdit' ,'itemUniteApprentissage', 'microCompetences'));
        }
        return view('PkgCompetences::uniteApprentissage.create', compact('bulkEdit' ,'itemUniteApprentissage', 'microCompetences'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $uniteApprentissage_ids = $request->input('ids', []);

        if (!is_array($uniteApprentissage_ids) || count($uniteApprentissage_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemUniteApprentissage = $this->uniteApprentissageService->find($uniteApprentissage_ids[0]);
         
 
        $microCompetences = $this->microCompetenceService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemUniteApprentissage = $this->uniteApprentissageService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::uniteApprentissage._fields', compact('bulkEdit', 'uniteApprentissage_ids', 'itemUniteApprentissage', 'microCompetences'));
        }
        return view('PkgCompetences::uniteApprentissage.bulk-edit', compact('bulkEdit', 'uniteApprentissage_ids', 'itemUniteApprentissage', 'microCompetences'));
    }
    /**
     */
    public function store(UniteApprentissageRequest $request) {
        $validatedData = $request->validated();
        $uniteApprentissage = $this->uniteApprentissageService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' => __('PkgCompetences::uniteApprentissage.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $uniteApprentissage->id]
            );
        }

        return redirect()->route('uniteApprentissages.edit',['uniteApprentissage' => $uniteApprentissage->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' => __('PkgCompetences::uniteApprentissage.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('uniteApprentissage.show_' . $id);

        $itemUniteApprentissage = $this->uniteApprentissageService->edit($id);


        $this->viewState->set('scope.alignementUa.unite_apprentissage_id', $id);
        

        $alignementUaService =  new AlignementUaService();
        $alignementUas_view_data = $alignementUaService->prepareDataForIndexView();
        extract($alignementUas_view_data);

        $this->viewState->set('scope.chapitre.unite_apprentissage_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.critereEvaluation.unite_apprentissage_id', $id);
        

        $critereEvaluationService =  new CritereEvaluationService();
        $critereEvaluations_view_data = $critereEvaluationService->prepareDataForIndexView();
        extract($critereEvaluations_view_data);

        $this->viewState->set('scope.realisationUa.unite_apprentissage_id', $id);
        

        $realisationUaService =  new RealisationUaService();
        $realisationUas_view_data = $realisationUaService->prepareDataForIndexView();
        extract($realisationUas_view_data);

        $this->viewState->set('scope.mobilisationUa.unite_apprentissage_id', $id);
        

        $mobilisationUaService =  new MobilisationUaService();
        $mobilisationUas_view_data = $mobilisationUaService->prepareDataForIndexView();
        extract($mobilisationUas_view_data);

        if (request()->ajax()) {
            return view('PkgCompetences::uniteApprentissage._show', array_merge(compact('itemUniteApprentissage'),$alignementUa_compact_value, $chapitre_compact_value, $critereEvaluation_compact_value, $realisationUa_compact_value, $mobilisationUa_compact_value));
        }

        return view('PkgCompetences::uniteApprentissage.show', array_merge(compact('itemUniteApprentissage'),$alignementUa_compact_value, $chapitre_compact_value, $critereEvaluation_compact_value, $realisationUa_compact_value, $mobilisationUa_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('uniteApprentissage.edit_' . $id);


        $itemUniteApprentissage = $this->uniteApprentissageService->edit($id);


        $microCompetences = $this->microCompetenceService->all();


        $this->viewState->set('scope.alignementUa.unite_apprentissage_id', $id);
        

        $alignementUaService =  new AlignementUaService();
        $alignementUas_view_data = $alignementUaService->prepareDataForIndexView();
        extract($alignementUas_view_data);

        $this->viewState->set('scope.chapitre.unite_apprentissage_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.critereEvaluation.unite_apprentissage_id', $id);
        

        $critereEvaluationService =  new CritereEvaluationService();
        $critereEvaluations_view_data = $critereEvaluationService->prepareDataForIndexView();
        extract($critereEvaluations_view_data);

        $this->viewState->set('scope.realisationUa.unite_apprentissage_id', $id);
        

        $realisationUaService =  new RealisationUaService();
        $realisationUas_view_data = $realisationUaService->prepareDataForIndexView();
        extract($realisationUas_view_data);

        $this->viewState->set('scope.mobilisationUa.unite_apprentissage_id', $id);
        

        $mobilisationUaService =  new MobilisationUaService();
        $mobilisationUas_view_data = $mobilisationUaService->prepareDataForIndexView();
        extract($mobilisationUas_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::uniteApprentissage._edit', array_merge(compact('bulkEdit' , 'itemUniteApprentissage','microCompetences'),$alignementUa_compact_value, $chapitre_compact_value, $critereEvaluation_compact_value, $realisationUa_compact_value, $mobilisationUa_compact_value));
        }

        return view('PkgCompetences::uniteApprentissage.edit', array_merge(compact('bulkEdit' ,'itemUniteApprentissage','microCompetences'),$alignementUa_compact_value, $chapitre_compact_value, $critereEvaluation_compact_value, $realisationUa_compact_value, $mobilisationUa_compact_value));


    }
    /**
     */
    public function update(UniteApprentissageRequest $request, string $id) {

        $validatedData = $request->validated();
        $uniteApprentissage = $this->uniteApprentissageService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' =>  __('PkgCompetences::uniteApprentissage.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $uniteApprentissage->id]
            );
        }

        return redirect()->route('uniteApprentissages.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' =>  __('PkgCompetences::uniteApprentissage.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $uniteApprentissage_ids = $request->input('uniteApprentissage_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($uniteApprentissage_ids) || count($uniteApprentissage_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($uniteApprentissage_ids as $id) {
            $entity = $this->uniteApprentissageService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->uniteApprentissageService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->uniteApprentissageService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $uniteApprentissage = $this->uniteApprentissageService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' =>  __('PkgCompetences::uniteApprentissage.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('uniteApprentissages.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $uniteApprentissage,
                'modelName' =>  __('PkgCompetences::uniteApprentissage.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $uniteApprentissage_ids = $request->input('ids', []);
        if (!is_array($uniteApprentissage_ids) || count($uniteApprentissage_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($uniteApprentissage_ids as $id) {
            $entity = $this->uniteApprentissageService->find($id);
            $this->uniteApprentissageService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($uniteApprentissage_ids) . ' éléments',
            'modelName' => __('PkgCompetences::uniteApprentissage.plural')
        ]));
    }

    public function export($format)
    {
        $uniteApprentissages_data = $this->uniteApprentissageService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new UniteApprentissageExport($uniteApprentissages_data,'csv'), 'uniteApprentissage_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new UniteApprentissageExport($uniteApprentissages_data,'xlsx'), 'uniteApprentissage_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new UniteApprentissageImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('uniteApprentissages.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('uniteApprentissages.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::uniteApprentissage.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getUniteApprentissages()
    {
        $uniteApprentissages = $this->uniteApprentissageService->all();
        return response()->json($uniteApprentissages);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $uniteApprentissage = $this->uniteApprentissageService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedUniteApprentissage = $this->uniteApprentissageService->dataCalcul($uniteApprentissage);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedUniteApprentissage
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
        $uniteApprentissageRequest = new UniteApprentissageRequest();
        $fullRules = $uniteApprentissageRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:unite_apprentissages,id'];
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