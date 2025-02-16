<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgFormation\Services\ModuleService;
use Modules\PkgCompetences\Services\NiveauCompetenceService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\CompetenceRequest;
use Modules\PkgCompetences\Models\Competence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\CompetenceExport;
use Modules\PkgCompetences\App\Imports\CompetenceImport;
use Modules\Core\Services\ContextState;

class BaseCompetenceController extends AdminController
{
    protected $competenceService;
    protected $technologyService;
    protected $moduleService;

    public function __construct(CompetenceService $competenceService, TechnologyService $technologyService, ModuleService $moduleService) {
        parent::__construct();
        $this->competenceService = $competenceService;
        $this->technologyService = $technologyService;
        $this->moduleService = $moduleService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('competence.index');

        // Extraire les paramètres de recherche, page, et filtres
        $competences_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('competences_search', $this->viewState->get("filter.competence.competences_search"))],
            $request->except(['competences_search', 'page', 'sort'])
        );

        // Paginer les competences
        $competences_data = $this->competenceService->paginate($competences_params);

        // Récupérer les statistiques et les champs filtrables
        $competences_stats = $this->competenceService->getcompetenceStats();
        $competences_filters = $this->competenceService->getFieldsFilterable();
        $competence_instance =  $this->competenceService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCompetences::competence._table', compact('competences_data', 'competences_stats', 'competences_filters','competence_instance'))->render();
        }

        return view('PkgCompetences::competence.index', compact('competences_data', 'competences_stats', 'competences_filters','competence_instance'));
    }
    public function create() {
        $itemCompetence = $this->competenceService->createInstance();
        $technologies = $this->technologyService->all();
        $modules = $this->moduleService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('itemCompetence', 'technologies', 'modules'));
        }
        return view('PkgCompetences::competence.create', compact('itemCompetence', 'technologies', 'modules'));
    }
    public function store(CompetenceRequest $request) {
        $validatedData = $request->validated();
        $competence = $this->competenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $competence->id]
            );
        }

        return redirect()->route('competences.edit',['competence' => $competence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('competence.edit_' . $id);

        $itemCompetence = $this->competenceService->find($id);
  
        $technologies = $this->technologyService->all();
        $modules = $this->moduleService->all();

        $this->viewState->set('scope.niveauCompetence.competence_id', $id);
        $niveauCompetenceService =  new NiveauCompetenceService();
        $niveauCompetences_data =  $itemCompetence->niveauCompetences()->paginate(10);
        $niveauCompetences_stats = $niveauCompetenceService->getniveauCompetenceStats();
        $niveauCompetences_filters = $niveauCompetenceService->getFieldsFilterable();
        $niveauCompetence_instance =  $niveauCompetenceService->createInstance();

        if (request()->ajax()) {
            return view('PkgCompetences::competence._edit', compact('itemCompetence', 'technologies', 'modules', 'niveauCompetences_data', 'niveauCompetences_stats', 'niveauCompetences_filters', 'niveauCompetence_instance'));
        }

        return view('PkgCompetences::competence.edit', compact('itemCompetence', 'technologies', 'modules', 'niveauCompetences_data', 'niveauCompetences_stats', 'niveauCompetences_filters', 'niveauCompetence_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('competence.edit_' . $id);

        $itemCompetence = $this->competenceService->find($id);

        $technologies = $this->technologyService->all();
        $modules = $this->moduleService->all();

        $this->viewState->set('scope.niveauCompetence.competence_id', $id);
        $niveauCompetenceService =  new NiveauCompetenceService();
        $niveauCompetences_data =  $itemCompetence->niveauCompetences()->paginate(10);
        $niveauCompetences_stats = $niveauCompetenceService->getniveauCompetenceStats();
        $niveauCompetences_filters = $niveauCompetenceService->getFieldsFilterable();
        $niveauCompetence_instance =  $niveauCompetenceService->createInstance();

        if (request()->ajax()) {
            return view('PkgCompetences::competence._edit', compact('itemCompetence', 'technologies', 'modules', 'niveauCompetences_data', 'niveauCompetences_stats', 'niveauCompetences_filters', 'niveauCompetence_instance'));
        }

        return view('PkgCompetences::competence.edit', compact('itemCompetence', 'technologies', 'modules', 'niveauCompetences_data', 'niveauCompetences_stats', 'niveauCompetences_filters', 'niveauCompetence_instance'));

    }
    public function update(CompetenceRequest $request, string $id) {

        $validatedData = $request->validated();
        $competence = $this->competenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $competence->id]
            );
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $competence = $this->competenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );

    }

    public function export($format)
    {
        $competences_data = $this->competenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new CompetenceExport($competences_data,'csv'), 'competence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new CompetenceExport($competences_data,'xlsx'), 'competence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $competence = $this->competenceService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedCompetence = $this->competenceService->dataCalcul($competence);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedCompetence
        ]);
    }
    

}
