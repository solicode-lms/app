<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\CategoryTechnologyService;
use Modules\PkgCompetences\Services\TechnologyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\CategoryTechnologyRequest;
use Modules\PkgCompetences\Models\CategoryTechnology;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\CategoryTechnologyExport;
use Modules\PkgCompetences\App\Imports\CategoryTechnologyImport;
use Modules\Core\Services\ContextState;

class BaseCategoryTechnologyController extends AdminController
{
    protected $categoryTechnologyService;

    public function __construct(CategoryTechnologyService $categoryTechnologyService) {
        parent::__construct();
        $this->service  =  $categoryTechnologyService;
        $this->categoryTechnologyService = $categoryTechnologyService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('categoryTechnology.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('categoryTechnology');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $categoryTechnologies_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'categoryTechnologies_search',
                $this->viewState->get("filter.categoryTechnology.categoryTechnologies_search")
            )],
            $request->except(['categoryTechnologies_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->categoryTechnologyService->prepareDataForIndexView($categoryTechnologies_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::categoryTechnology._index', $categoryTechnology_compact_value)->render();
            }else{
                return view($categoryTechnology_partialViewName, $categoryTechnology_compact_value)->render();
            }
        }

        return view('PkgCompetences::categoryTechnology.index', $categoryTechnology_compact_value);
    }
    /**
     */
    public function create() {


        $itemCategoryTechnology = $this->categoryTechnologyService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgCompetences::categoryTechnology._fields', compact('itemCategoryTechnology'));
        }
        return view('PkgCompetences::categoryTechnology.create', compact('itemCategoryTechnology'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $categoryTechnology_ids = $request->input('ids', []);

        if (!is_array($categoryTechnology_ids) || count($categoryTechnology_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemCategoryTechnology = $this->categoryTechnologyService->find($categoryTechnology_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemCategoryTechnology = $this->categoryTechnologyService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::categoryTechnology._fields', compact('bulkEdit', 'categoryTechnology_ids', 'itemCategoryTechnology'));
        }
        return view('PkgCompetences::categoryTechnology.bulk-edit', compact('bulkEdit', 'categoryTechnology_ids', 'itemCategoryTechnology'));
    }
    /**
     */
    public function store(CategoryTechnologyRequest $request) {
        $validatedData = $request->validated();
        $categoryTechnology = $this->categoryTechnologyService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' => __('PkgCompetences::categoryTechnology.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $categoryTechnology->id]
            );
        }

        return redirect()->route('categoryTechnologies.edit',['categoryTechnology' => $categoryTechnology->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' => __('PkgCompetences::categoryTechnology.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('categoryTechnology.show_' . $id);

        $itemCategoryTechnology = $this->categoryTechnologyService->edit($id);


        $this->viewState->set('scope.technology.category_technology_id', $id);
        

        $technologyService =  new TechnologyService();
        $technologies_view_data = $technologyService->prepareDataForIndexView();
        extract($technologies_view_data);

        if (request()->ajax()) {
            return view('PkgCompetences::categoryTechnology._show', array_merge(compact('itemCategoryTechnology'),$technology_compact_value));
        }

        return view('PkgCompetences::categoryTechnology.show', array_merge(compact('itemCategoryTechnology'),$technology_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('categoryTechnology.edit_' . $id);


        $itemCategoryTechnology = $this->categoryTechnologyService->edit($id);




        $this->viewState->set('scope.technology.category_technology_id', $id);
        

        $technologyService =  new TechnologyService();
        $technologies_view_data = $technologyService->prepareDataForIndexView();
        extract($technologies_view_data);

        if (request()->ajax()) {
            return view('PkgCompetences::categoryTechnology._edit', array_merge(compact('itemCategoryTechnology',),$technology_compact_value));
        }

        return view('PkgCompetences::categoryTechnology.edit', array_merge(compact('itemCategoryTechnology',),$technology_compact_value));


    }
    /**
     */
    public function update(CategoryTechnologyRequest $request, string $id) {

        $validatedData = $request->validated();
        $categoryTechnology = $this->categoryTechnologyService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' =>  __('PkgCompetences::categoryTechnology.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $categoryTechnology->id]
            );
        }

        return redirect()->route('categoryTechnologies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' =>  __('PkgCompetences::categoryTechnology.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $categoryTechnology_ids = $request->input('categoryTechnology_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($categoryTechnology_ids) || count($categoryTechnology_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($categoryTechnology_ids as $id) {
            $entity = $this->categoryTechnologyService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->categoryTechnologyService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->categoryTechnologyService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $categoryTechnology = $this->categoryTechnologyService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' =>  __('PkgCompetences::categoryTechnology.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('categoryTechnologies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' =>  __('PkgCompetences::categoryTechnology.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $categoryTechnology_ids = $request->input('ids', []);
        if (!is_array($categoryTechnology_ids) || count($categoryTechnology_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($categoryTechnology_ids as $id) {
            $entity = $this->categoryTechnologyService->find($id);
            $this->categoryTechnologyService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($categoryTechnology_ids) . ' éléments',
            'modelName' => __('PkgCompetences::categoryTechnology.plural')
        ]));
    }

    public function export($format)
    {
        $categoryTechnologies_data = $this->categoryTechnologyService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new CategoryTechnologyExport($categoryTechnologies_data,'csv'), 'categoryTechnology_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new CategoryTechnologyExport($categoryTechnologies_data,'xlsx'), 'categoryTechnology_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new CategoryTechnologyImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('categoryTechnologies.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('categoryTechnologies.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::categoryTechnology.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCategoryTechnologies()
    {
        $categoryTechnologies = $this->categoryTechnologyService->all();
        return response()->json($categoryTechnologies);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $categoryTechnology = $this->categoryTechnologyService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedCategoryTechnology = $this->categoryTechnologyService->dataCalcul($categoryTechnology);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedCategoryTechnology
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
        $categoryTechnologyRequest = new CategoryTechnologyRequest();
        $fullRules = $categoryTechnologyRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:category_technologies,id'];
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