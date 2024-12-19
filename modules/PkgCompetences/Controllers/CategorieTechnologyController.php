<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\CategorieTechnologyRequest;
use Modules\PkgCompetences\Services\CategorieTechnologyService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\CategorieTechnologyExport;
use Modules\PkgCompetences\App\Imports\CategorieTechnologyImport;

class CategorieTechnologyController extends AdminController
{
    protected $categorieTechnologyService;

    public function __construct(CategorieTechnologyService $categorieTechnologyService)
    {
        $this->categorieTechnologyService = $categorieTechnologyService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->categorieTechnologyService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCompetences::categorieTechnology.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCompetences::categorieTechnology.index', compact('data'));
    }

    public function create()
    {
        $item = $this->categorieTechnologyService->createInstance();
        return view('PkgCompetences::categorieTechnology.create', compact('item'));
    }

    public function store(CategorieTechnologyRequest $request)
    {
        $validatedData = $request->validated();
        $categorieTechnology = $this->categorieTechnologyService->create($validatedData);


        return redirect()->route('categorieTechnologies.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $categorieTechnology,
            'modelName' => __('PkgCompetences::categorieTechnology.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->categorieTechnologyService->find($id);
        return view('PkgCompetences::categorietechnology.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->categorieTechnologyService->find($id);
        return view('PkgCompetences::categorieTechnology.edit', compact('item'));
    }

    public function update(CategorieTechnologyRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $categorietechnology = $this->categorieTechnologyService->update($id, $validatedData);



        return redirect()->route('categorietechnologies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $categorietechnology,
                'modelName' =>  __('PkgCompetences::categorietechnology.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $categorietechnology = $this->categorieTechnologyService->destroy($id);
        return redirect()->route('categorietechnologies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $categorietechnology,
                'modelName' =>  __('PkgCompetences::categorietechnology.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->categorieTechnologyService->all();
        return Excel::download(new CategorieTechnologyExport($data), 'categorieTechnology_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CategorieTechnologyImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('categorieTechnologies.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('categorieTechnologies.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::categorietechnology.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCategorieTechnologies()
    {
        $categorieTechnologies = $this->categorieTechnologyService->all();
        return response()->json($categorieTechnologies);
    }
}
