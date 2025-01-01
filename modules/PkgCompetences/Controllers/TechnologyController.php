<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\TechnologyRequest;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Modules\PkgCompetences\Services\CategoryTechnologyService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\TechnologyExport;
use Modules\PkgCompetences\App\Imports\TechnologyImport;
use Modules\Core\Services\ContextState;

class TechnologyController extends AdminController
{
    protected $technologyService;
    protected $competenceService;
    protected $transfertCompetenceService;
    protected $categoryTechnologyService;

    public function __construct(TechnologyService $technologyService, CompetenceService $competenceService, TransfertCompetenceService $transfertCompetenceService, CategoryTechnologyService $categoryTechnologyService)
    {
        parent::__construct();
        $this->technologyService = $technologyService;
        $this->competenceService = $competenceService;
        $this->transfertCompetenceService = $transfertCompetenceService;
        $this->categoryTechnologyService = $categoryTechnologyService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $technology_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $technologies_data = $this->technologyService->paginate($technology_searchQuery);

        if ($request->ajax()) {
            return view('PkgCompetences::technology._table', compact('technologies_data'))->render();
        }

        return view('PkgCompetences::technology.index', compact('technologies_data','technology_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemTechnology = $this->technologyService->createInstance();
        $competences = $this->competenceService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();
        $categoryTechnologies = $this->categoryTechnologyService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));
        }
        return view('PkgCompetences::technology.create', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));
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
        if ($request->has('transfertCompetences')) {
            $technology->transfertCompetences()->sync($request->input('transfertCompetences'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $technology,
                'modelName' => __('PkgCompetences::technology.singular')])
            ]);
        }

        return redirect()->route('technologies.index')->with(
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
        $categoryTechnologies = $this->categoryTechnologyService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));
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
        $categoryTechnologies = $this->categoryTechnologyService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('technology_id', $id);


        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));
        }

        return view('PkgCompetences::technology.edit', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(TechnologyRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $technology = $this->technologyService->update($id, $validatedData);


        $technology->competences()->sync($request->input('competences'));
        $technology->transfertCompetences()->sync($request->input('transfertCompetences'));


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
        $technologies_data = $this->technologyService->all();
        return Excel::download(new TechnologyExport($technologies_data), 'technology_export.xlsx');
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
