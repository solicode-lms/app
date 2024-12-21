<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\NiveauCompetenceRequest;
use Modules\PkgCompetences\Services\NiveauCompetenceService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\NiveauCompetenceExport;
use Modules\PkgCompetences\App\Imports\NiveauCompetenceImport;

class NiveauCompetenceController extends AdminController
{
    protected $niveauCompetenceService;

    public function __construct(NiveauCompetenceService $niveauCompetenceService)
    {
        $this->niveauCompetenceService = $niveauCompetenceService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->niveauCompetenceService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCompetences::niveauCompetence._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCompetences::niveauCompetence.index', compact('data'));
    }

    public function create()
    {
        $item = $this->niveauCompetenceService->createInstance();
        return view('PkgCompetences::niveauCompetence.create', compact('item'));
    }

    public function store(NiveauCompetenceRequest $request)
    {
        $validatedData = $request->validated();
        $niveauCompetence = $this->niveauCompetenceService->create($validatedData);


        return redirect()->route('niveauCompetences.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $niveauCompetence,
            'modelName' => __('PkgCompetences::niveauCompetence.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->niveauCompetenceService->find($id);
        return view('PkgCompetences::niveaucompetence.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->niveauCompetenceService->find($id);
        return view('PkgCompetences::niveauCompetence.edit', compact('item'));
    }

    public function update(NiveauCompetenceRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $niveaucompetence = $this->niveauCompetenceService->update($id, $validatedData);



        return redirect()->route('niveaucompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveaucompetence,
                'modelName' =>  __('PkgCompetences::niveaucompetence.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $niveaucompetence = $this->niveauCompetenceService->destroy($id);
        return redirect()->route('niveaucompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveaucompetence,
                'modelName' =>  __('PkgCompetences::niveaucompetence.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->niveauCompetenceService->all();
        return Excel::download(new NiveauCompetenceExport($data), 'niveauCompetence_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new NiveauCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveauCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::niveaucompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauCompetences()
    {
        $niveauCompetences = $this->niveauCompetenceService->all();
        return response()->json($niveauCompetences);
    }
}
