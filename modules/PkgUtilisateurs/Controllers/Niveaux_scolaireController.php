<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\Niveaux_scolaireRequest;
use Modules\PkgUtilisateurs\Services\Niveaux_scolaireService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\Niveaux_scolaireExport;
use Modules\PkgUtilisateurs\App\Imports\Niveaux_scolaireImport;

class Niveaux_scolaireController extends AdminController
{
    protected $niveaux_scolaireService;

    public function __construct(Niveaux_scolaireService $niveaux_scolaireService)
    {
        $this->niveaux_scolaireService = $niveaux_scolaireService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->niveaux_scolaireService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgUtilisateurs::niveaux_scolaire.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgUtilisateurs::niveaux_scolaire.index', compact('data'));
    }

    public function create()
    {
        $item = $this->niveaux_scolaireService->createInstance();
        return view('PkgUtilisateurs::niveaux_scolaire.create', compact('item'));
    }

    public function store(Niveaux_scolaireRequest $request)
    {
        $validatedData = $request->validated();
        $niveaux_scolaire = $this->niveaux_scolaireService->create($validatedData);


        return redirect()->route('niveaux_scolaires.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $niveaux_scolaire,
            'modelName' => __('PkgUtilisateurs::niveaux_scolaire.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->niveaux_scolaireService->find($id);
        return view('PkgUtilisateurs::niveaux_scolaire.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->niveaux_scolaireService->find($id);
        return view('PkgUtilisateurs::niveaux_scolaire.edit', compact('item'));
    }

    public function update(Niveaux_scolaireRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $niveaux_scolaire = $this->niveaux_scolaireService->update($id, $validatedData);



        return redirect()->route('niveaux_scolaires.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveaux_scolaire,
                'modelName' =>  __('PkgUtilisateurs::niveaux_scolaire.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $niveaux_scolaire = $this->niveaux_scolaireService->destroy($id);
        return redirect()->route('niveaux_scolaires.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveaux_scolaire,
                'modelName' =>  __('PkgUtilisateurs::niveaux_scolaire.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->niveaux_scolaireService->all();
        return Excel::download(new Niveaux_scolaireExport($data), 'niveaux_scolaire_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new Niveaux_scolaireImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveaux_scolaires.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveaux_scolaires.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::niveaux_scolaire.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveaux_scolaires()
    {
        $niveaux_scolaires = $this->niveaux_scolaireService->all();
        return response()->json($niveaux_scolaires);
    }
}
