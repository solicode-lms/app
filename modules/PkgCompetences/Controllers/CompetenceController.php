<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\CompetenceRequest;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCompetences\Services\TechnologyService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\CompetenceExport;
use Modules\PkgCompetences\App\Imports\CompetenceImport;

class CompetenceController extends AdminController
{
    protected $competenceService;
    protected $technologyService;

    public function __construct(CompetenceService $competenceService, TechnologyService $technologyService)
    {
        $this->competenceService = $competenceService;
        $this->technologyService = $technologyService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->competenceService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCompetences::_competence.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCompetences::competence.index', compact('data'));
    }

    public function create()
    {
        $item = $this->competenceService->createInstance();
        $technologies = $this->technologyService->all();
        return view('PkgCompetences::competence.create', compact('item', 'technologies'));
    }

    public function store(CompetenceRequest $request)
    {
        $validatedData = $request->validated();
        $competence = $this->competenceService->create($validatedData);

        if ($request->has('technologies')) {
            $competence->technologies()->sync($request->input('technologies'));
        }

        return redirect()->route('competences.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $competence,
            'modelName' => __('PkgCompetences::competence.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->competenceService->find($id);
        return view('PkgCompetences::competence.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->competenceService->find($id);
        $technologies = $this->technologyService->all();
        return view('PkgCompetences::competence.edit', compact('item', 'technologies'));
    }

    public function update(CompetenceRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $competence = $this->competenceService->update($id, $validatedData);


        if ($request->has('technologies')) {
            $competence->technologies()->sync($request->input('technologies'));
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $competence = $this->competenceService->destroy($id);
        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->competenceService->all();
        return Excel::download(new CompetenceExport($data), 'competence_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('competences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('competences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::competence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCompetences()
    {
        $competences = $this->competenceService->all();
        return response()->json($competences);
    }
}
