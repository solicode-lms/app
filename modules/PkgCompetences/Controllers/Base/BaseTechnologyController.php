<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgAutoformation\Services\FormationService;
use Modules\PkgCompetences\Services\CategoryTechnologyService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\TechnologyRequest;
use Modules\PkgCompetences\Models\Technology;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\TechnologyExport;
use Modules\PkgCompetences\App\Imports\TechnologyImport;
use Modules\Core\Services\ContextState;

class BaseTechnologyController extends AdminController
{
    protected $technologyService;
    protected $competenceService;
    protected $formationService;
    protected $categoryTechnologyService;
    protected $transfertCompetenceService;

    public function __construct(TechnologyService $technologyService, CompetenceService $competenceService, FormationService $formationService, CategoryTechnologyService $categoryTechnologyService, TransfertCompetenceService $transfertCompetenceService) {
        parent::__construct();
        $this->service  =  $technologyService;
        $this->technologyService = $technologyService;
        $this->competenceService = $competenceService;
        $this->formationService = $formationService;
        $this->categoryTechnologyService = $categoryTechnologyService;
        $this->transfertCompetenceService = $transfertCompetenceService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('technology.index');



        // Extraire les paramètres de recherche, page, et filtres
        $technologies_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('technologies_search', $this->viewState->get("filter.technology.technologies_search"))],
            $request->except(['technologies_search', 'page', 'sort'])
        );

        // Paginer les technologies
        $technologies_data = $this->technologyService->paginate($technologies_params);

        // Récupérer les statistiques et les champs filtrables
        $technologies_stats = $this->technologyService->gettechnologyStats();
        $this->viewState->set('stats.technology.stats'  , $technologies_stats);
        $technologies_filters = $this->technologyService->getFieldsFilterable();
        $technology_instance =  $this->technologyService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCompetences::technology._table', compact('technologies_data', 'technologies_stats', 'technologies_filters','technology_instance'))->render();
        }

        return view('PkgCompetences::technology.index', compact('technologies_data', 'technologies_stats', 'technologies_filters','technology_instance'));
    }
    public function create() {


        $itemTechnology = $this->technologyService->createInstance();
        

        $categoryTechnologies = $this->categoryTechnologyService->all();
        $competences = $this->competenceService->all();
        $formations = $this->formationService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));
        }
        return view('PkgCompetences::technology.create', compact('itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));
    }
    public function store(TechnologyRequest $request) {
        $validatedData = $request->validated();
        $technology = $this->technologyService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $technology,
                'modelName' => __('PkgCompetences::technology.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $technology->id]
            );
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

        $this->viewState->setContextKey('technology.edit_' . $id);


        $itemTechnology = $this->technologyService->find($id);


        $categoryTechnologies = $this->categoryTechnologyService->all();
        $competences = $this->competenceService->all();
        $formations = $this->formationService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));
        }

        return view('PkgCompetences::technology.edit', compact('itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('technology.edit_' . $id);


        $itemTechnology = $this->technologyService->find($id);


        $categoryTechnologies = $this->categoryTechnologyService->all();
        $competences = $this->competenceService->all();
        $formations = $this->formationService->all();
        $transfertCompetences = $this->transfertCompetenceService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::technology._fields', compact('itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));
        }

        return view('PkgCompetences::technology.edit', compact('itemTechnology', 'competences', 'formations', 'transfertCompetences', 'categoryTechnologies'));

    }
    public function update(TechnologyRequest $request, string $id) {

        $validatedData = $request->validated();
        $technology = $this->technologyService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $technology->id]
            );
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
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('technologies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $technology,
                'modelName' =>  __('PkgCompetences::technology.singular')
                ])
        );

    }

    public function export($format)
    {
        $technologies_data = $this->technologyService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new TechnologyExport($technologies_data,'csv'), 'technology_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new TechnologyExport($technologies_data,'xlsx'), 'technology_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $technology = $this->technologyService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedTechnology = $this->technologyService->dataCalcul($technology);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedTechnology
        ]);
    }
    

}
