<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\ModuleService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgCompetences\Services\CompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\ModuleRequest;
use Modules\PkgFormation\Models\Module;
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
        $this->service  =  $moduleService;
        $this->moduleService = $moduleService;
        $this->filiereService = $filiereService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('module.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $modules_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'modules_search',
                $this->viewState->get("filter.module.modules_search")
            )],
            $request->except(['modules_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->moduleService->prepareDataForIndexView($modules_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($module_partialViewName, $module_compact_value)->render();
        }

        return view('PkgFormation::module.index', $module_compact_value);
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
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $module,
                'modelName' => __('PkgFormation::module.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $module->id]
            );
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

        $this->viewState->setContextKey('module.edit_' . $id);


        $itemModule = $this->moduleService->find($id);


        $filieres = $this->filiereService->all();


        $this->viewState->set('scope.competence.module_id', $id);
        

        $competenceService =  new CompetenceService();
        $competences_view_data = $competenceService->prepareDataForIndexView();
        extract($competences_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::module._edit', array_merge(compact('itemModule','filieres'),$competence_compact_value));
        }

        return view('PkgFormation::module.edit', array_merge(compact('itemModule','filieres'),$competence_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('module.edit_' . $id);


        $itemModule = $this->moduleService->find($id);


        $filieres = $this->filiereService->all();


        $this->viewState->set('scope.competence.module_id', $id);
        

        $competenceService =  new CompetenceService();
        $competences_view_data = $competenceService->prepareDataForIndexView();
        extract($competences_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::module._edit', array_merge(compact('itemModule','filieres'),$competence_compact_value));
        }

        return view('PkgFormation::module.edit', array_merge(compact('itemModule','filieres'),$competence_compact_value));

    }
    public function update(ModuleRequest $request, string $id) {

        $validatedData = $request->validated();
        $module = $this->moduleService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $module->id]
            );
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
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('modules.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')
                ])
        );

    }

    public function export($format)
    {
        $modules_data = $this->moduleService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ModuleExport($modules_data,'csv'), 'module_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ModuleExport($modules_data,'xlsx'), 'module_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $module = $this->moduleService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedModule = $this->moduleService->dataCalcul($module);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedModule
        ]);
    }
    

}