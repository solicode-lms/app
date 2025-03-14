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

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('categoryTechnology.index');



        // Extraire les paramètres de recherche, page, et filtres
        $categoryTechnologies_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('categoryTechnologies_search', $this->viewState->get("filter.categoryTechnology.categoryTechnologies_search"))],
            $request->except(['categoryTechnologies_search', 'page', 'sort'])
        );

        // Paginer les categoryTechnologies
        $categoryTechnologies_data = $this->categoryTechnologyService->paginate($categoryTechnologies_params);

        // Récupérer les statistiques et les champs filtrables
        $categoryTechnologies_stats = $this->categoryTechnologyService->getcategoryTechnologyStats();
        $this->viewState->set('stats.categoryTechnology.stats'  , $categoryTechnologies_stats);
        $categoryTechnologies_filters = $this->categoryTechnologyService->getFieldsFilterable();
        $categoryTechnology_instance =  $this->categoryTechnologyService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCompetences::categoryTechnology._table', compact('categoryTechnologies_data', 'categoryTechnologies_stats', 'categoryTechnologies_filters','categoryTechnology_instance'))->render();
        }

        return view('PkgCompetences::categoryTechnology.index', compact('categoryTechnologies_data', 'categoryTechnologies_stats', 'categoryTechnologies_filters','categoryTechnology_instance'));
    }
    public function create() {


        $itemCategoryTechnology = $this->categoryTechnologyService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgCompetences::categoryTechnology._fields', compact('itemCategoryTechnology'));
        }
        return view('PkgCompetences::categoryTechnology.create', compact('itemCategoryTechnology'));
    }
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
    public function show(string $id) {

        $this->viewState->setContextKey('categoryTechnology.edit_' . $id);


        $itemCategoryTechnology = $this->categoryTechnologyService->find($id);




        $this->viewState->set('scope.technology.category_technology_id', $id);


        $technologyService =  new TechnologyService();
        $technologies_data =  $technologyService->paginate();
        $technologies_stats = $technologyService->gettechnologyStats();
        $technologies_filters = $technologyService->getFieldsFilterable();
        $technology_instance =  $technologyService->createInstance();

        if (request()->ajax()) {
            return view('PkgCompetences::categoryTechnology._edit', compact('itemCategoryTechnology', 'technologies_data', 'technologies_stats', 'technologies_filters', 'technology_instance'));
        }

        return view('PkgCompetences::categoryTechnology.edit', compact('itemCategoryTechnology', 'technologies_data', 'technologies_stats', 'technologies_filters', 'technology_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('categoryTechnology.edit_' . $id);


        $itemCategoryTechnology = $this->categoryTechnologyService->find($id);




        $this->viewState->set('scope.technology.category_technology_id', $id);
        

        $technologyService =  new TechnologyService();
        $technologies_data =  $technologyService->paginate();
        $technologies_stats = $technologyService->gettechnologyStats();
        $this->viewState->set('stats.technology.stats'  , $technologies_stats);
        $technologies_filters = $technologyService->getFieldsFilterable();
        $technology_instance =  $technologyService->createInstance();

        if (request()->ajax()) {
            return view('PkgCompetences::categoryTechnology._edit', compact('itemCategoryTechnology', 'technologies_data', 'technologies_stats', 'technologies_filters', 'technology_instance'));
        }

        return view('PkgCompetences::categoryTechnology.edit', compact('itemCategoryTechnology', 'technologies_data', 'technologies_stats', 'technologies_filters', 'technology_instance'));

    }
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
    

}
