<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\NatureLivrableRequest;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\NatureLivrableExport;
use Modules\PkgCreationProjet\App\Imports\NatureLivrableImport;

class NatureLivrableController extends AdminController
{
    protected $natureLivrableService;

    public function __construct(NatureLivrableService $natureLivrableService)
    {
        parent::__construct();
        $this->natureLivrableService = $natureLivrableService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->natureLivrableService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCreationProjet::natureLivrable._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCreationProjet::natureLivrable.index', compact('data'));
    }

    public function create()
    {
        $item = $this->natureLivrableService->createInstance();
        return view('PkgCreationProjet::natureLivrable.create', compact('item'));
    }

    public function store(NatureLivrableRequest $request)
    {
        $validatedData = $request->validated();
        $natureLivrable = $this->natureLivrableService->create($validatedData);


        return redirect()->route('natureLivrables.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $natureLivrable,
            'modelName' => __('PkgCreationProjet::natureLivrable.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->natureLivrableService->find($id);
        return view('PkgCreationProjet::naturelivrable.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->natureLivrableService->find($id);
        return view('PkgCreationProjet::natureLivrable.edit', compact('item'));
    }

    public function update(NatureLivrableRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $naturelivrable = $this->natureLivrableService->update($id, $validatedData);



        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $naturelivrable,
                'modelName' =>  __('PkgCreationProjet::naturelivrable.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $naturelivrable = $this->natureLivrableService->destroy($id);
        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $naturelivrable,
                'modelName' =>  __('PkgCreationProjet::naturelivrable.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->natureLivrableService->all();
        return Excel::download(new NatureLivrableExport($data), 'natureLivrable_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new NatureLivrableImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('natureLivrables.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('natureLivrables.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::naturelivrable.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNatureLivrables()
    {
        $natureLivrables = $this->natureLivrableService->all();
        return response()->json($natureLivrables);
    }
}
