<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\AppreciationRequest;
use Modules\PkgCompetences\Services\AppreciationService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\AppreciationExport;
use Modules\PkgCompetences\App\Imports\AppreciationImport;

class AppreciationController extends AdminController
{
    protected $appreciationService;

    public function __construct(AppreciationService $appreciationService)
    {
        parent::__construct();
        $this->appreciationService = $appreciationService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->appreciationService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCompetences::appreciation._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCompetences::appreciation.index', compact('data'));
    }

    public function create()
    {
        $item = $this->appreciationService->createInstance();
        return view('PkgCompetences::appreciation.create', compact('item'));
    }

    public function store(AppreciationRequest $request)
    {
        $validatedData = $request->validated();
        $appreciation = $this->appreciationService->create($validatedData);


        return redirect()->route('appreciations.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $appreciation,
            'modelName' => __('PkgCompetences::appreciation.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->appreciationService->find($id);
        return view('PkgCompetences::appreciation.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->appreciationService->find($id);
        return view('PkgCompetences::appreciation.edit', compact('item'));
    }

    public function update(AppreciationRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $appreciation = $this->appreciationService->update($id, $validatedData);



        return redirect()->route('appreciations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $appreciation,
                'modelName' =>  __('PkgCompetences::appreciation.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $appreciation = $this->appreciationService->destroy($id);
        return redirect()->route('appreciations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $appreciation,
                'modelName' =>  __('PkgCompetences::appreciation.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->appreciationService->all();
        return Excel::download(new AppreciationExport($data), 'appreciation_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new AppreciationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('appreciations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('appreciations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::appreciation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAppreciations()
    {
        $appreciations = $this->appreciationService->all();
        return response()->json($appreciations);
    }
}
