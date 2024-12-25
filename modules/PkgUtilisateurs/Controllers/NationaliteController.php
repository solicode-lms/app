<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\NationaliteRequest;
use Modules\PkgUtilisateurs\Services\NationaliteService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\NationaliteExport;
use Modules\PkgUtilisateurs\App\Imports\NationaliteImport;

class NationaliteController extends AdminController
{
    protected $nationaliteService;

    public function __construct(NationaliteService $nationaliteService)
    {
        parent::__construct();
        $this->nationaliteService = $nationaliteService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->nationaliteService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgUtilisateurs::nationalite._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgUtilisateurs::nationalite.index', compact('data'));
    }

    public function create()
    {
        $item = $this->nationaliteService->createInstance();
        return view('PkgUtilisateurs::nationalite.create', compact('item'));
    }

    public function store(NationaliteRequest $request)
    {
        $validatedData = $request->validated();
        $nationalite = $this->nationaliteService->create($validatedData);


        return redirect()->route('nationalites.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $nationalite,
            'modelName' => __('PkgUtilisateurs::nationalite.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->nationaliteService->find($id);
        return view('PkgUtilisateurs::nationalite.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->nationaliteService->find($id);
        return view('PkgUtilisateurs::nationalite.edit', compact('item'));
    }

    public function update(NationaliteRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $nationalite = $this->nationaliteService->update($id, $validatedData);



        return redirect()->route('nationalites.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgUtilisateurs::nationalite.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $nationalite = $this->nationaliteService->destroy($id);
        return redirect()->route('nationalites.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgUtilisateurs::nationalite.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->nationaliteService->all();
        return Excel::download(new NationaliteExport($data), 'nationalite_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new NationaliteImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('nationalites.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('nationalites.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::nationalite.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNationalites()
    {
        $nationalites = $this->nationaliteService->all();
        return response()->json($nationalites);
    }
}
