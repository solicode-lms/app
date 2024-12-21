<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\TechnologyRequest;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\CompetenceService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\TechnologyExport;
use Modules\PkgCompetences\App\Imports\TechnologyImport;

class TechnologyController extends AdminController
{
    protected $technologyService;
    protected $competenceService;

    public function __construct(TechnologyService $technologyService, CompetenceService $competenceService)
    {
        $this->technologyService = $technologyService;
        $this->competenceService = $competenceService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->technologyService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCompetences::_technology.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCompetences::technology.index', compact('data'));
    }

    public function create()
    {
        $item = $this->technologyService->createInstance();
        $competences = $this->competenceService->all();
        return view('PkgCompetences::technology.create', compact('item', 'competences'));
    }

    public function store(TechnologyRequest $request)
    {
        $validatedData = $request->validated();
        $technology = $this->technologyService->create($validatedData);

        if ($request->has('competences')) {
            $technology->competences()->sync($request->input('competences'));
        }

        return redirect()->route('technologies.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $technology,
            'modelName' => __('PkgCompetences::technology.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->technologyService->find($id);
        return view('PkgCompetences::technology.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->technologyService->find($id);
        $competences = $this->competenceService->all();
        return view('PkgCompetences::technology.edit', compact('item', 'competences'));
    }

    public function update(TechnologyRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $technology = $this->technologyService->update($id, $validatedData);


        if ($request->has('competences')) {
            $technology->competences()->sync($request->input('competences'));
        }

        return redirect()->route('technologies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $technology = $this->technologyService->destroy($id);
        return redirect()->route('technologies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->technologyService->all();
        return Excel::download(new TechnologyExport($data), 'technology_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new TechnologyImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('technologies.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('technologies.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::technology.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTechnologies()
    {
        $technologies = $this->technologyService->all();
        return response()->json($technologies);
    }
}
