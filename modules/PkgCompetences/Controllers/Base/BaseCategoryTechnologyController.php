<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\CategoryTechnologyService;
use Modules\PkgCompetences\Services\TechnologyService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\CategoryTechnologyRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\CategoryTechnologyExport;
use Modules\PkgCompetences\App\Imports\CategoryTechnologyImport;
use Modules\Core\Services\ContextState;

class BaseCategoryTechnologyController extends AdminController
{
    protected $categoryTechnologyService;

    public function __construct(CategoryTechnologyService $categoryTechnologyService) {
        parent::__construct();
        $this->categoryTechnologyService = $categoryTechnologyService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $categoryTechnologies_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('categoryTechnologies_search', '')],
            $request->except(['categoryTechnologies_search', 'page', 'sort'])
        );

        // Paginer les categoryTechnologies
        $categoryTechnologies_data = $this->categoryTechnologyService->paginate($categoryTechnologies_params);

        // Récupérer les statistiques et les champs filtrables
        $categoryTechnologies_stats = $this->categoryTechnologyService->getcategoryTechnologyStats();
        $categoryTechnologies_filters = $this->categoryTechnologyService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCompetences::categoryTechnology._table', compact('categoryTechnologies_data', 'categoryTechnologies_stats', 'categoryTechnologies_filters'))->render();
        }

        return view('PkgCompetences::categoryTechnology.index', compact('categoryTechnologies_data', 'categoryTechnologies_stats', 'categoryTechnologies_filters'));
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
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' => __('PkgCompetences::categoryTechnology.singular')])
            ]);
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
        $itemCategoryTechnology = $this->categoryTechnologyService->find($id);


        if (request()->ajax()) {
            return view('PkgCompetences::categoryTechnology._fields', compact('itemCategoryTechnology'));
        }

        return view('PkgCompetences::categoryTechnology.show', compact('itemCategoryTechnology'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('category_technology_id', $id);
        
        $itemCategoryTechnology = $this->categoryTechnologyService->find($id);
        $technologyService =  new TechnologyService();
        $technologies_data =  $itemCategoryTechnology->technologies()->paginate(10);
        $technologies_stats = $technologyService->gettechnologyStats();
        $technologies_filters = $technologyService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgCompetences::categoryTechnology._fields', compact('itemCategoryTechnology', 'technologies_data', 'technologies_stats', 'technologies_filters'));
        }

        return view('PkgCompetences::categoryTechnology.edit', compact('itemCategoryTechnology', 'technologies_data', 'technologies_stats', 'technologies_filters'));

    }
    public function update(CategoryTechnologyRequest $request, string $id) {

        $validatedData = $request->validated();
        $categoryTechnology = $this->categoryTechnologyService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' =>  __('PkgCompetences::categoryTechnology.singular')])
            ]);
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
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' =>  __('PkgCompetences::categoryTechnology.singular')])
            ]);
        }

        return redirect()->route('categoryTechnologies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $categoryTechnology,
                'modelName' =>  __('PkgCompetences::categoryTechnology.singular')
                ])
        );

    }

    public function export()
    {
        $categoryTechnologies_data = $this->categoryTechnologyService->all();
        return Excel::download(new CategoryTechnologyExport($categoryTechnologies_data), 'categoryTechnology_export.xlsx');
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

}
