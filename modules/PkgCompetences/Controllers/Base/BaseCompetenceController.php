<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgFormation\Services\ModuleService;
use Modules\PkgCompetences\Services\NiveauCompetenceService;
use Modules\PkgAutoformation\Services\FormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\CompetenceRequest;
use Modules\PkgCompetences\Models\Competence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\CompetenceExport;
use Modules\PkgCompetences\App\Imports\CompetenceImport;
use Modules\Core\Services\ContextState;

class BaseCompetenceController extends AdminController
{
    protected $competenceService;
    protected $technologyService;
    protected $moduleService;

    public function __construct(CompetenceService $competenceService, TechnologyService $technologyService, ModuleService $moduleService) {
        parent::__construct();
        $this->service  =  $competenceService;
        $this->competenceService = $competenceService;
        $this->technologyService = $technologyService;
        $this->moduleService = $moduleService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('competence.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('competence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $competences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'competences_search',
                $this->viewState->get("filter.competence.competences_search")
            )],
            $request->except(['competences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->competenceService->prepareDataForIndexView($competences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::competence._index', $competence_compact_value)->render();
            }else{
                return view($competence_partialViewName, $competence_compact_value)->render();
            }
        }

        return view('PkgCompetences::competence.index', $competence_compact_value);
    }
    /**
     */
    public function create() {


        $itemCompetence = $this->competenceService->createInstance();
        

        $modules = $this->moduleService->all();
        $technologies = $this->technologyService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('itemCompetence', 'technologies', 'modules'));
        }
        return view('PkgCompetences::competence.create', compact('itemCompetence', 'technologies', 'modules'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $competence_ids = $request->input('ids', []);

        if (!is_array($competence_ids) || count($competence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemCompetence = $this->competenceService->find($competence_ids[0]);
         
 
        $modules = $this->moduleService->all();
        $technologies = $this->technologyService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemCompetence = $this->competenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('bulkEdit', 'competence_ids', 'itemCompetence', 'technologies', 'modules'));
        }
        return view('PkgCompetences::competence.bulk-edit', compact('bulkEdit', 'competence_ids', 'itemCompetence', 'technologies', 'modules'));
    }
    /**
     */
    public function store(CompetenceRequest $request) {
        $validatedData = $request->validated();
        $competence = $this->competenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $competence->id]
            );
        }

        return redirect()->route('competences.edit',['competence' => $competence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('competence.show_' . $id);

        $itemCompetence = $this->competenceService->edit($id);


        $this->viewState->set('scope.niveauCompetence.competence_id', $id);
        

        $niveauCompetenceService =  new NiveauCompetenceService();
        $niveauCompetences_view_data = $niveauCompetenceService->prepareDataForIndexView();
        extract($niveauCompetences_view_data);

        $this->viewState->set('scope.formation.competence_id', $id);
        

        $formationService =  new FormationService();
        $formations_view_data = $formationService->prepareDataForIndexView();
        extract($formations_view_data);

        if (request()->ajax()) {
            return view('PkgCompetences::competence._show', array_merge(compact('itemCompetence'),$niveauCompetence_compact_value, $formation_compact_value));
        }

        return view('PkgCompetences::competence.show', array_merge(compact('itemCompetence'),$niveauCompetence_compact_value, $formation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('competence.edit_' . $id);


        $itemCompetence = $this->competenceService->edit($id);


        $modules = $this->moduleService->all();
        $technologies = $this->technologyService->all();


        $this->viewState->set('scope.niveauCompetence.competence_id', $id);
        

        $niveauCompetenceService =  new NiveauCompetenceService();
        $niveauCompetences_view_data = $niveauCompetenceService->prepareDataForIndexView();
        extract($niveauCompetences_view_data);

        $this->viewState->set('scope.formation.competence_id', $id);
        

        $formationService =  new FormationService();
        $formations_view_data = $formationService->prepareDataForIndexView();
        extract($formations_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::competence._edit', array_merge(compact('bulkEdit' , 'itemCompetence','technologies', 'modules'),$niveauCompetence_compact_value, $formation_compact_value));
        }

        return view('PkgCompetences::competence.edit', array_merge(compact('itemCompetence','technologies', 'modules'),$niveauCompetence_compact_value, $formation_compact_value));


    }
    /**
     */
    public function update(CompetenceRequest $request, string $id) {

        $validatedData = $request->validated();
        $competence = $this->competenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $competence->id]
            );
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $competence_ids = $request->input('competence_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($competence_ids) || count($competence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($competence_ids as $id) {
            $entity = $this->competenceService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->competenceService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->competenceService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $competence = $this->competenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $competence_ids = $request->input('ids', []);
        if (!is_array($competence_ids) || count($competence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($competence_ids as $id) {
            $entity = $this->competenceService->find($id);
            $this->competenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($competence_ids) . ' éléments',
            'modelName' => __('PkgCompetences::competence.plural')
        ]));
    }

    public function export($format)
    {
        $competences_data = $this->competenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new CompetenceExport($competences_data,'csv'), 'competence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new CompetenceExport($competences_data,'xlsx'), 'competence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new CompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('competences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('competences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::competence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCompetences()
    {
        $competences = $this->competenceService->all();
        return response()->json($competences);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $competence = $this->competenceService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedCompetence = $this->competenceService->dataCalcul($competence);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedCompetence
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
        $competenceRequest = new CompetenceRequest();
        $fullRules = $competenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:competences,id'];
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