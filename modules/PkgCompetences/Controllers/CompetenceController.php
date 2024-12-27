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
        parent::__construct();
        $this->competenceService = $competenceService;
        $this->technologyService = $technologyService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('searchValue', ''));
        $data = $this->competenceService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgCompetences::competence._table', compact('data'))->render();
        }

        return view('PkgCompetences::competence.index', compact('data'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemCompetence = $this->competenceService->createInstance();
        $technologies = $this->technologyService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('itemCompetence', 'technologies'));
        }
        return view('PkgCompetences::competence.create', compact('itemCompetence', 'technologies'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(CompetenceRequest $request)
    {
        $validatedData = $request->validated();
        $competence = $this->competenceService->create($validatedData);

        if ($request->has('technologies')) {
            $competence->technologies()->sync($request->input('technologies'));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')])
            ]);
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemCompetence = $this->competenceService->find($id);

        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('itemCompetence'));
        }

        return view('PkgCompetences::competence.show', compact('itemCompetence'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemCompetence = $this->competenceService->find($id);
        $technologies = $this->technologyService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('itemCompetence', 'technologies'));
        }

        return view('PkgCompetences::competence.edit', compact('itemCompetence', 'technologies'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(CompetenceRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $competence = $this->competenceService->update($id, $validatedData);

        if ($request->has('technologies')) {
            $competence->technologies()->sync($request->input('technologies'));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')])
            ]);
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $competence = $this->competenceService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')])
            ]);
        }

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
