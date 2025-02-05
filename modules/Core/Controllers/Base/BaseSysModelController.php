<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysModelService;
use Modules\Core\Services\SysColorService;
use Modules\Core\Services\SysModuleService;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysModelRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysModelExport;
use Modules\Core\App\Imports\SysModelImport;
use Modules\Core\Services\ContextState;

class BaseSysModelController extends AdminController
{
    protected $sysModelService;
    protected $sysColorService;
    protected $sysModuleService;

    public function __construct(SysModelService $sysModelService, SysColorService $sysColorService, SysModuleService $sysModuleService) {
        parent::__construct();
        $this->sysModelService = $sysModelService;
        $this->sysColorService = $sysColorService;
        $this->sysModuleService = $sysModuleService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $sysModels_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('sysModels_search', '')],
            $request->except(['sysModels_search', 'page', 'sort'])
        );

        // Paginer les sysModels
        $sysModels_data = $this->sysModelService->paginate($sysModels_params);

        // Récupérer les statistiques et les champs filtrables
        $sysModels_stats = $this->sysModelService->getsysModelStats();
        $sysModels_filters = $this->sysModelService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('Core::sysModel._table', compact('sysModels_data', 'sysModels_stats', 'sysModels_filters'))->render();
        }

        return view('Core::sysModel.index', compact('sysModels_data', 'sysModels_stats', 'sysModels_filters'));
    }
    public function create() {
        $itemSysModel = $this->sysModelService->createInstance();
        $sysColors = $this->sysColorService->all();
        $sysModules = $this->sysModuleService->all();


        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('itemSysModel', 'sysColors', 'sysModules'));
        }
        return view('Core::sysModel.create', compact('itemSysModel', 'sysColors', 'sysModules'));
    }
    public function store(SysModelRequest $request) {
        $validatedData = $request->validated();
        $sysModel = $this->sysModelService->create($validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'entity_id' => $sysModel->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $sysModel,
                'modelName' => __('Core::sysModel.singular')])
            ]);
        }

        return redirect()->route('sysModels.edit',['sysModel' => $sysModel->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysModel,
                'modelName' => __('Core::sysModel.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('sys_model_id', $id);
        
        $itemSysModel = $this->sysModelService->find($id);
        $sysColors = $this->sysColorService->all();
        $sysModules = $this->sysModuleService->all();
        $widgetService =  new WidgetService();
        $widgets_data =  $itemSysModel->widgets()->paginate(10);
        $widgets_stats = $widgetService->getwidgetStats();
        $widgets_filters = $widgetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('Core::sysModel._edit', compact('itemSysModel', 'sysColors', 'sysModules', 'widgets_data', 'widgets_stats', 'widgets_filters'));
        }

        return view('Core::sysModel.edit', compact('itemSysModel', 'sysColors', 'sysModules', 'widgets_data', 'widgets_stats', 'widgets_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('sys_model_id', $id);
        
        $itemSysModel = $this->sysModelService->find($id);
        $sysColors = $this->sysColorService->all();
        $sysModules = $this->sysModuleService->all();
        $widgetService =  new WidgetService();
        $widgets_data =  $itemSysModel->widgets()->paginate(10);
        $widgets_stats = $widgetService->getwidgetStats();
        $widgets_filters = $widgetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('Core::sysModel._edit', compact('itemSysModel', 'sysColors', 'sysModules', 'widgets_data', 'widgets_stats', 'widgets_filters'));
        }

        return view('Core::sysModel.edit', compact('itemSysModel', 'sysColors', 'sysModules', 'widgets_data', 'widgets_stats', 'widgets_filters'));

    }
    public function update(SysModelRequest $request, string $id) {

        $validatedData = $request->validated();
        $sysModel = $this->sysModelService->update($id, $validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')])
            ]);
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $sysModel = $this->sysModelService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')])
            ]);
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')
                ])
        );

    }

    public function export()
    {
        $sysModels_data = $this->sysModelService->all();
        return Excel::download(new SysModelExport($sysModels_data), 'sysModel_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SysModelImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysModels.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysModels.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysModel.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysModels()
    {
        $sysModels = $this->sysModelService->all();
        return response()->json($sysModels);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $sysModel = $this->sysModelService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedSysModel = $this->sysModelService->dataCalcul($sysModel);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedSysModel
        ]);
    }
    


}
