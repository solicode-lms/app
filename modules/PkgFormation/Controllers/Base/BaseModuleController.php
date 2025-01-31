<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\ModuleService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgCompetences\Services\CompetenceService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgFormation\App\Requests\ModuleRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgFormation\App\Exports\ModuleExport;
use Modules\PkgFormation\App\Imports\ModuleImport;
use Modules\Core\Services\ContextState;

class BaseModuleController extends AdminController
{
    protected $moduleService;
    protected $filiereService;

    public function __construct(ModuleService $moduleService, FiliereService $filiereService) {
        parent::__construct();
        $this->moduleService = $moduleService;
        $this->filiereService = $filiereService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $modules_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('modules_search', '')],
            $request->except(['modules_search', 'page', 'sort'])
        );

        // Paginer les modules
        $modules_data = $this->moduleService->paginate($modules_params);

        // Récupérer les statistiques et les champs filtrables
        $modules_stats = $this->moduleService->getmoduleStats();
        $modules_filters = $this->moduleService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgFormation::module._table', compact('modules_data', 'modules_stats', 'modules_filters'))->render();
        }

        return view('PkgFormation::module.index', compact('modules_data', 'modules_stats', 'modules_filters'));
    }
    public function create() {
        $itemModule = $this->moduleService->createInstance();
        $filieres = $this->filiereService->all();


        if (request()->ajax()) {
            return view('PkgFormation::module._fields', compact('itemModule', 'filieres'));
        }
        return view('PkgFormation::module.create', compact('itemModule', 'filieres'));
    }
    public function store(ModuleRequest $request) {
        $validatedData = $request->validated();
        $module = $this->moduleService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'module_id' => $module->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $module,
                'modelName' => __('PkgFormation::module.singular')])
            ]);
        }

        return redirect()->route('modules.edit',['module' => $module->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $module,
                'modelName' => __('PkgFormation::module.singular')
            ])
        );
    }
    public function show(string $id) {
        $itemModule = $this->moduleService->find($id);
        $filieres = $this->filiereService->all();


        if (request()->ajax()) {
            return view('PkgFormation::module._fields', compact('itemModule', 'filieres'));
        }

        return view('PkgFormation::module.show', compact('itemModule'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('module_id', $id);
        
        $itemModule = $this->moduleService->find($id);
        $filieres = $this->filiereService->all();
        $competenceService =  new CompetenceService();
        $competences_data =  $itemModule->competences()->paginate(10);
        $competences_stats = $competenceService->getcompetenceStats();
        $competences_filters = $competenceService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgFormation::module._fields', compact('itemModule', 'filieres', 'competences_data', 'competences_stats', 'competences_filters'));
        }

        return view('PkgFormation::module.edit', compact('itemModule', 'filieres', 'competences_data', 'competences_stats', 'competences_filters'));

    }
    public function update(ModuleRequest $request, string $id) {

        $validatedData = $request->validated();
        $module = $this->moduleService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')])
            ]);
        }

        return redirect()->route('modules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $module = $this->moduleService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')])
            ]);
        }

        return redirect()->route('modules.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')
                ])
        );

    }

    public function export()
    {
        $modules_data = $this->moduleService->all();
        return Excel::download(new ModuleExport($modules_data), 'module_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ModuleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('modules.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('modules.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::module.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getModules()
    {
        $modules = $this->moduleService->all();
        return response()->json($modules);
    }

}
