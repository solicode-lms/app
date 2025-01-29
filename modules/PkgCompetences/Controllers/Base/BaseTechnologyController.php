<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCompetences\Services\CategoryTechnologyService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\TechnologyRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\TechnologyExport;
use Modules\PkgCompetences\App\Imports\TechnologyImport;
use Modules\Core\Services\ContextState;

class BaseTechnologyController extends AdminController
{
    protected $technologyService;
    protected $competenceService;
    protected $categoryTechnologyService;
    protected $transfertCompetenceService;

    public function __construct(TechnologyService $technologyService, CompetenceService $competenceService, CategoryTechnologyService $categoryTechnologyService, TransfertCompetenceService $transfertCompetenceService) {
        parent::__construct();
        $this->technologyService = $technologyService;
        $this->competenceService = $competenceService;
        $this->categoryTechnologyService = $categoryTechnologyService;
        $this->transfertCompetenceService = $transfertCompetenceService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $technologies_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('technologies_search', '')],
            $request->except(['technologies_search', 'page', 'sort'])
        );

        // Paginer les technologies
        $technologies_data = $this->technologyService->paginate($technologies_params);

        // Récupérer les statistiques et les champs filtrables
        $technologies_stats = $this->technologyService->gettechnologyStats();
        $technologies_filters = $this->technologyService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCompetences::technology._table', compact('technologies_data', 'technologies_stats', 'technologies_filters'))->render();
        }

        return view('PkgCompetences::technology.index', compact('technologies_data', 'technologies_stats', 'technologies_filters'));
    }
    public function create() {
        $itemTechnology = $this->technologyService->createInstance();
        $competences = $this->competenceService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();
        $categoryTechnologies = $this->categoryTechnologyService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));
        }
        return view('PkgCompetences::technology.create', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));
    }
    public function store(TechnologyRequest $request) {
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
    public function show(string $id) {
        $itemTechnology = $this->technologyService->find($id);
        $competences = $this->competenceService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();
        $categoryTechnologies = $this->categoryTechnologyService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));
        }

        return view('PkgCompetences::technology.show', compact('itemTechnology'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('technology_id', $id);
        
        $itemTechnology = $this->technologyService->find($id);
        $competences = $this->competenceService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();
        $categoryTechnologies = $this->categoryTechnologyService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));
        }

        return view('PkgCompetences::technology.edit', compact('itemTechnology', 'competences', 'transfertCompetences', 'categoryTechnologies'));

    }
    public function update(TechnologyRequest $request, string $id) {

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
    public function destroy(Request $request, string $id) {

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
