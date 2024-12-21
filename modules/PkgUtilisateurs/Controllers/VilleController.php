<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\VilleRequest;
use Modules\PkgUtilisateurs\Services\VilleService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\VilleExport;
use Modules\PkgUtilisateurs\App\Imports\VilleImport;

class VilleController extends AdminController
{
    protected $villeService;

    public function __construct(VilleService $villeService)
    {
        parent::__construct();
        $this->villeService = $villeService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->villeService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgUtilisateurs::ville._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgUtilisateurs::ville.index', compact('data'));
    }

    public function create()
    {
        $item = $this->villeService->createInstance();
        return view('PkgUtilisateurs::ville.create', compact('item'));
    }

    public function store(VilleRequest $request)
    {
        $validatedData = $request->validated();
        $ville = $this->villeService->create($validatedData);


        return redirect()->route('villes.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $ville,
            'modelName' => __('PkgUtilisateurs::ville.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->villeService->find($id);
        return view('PkgUtilisateurs::ville.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->villeService->find($id);
        return view('PkgUtilisateurs::ville.edit', compact('item'));
    }

    public function update(VilleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $ville = $this->villeService->update($id, $validatedData);



        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgUtilisateurs::ville.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $ville = $this->villeService->destroy($id);
        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgUtilisateurs::ville.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->villeService->all();
        return Excel::download(new VilleExport($data), 'ville_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new VilleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('villes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('villes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::ville.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getVilles()
    {
        $villes = $this->villeService->all();
        return response()->json($villes);
    }
}
