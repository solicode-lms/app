<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgBlog\App\Requests\ArticleRequest;
use Modules\PkgBlog\Services\ArticleService;
use Modules\PkgBlog\Services\TagService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgBlog\App\Exports\ArticleExport;
use Modules\PkgBlog\App\Imports\ArticleImport;

class ArticleController extends AdminController
{
    protected $articleService;
    protected $tagService;

    public function __construct(ArticleService $articleService, TagService $tagService)
    {
        $this->articleService = $articleService;
        $this->tagService = $tagService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->articleService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgBlog::article.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgBlog::article.index', compact('data'));
    }

    public function create()
    {
        $item = $this->articleService->createInstance();
        $tags = $this->tagService->all();
        return view('PkgBlog::article.create', compact('item', 'tags'));
    }

    public function store(ArticleRequest $request)
    {
        $validatedData = $request->validated();
        $article = $this->articleService->create($validatedData);

        if ($request->has('tags')) {
            $article->tags()->sync($request->input('tags'));
        }

        return redirect()->route('articles.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $article,
            'modelName' => __('PkgBlog::article.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->articleService->find($id);
        return view('PkgBlog::article.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->articleService->find($id);
        $tags = $this->tagService->all();
        return view('PkgBlog::article.edit', compact('item', 'tags'));
    }

    public function update(ArticleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $article = $this->articleService->update($id, $validatedData);


        if ($request->has('tags')) {
            $article->tags()->sync($request->input('tags'));
        }

        return redirect()->route('articles.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $article,
                'modelName' =>  __('PkgBlog::article.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $article = $this->articleService->destroy($id);
        return redirect()->route('articles.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $article,
                'modelName' =>  __('PkgBlog::article.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->articleService->all();
        return Excel::download(new ArticleExport($data), 'article_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ArticleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('articles.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('articles.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgBlog::article.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getArticles()
    {
        $articles = $this->articleService->all();
        return response()->json($articles);
    }
}
