<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\NiveauxScolaireRequest;
use Modules\PkgUtilisateurs\Services\NiveauxScolaireService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\NiveauxScolaireExport;
use Modules\PkgUtilisateurs\App\Imports\NiveauxScolaireImport;

class NiveauxScolaireController extends AdminController
{
    protected $niveauxScolaireService;

    public function __construct(NiveauxScolaireService $niveauxScolaireService)
    {
        $this->niveauxScolaireService = $niveauxScolaireService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->niveauxScolaireService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgUtilisateurs::niveauxScolaire.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgUtilisateurs::niveauxScolaire.index', compact('data'));
    }

    public function create()
    {
        $item = $this->niveauxScolaireService->createInstance();
        return view('PkgUtilisateurs::niveauxScolaire.create', compact('item'));
    }

    public function store(NiveauxScolaireRequest $request)
    {
        $validatedData = $request->validated();
        $niveauxScolaire = $this->niveauxScolaireService->create($validatedData);


        return redirect()->route('niveauxScolaires.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $niveauxScolaire,
            'modelName' => __('PkgUtilisateurs::niveauxScolaire.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->niveauxScolaireService->find($id);
        return view('PkgUtilisateurs::niveauxscolaire.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->niveauxScolaireService->find($id);
        return view('PkgUtilisateurs::niveauxScolaire.edit', compact('item'));
    }

    public function update(NiveauxScolaireRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $niveauxscolaire = $this->niveauxScolaireService->update($id, $validatedData);



        return redirect()->route('niveauxscolaires.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveauxscolaire,
                'modelName' =>  __('PkgUtilisateurs::niveauxscolaire.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $niveauxscolaire = $this->niveauxScolaireService->destroy($id);
        return redirect()->route('niveauxscolaires.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauxscolaire,
                'modelName' =>  __('PkgUtilisateurs::niveauxscolaire.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->niveauxScolaireService->all();
        return Excel::download(new NiveauxScolaireExport($data), 'niveauxScolaire_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new NiveauxScolaireImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveauxScolaires.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::niveauxscolaire.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauxScolaires()
    {
        $niveauxScolaires = $this->niveauxScolaireService->all();
        return response()->json($niveauxScolaires);
    }
}
