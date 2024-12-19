<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgBlog\App\Requests\CategoryRequest;
use Modules\PkgBlog\Services\CategoryService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgBlog\App\Exports\CategoryExport;
use Modules\PkgBlog\App\Imports\CategoryImport;

class CategoryController extends AdminController
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->categoryService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgBlog::category.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgBlog::category.index', compact('data'));
    }

    public function create()
    {
        $item = $this->categoryService->createInstance();
        return view('PkgBlog::category.create', compact('item'));
    }

    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();
        $category = $this->categoryService->create($validatedData);


        return redirect()->route('categories.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $category,
            'modelName' => __('PkgBlog::category.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->categoryService->find($id);
        return view('PkgBlog::category.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->categoryService->find($id);
        return view('PkgBlog::category.edit', compact('item'));
    }

    public function update(CategoryRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $category = $this->categoryService->update($id, $validatedData);



        return redirect()->route('categories.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $category,
                'modelName' =>  __('PkgBlog::category.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $category = $this->categoryService->destroy($id);
        return redirect()->route('categories.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $category,
                'modelName' =>  __('PkgBlog::category.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->categoryService->all();
        return Excel::download(new CategoryExport($data), 'category_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CategoryImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('categories.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('categories.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgBlog::category.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCategories()
    {
        $categories = $this->categoryService->all();
        return response()->json($categories);
    }
}
