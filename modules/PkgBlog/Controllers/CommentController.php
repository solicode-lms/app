<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgBlog\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgBlog\App\Requests\CommentRequest;
use Modules\PkgBlog\Services\CommentService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgBlog\App\Exports\CommentExport;
use Modules\PkgBlog\App\Imports\CommentImport;

class CommentController extends AdminController
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->commentService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgBlog::comment.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgBlog::comment.index', compact('data'));
    }

    public function create()
    {
        $item = $this->commentService->createInstance();
        return view('PkgBlog::comment.create', compact('item'));
    }

    public function store(CommentRequest $request)
    {
        $validatedData = $request->validated();
        $comment = $this->commentService->create($validatedData);


        return redirect()->route('comments.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $comment,
            'modelName' => __('PkgBlog::comment.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->commentService->find($id);
        return view('PkgBlog::comment.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->commentService->find($id);
        return view('PkgBlog::comment.edit', compact('item'));
    }

    public function update(CommentRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $comment = $this->commentService->update($id, $validatedData);



        return redirect()->route('comments.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $comment,
                'modelName' =>  __('PkgBlog::comment.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $comment = $this->commentService->destroy($id);
        return redirect()->route('comments.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $comment,
                'modelName' =>  __('PkgBlog::comment.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->commentService->all();
        return Excel::download(new CommentExport($data), 'comment_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CommentImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('comments.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('comments.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgBlog::comment.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getComments()
    {
        $comments = $this->commentService->all();
        return response()->json($comments);
    }
}
