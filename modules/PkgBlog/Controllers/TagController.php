<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgBlog\App\Requests\TagRequest;
use Modules\PkgBlog\Services\TagService;
use Modules\PkgBlog\Services\ArticleService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgBlog\App\Exports\TagExport;
use Modules\PkgBlog\App\Imports\TagImport;

class TagController extends AdminController
{
    protected $tagService;
    protected $articleService;

    public function __construct(TagService $tagService, ArticleService $articleService)
    {
        $this->tagService = $tagService;
        $this->articleService = $articleService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->tagService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgBlog::tag.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgBlog::tag.index', compact('data'));
    }

    public function create()
    {
        $item = $this->tagService->createInstance();
        $articles = $this->articleService->all();
        return view('PkgBlog::tag.create', compact('item', 'articles'));
    }

    public function store(TagRequest $request)
    {
        $validatedData = $request->validated();
        $tag = $this->tagService->create($validatedData);

        if ($request->has('articles')) {
            $tag->articles()->sync($request->input('articles'));
        }

        return redirect()->route('tags.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $tag,
            'modelName' => __('PkgBlog::tag.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->tagService->find($id);
        return view('PkgBlog::tag.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->tagService->find($id);
        $articles = $this->articleService->all();
        return view('PkgBlog::tag.edit', compact('item', 'articles'));
    }

    public function update(TagRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $tag = $this->tagService->update($id, $validatedData);


        if ($request->has('articles')) {
            $tag->articles()->sync($request->input('articles'));
        }

        return redirect()->route('tags.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $tag,
                'modelName' =>  __('PkgBlog::tag.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $tag = $this->tagService->destroy($id);
        return redirect()->route('tags.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $tag,
                'modelName' =>  __('PkgBlog::tag.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->tagService->all();
        return Excel::download(new TagExport($data), 'tag_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new TagImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('tags.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('tags.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgBlog::tag.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTags()
    {
        $tags = $this->tagService->all();
        return response()->json($tags);
    }
}
