<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgAutoformation\Services\FormationService;
use Modules\PkgCompetences\Services\CategoryTechnologyService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\TechnologyRequest;
use Modules\PkgCompetences\Models\Technology;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\TechnologyExport;
use Modules\PkgCompetences\App\Imports\TechnologyImport;
use Modules\Core\Services\ContextState;

class BaseTechnologyController extends AdminController
{
    protected $technologyService;
    protected $competenceService;
    protected $formationService;
    protected $categoryTechnologyService;
    protected $transfertCompetenceService;

    public function __construct(TechnologyService $technologyService, CompetenceService $competenceService, FormationService $formationService, CategoryTechnologyService $categoryTechnologyService, TransfertCompetenceService $transfertCompetenceService) {
        parent::__construct();
        $this->service  =  $technologyService;
        $this->technologyService = $technologyService;
        $this->competenceService = $competenceService;
        $this->formationService = $formationService;
        $this->categoryTechnologyService = $categoryTechnologyService;
        $this->transfertCompetenceService = $transfertCompetenceService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('technology.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('technology');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $technologies_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'technologies_search',
                $this->viewState->get("filter.technology.technologies_search")
            )],
            $request->except(['technologies_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->technologyService->prepareDataForIndexView($technologies_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::technology._index', $technology_compact_value)->render();
            }else{
                return view($technology_partialViewName, $technology_compact_value)->render();
            }
        }

        return view('PkgCompetences::technology.index', $technology_compact_value);
    }
    /**
     */
    public function create() {


        $itemTechnology = $this->technologyService->createInstance();
        

        $categoryTechnologies = $this->categoryTechnologyService->all();
        $competences = $this->competenceService->all();
        $formations = $this->formationService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('bulkEdit' ,'itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));
        }
        return view('PkgCompetences::technology.create', compact('bulkEdit' ,'itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $technology_ids = $request->input('ids', []);

        if (!is_array($technology_ids) || count($technology_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemTechnology = $this->technologyService->find($technology_ids[0]);
         
 
        $categoryTechnologies = $this->categoryTechnologyService->all();
        $competences = $this->competenceService->all();
        $formations = $this->formationService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemTechnology = $this->technologyService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('bulkEdit', 'technology_ids', 'itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));
        }
        return view('PkgCompetences::technology.bulk-edit', compact('bulkEdit', 'technology_ids', 'itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));
    }
    /**
     */
    public function store(TechnologyRequest $request) {
        $validatedData = $request->validated();
        $technology = $this->technologyService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $technology,
                'modelName' => __('PkgCompetences::technology.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $technology->id]
            );
        }

        return redirect()->route('technologies.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $technology,
                'modelName' => __('PkgCompetences::technology.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('technology.show_' . $id);

        $itemTechnology = $this->technologyService->edit($id);


        if (request()->ajax()) {
            return view('PkgCompetences::technology._show', array_merge(compact('itemTechnology'),));
        }

        return view('PkgCompetences::technology.show', array_merge(compact('itemTechnology'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('technology.edit_' . $id);


        $itemTechnology = $this->technologyService->edit($id);


        $categoryTechnologies = $this->categoryTechnologyService->all();
        $competences = $this->competenceService->all();
        $formations = $this->formationService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', array_merge(compact('bulkEdit' , 'itemTechnology','competences', 'formations', 'transfertCompetences', 'categoryTechnologies'),));
        }

        return view('PkgCompetences::technology.edit', array_merge(compact('bulkEdit' ,'itemTechnology','competences', 'formations', 'transfertCompetences', 'categoryTechnologies'),));


    }
    /**
     */
    public function update(TechnologyRequest $request, string $id) {

        $validatedData = $request->validated();
        $technology = $this->technologyService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $technology->id]
            );
        }

        return redirect()->route('technologies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $technology_ids = $request->input('technology_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($technology_ids) || count($technology_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($technology_ids as $id) {
            $entity = $this->technologyService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->technologyService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->technologyService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $technology = $this->technologyService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('technologies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $technology_ids = $request->input('ids', []);
        if (!is_array($technology_ids) || count($technology_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($technology_ids as $id) {
            $entity = $this->technologyService->find($id);
            $this->technologyService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($technology_ids) . ' éléments',
            'modelName' => __('PkgCompetences::technology.plural')
        ]));
    }

    public function export($format)
    {
        $technologies_data = $this->technologyService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new TechnologyExport($technologies_data,'csv'), 'technology_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new TechnologyExport($technologies_data,'xlsx'), 'technology_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new TechnologyImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('technologies.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('technologies.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::technology.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTechnologies()
    {
        $technologies = $this->technologyService->all();
        return response()->json($technologies);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $technology = $this->technologyService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedTechnology = $this->technologyService->dataCalcul($technology);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedTechnology
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
        $technologyRequest = new TechnologyRequest();
        $fullRules = $technologyRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:technologies,id'];
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