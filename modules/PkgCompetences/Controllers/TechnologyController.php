<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\TechnologyRequest;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Modules\PkgCompetences\Services\CategorieTechnologyService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\TechnologyExport;
use Modules\PkgCompetences\App\Imports\TechnologyImport;

class TechnologyController extends AdminController
{
    protected $technologyService;
    protected $competenceService;
    protected $transfertCompetenceService;
    protected $categorieTechnologyService;

    public function __construct(TechnologyService $technologyService, CompetenceService $competenceService, TransfertCompetenceService $transfertCompetenceService, CategorieTechnologyService $categorieTechnologyService)
    {
        parent::__construct();
        $this->technologyService = $technologyService;
        $this->competenceService = $competenceService;
        $this->transfertCompetenceService = $transfertCompetenceService;
        $this->categorieTechnologyService = $categorieTechnologyService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->technologyService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgCompetences::technology._table', compact('data'))->render();
        }

        return view('PkgCompetences::technology.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemTechnology = $this->technologyService->createInstance();
        $competences = $this->competenceService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();
        $categorieTechnologies = $this->categorieTechnologyService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'transfertCompetences', 'categorieTechnologies'));
        }
        return view('PkgCompetences::technology.create', compact('itemTechnology', 'competences', 'transfertCompetences', 'categorieTechnologies'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(TechnologyRequest $request)
    {
        $validatedData = $request->validated();
        $technology = $this->technologyService->create($validatedData);

        if ($request->has('competences')) {
            $technology->competences()->sync($request->input('competences'));
        }
        if ($request->has('transfertcompetences')) {
            $technology->transfertcompetences()->sync($request->input('transfertcompetences'));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $technology,
                'modelName' => __('PkgCompetences::technology.singular')])
            ]);
        }

        return redirect()->route('technologys.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $technology,
                'modelName' => __('PkgCompetences::technology.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemTechnology = $this->technologyService->find($id);
        $competences = $this->competenceService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();
        $categorieTechnologies = $this->categorieTechnologyService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'transfertCompetences', 'categorieTechnologies'));
        }

        return view('PkgCompetences::technology.show', compact('itemTechnology'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemTechnology = $this->technologyService->find($id);
        $competences = $this->competenceService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();
        $categorieTechnologies = $this->categorieTechnologyService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'transfertCompetences', 'categorieTechnologies'));
        }

        return view('PkgCompetences::technology.edit', compact('itemTechnology', 'competences', 'transfertCompetences', 'categorieTechnologies'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(TechnologyRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $technology = $this->technologyService->update($id, $validatedData);

        $technology->competences()->sync($request->input('competences'));
        $technology->transfertcompetences()->sync($request->input('transfertcompetences'));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')])
            ]);
        }

        return redirect()->route('technologies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $technology = $this->technologyService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')])
            ]);
        }

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
