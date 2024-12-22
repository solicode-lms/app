<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\FiliereRequest;
use Modules\PkgCompetences\Services\FiliereService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\FiliereExport;
use Modules\PkgCompetences\App\Imports\FiliereImport;

class FiliereController extends AdminController
{
    protected $filiereService;

    public function __construct(FiliereService $filiereService)
    {
        parent::__construct();
        $this->filiereService = $filiereService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->filiereService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCompetences::filiere._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCompetences::filiere.index', compact('data'));
    }

    public function create()
    {
        $item = $this->filiereService->createInstance();
        return view('PkgCompetences::filiere.create', compact('item'));
    }

    public function store(FiliereRequest $request)
    {
        $validatedData = $request->validated();
        $filiere = $this->filiereService->create($validatedData);


        return redirect()->route('filieres.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $filiere,
            'modelName' => __('PkgCompetences::filiere.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->filiereService->find($id);
        return view('PkgCompetences::filiere.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->filiereService->find($id);
        return view('PkgCompetences::filiere.edit', compact('item'));
    }

    public function update(FiliereRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $filiere = $this->filiereService->update($id, $validatedData);



        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgCompetences::filiere.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $filiere = $this->filiereService->destroy($id);
        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgCompetences::filiere.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->filiereService->all();
        return Excel::download(new FiliereExport($data), 'filiere_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new FiliereImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('filieres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('filieres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::filiere.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFilieres()
    {
        $filieres = $this->filiereService->all();
        return response()->json($filieres);
    }
}
