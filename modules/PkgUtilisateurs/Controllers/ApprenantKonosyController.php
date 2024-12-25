<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\ApprenantKonosyRequest;
use Modules\PkgUtilisateurs\Services\ApprenantKonosyService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\ApprenantKonosyExport;
use Modules\PkgUtilisateurs\App\Imports\ApprenantKonosyImport;

class ApprenantKonosyController extends AdminController
{
    protected $apprenantKonosyService;

    public function __construct(ApprenantKonosyService $apprenantKonosyService)
    {
        parent::__construct();
        $this->apprenantKonosyService = $apprenantKonosyService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->apprenantKonosyService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgUtilisateurs::apprenantKonosy._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgUtilisateurs::apprenantKonosy.index', compact('data'));
    }

    public function create()
    {
        $item = $this->apprenantKonosyService->createInstance();
        return view('PkgUtilisateurs::apprenantKonosy.create', compact('item'));
    }

    public function store(ApprenantKonosyRequest $request)
    {
        $validatedData = $request->validated();
        $apprenantKonosy = $this->apprenantKonosyService->create($validatedData);


        return redirect()->route('apprenantKonosies.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $apprenantKonosy,
            'modelName' => __('PkgUtilisateurs::apprenantKonosy.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->apprenantKonosyService->find($id);
        return view('PkgUtilisateurs::apprenantkonosy.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->apprenantKonosyService->find($id);
        return view('PkgUtilisateurs::apprenantKonosy.edit', compact('item'));
    }

    public function update(ApprenantKonosyRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $apprenantkonosy = $this->apprenantKonosyService->update($id, $validatedData);



        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenantkonosy,
                'modelName' =>  __('PkgUtilisateurs::apprenantkonosy.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $apprenantkonosy = $this->apprenantKonosyService->destroy($id);
        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenantkonosy,
                'modelName' =>  __('PkgUtilisateurs::apprenantkonosy.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->apprenantKonosyService->all();
        return Excel::download(new ApprenantKonosyExport($data), 'apprenantKonosy_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ApprenantKonosyImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('apprenantKonosies.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::apprenantkonosy.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getApprenantKonosies()
    {
        $apprenantKonosies = $this->apprenantKonosyService->all();
        return response()->json($apprenantKonosies);
    }
}
