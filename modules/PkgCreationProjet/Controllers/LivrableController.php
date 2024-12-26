<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\LivrableRequest;
use Modules\PkgCreationProjet\Services\LivrableService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\LivrableExport;
use Modules\PkgCreationProjet\App\Imports\LivrableImport;

class LivrableController extends AdminController
{
    protected $livrableService;

    public function __construct(LivrableService $livrableService)
    {
        parent::__construct();
        $this->livrableService = $livrableService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->livrableService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCreationProjet::livrable._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCreationProjet::livrable.index', compact('data'));
    }

    public function create()
    {
        $item = $this->livrableService->createInstance();
        return view('PkgCreationProjet::livrable.create', compact('item'));
    }

    public function store(LivrableRequest $request)
    {
        $validatedData = $request->validated();
        $livrable = $this->livrableService->create($validatedData);


        return redirect()->route('livrables.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $livrable,
            'modelName' => __('PkgCreationProjet::livrable.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->livrableService->find($id);
        return view('PkgCreationProjet::livrable.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->livrableService->find($id);
        return view('PkgCreationProjet::livrable.edit', compact('item'));
    }

    public function update(LivrableRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $livrable = $this->livrableService->update($id, $validatedData);



        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $livrable = $this->livrableService->destroy($id);
        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->livrableService->all();
        return Excel::download(new LivrableExport($data), 'livrable_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new LivrableImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('livrables.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('livrables.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::livrable.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLivrables()
    {
        $livrables = $this->livrableService->all();
        return response()->json($livrables);
    }
}
