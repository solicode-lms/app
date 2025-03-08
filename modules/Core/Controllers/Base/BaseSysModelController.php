<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysModelService;
use Modules\Core\Services\SysColorService;
use Modules\Core\Services\SysModuleService;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\App\Requests\SysModelRequest;
use Modules\Core\Models\SysModel;
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
        $this->service  =  $sysModelService;
        $this->sysModelService = $sysModelService;
        $this->sysColorService = $sysColorService;
        $this->sysModuleService = $sysModuleService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('sysModel.index');



        // Extraire les paramètres de recherche, page, et filtres
        $sysModels_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('sysModels_search', $this->viewState->get("filter.sysModel.sysModels_search"))],
            $request->except(['sysModels_search', 'page', 'sort'])
        );

        // Paginer les sysModels
        $sysModels_data = $this->sysModelService->paginate($sysModels_params);

        // Récupérer les statistiques et les champs filtrables
        $sysModels_stats = $this->sysModelService->getsysModelStats();
        $this->viewState->set('stats.sysModel.stats'  , $sysModels_stats);
        $sysModels_filters = $this->sysModelService->getFieldsFilterable();
        $sysModel_instance =  $this->sysModelService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('Core::sysModel._table', compact('sysModels_data', 'sysModels_stats', 'sysModels_filters','sysModel_instance'))->render();
        }

        return view('Core::sysModel.index', compact('sysModels_data', 'sysModels_stats', 'sysModels_filters','sysModel_instance'));
    }
    public function create() {


        $itemSysModel = $this->sysModelService->createInstance();
        

        $sysModules = $this->sysModuleService->all();
        $sysColors = $this->sysColorService->all();

        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('itemSysModel', 'sysColors', 'sysModules'));
        }
        return view('Core::sysModel.create', compact('itemSysModel', 'sysColors', 'sysModules'));
    }
    public function store(SysModelRequest $request) {
        $validatedData = $request->validated();
        $sysModel = $this->sysModelService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sysModel,
                'modelName' => __('Core::sysModel.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $sysModel->id]
            );
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

        $this->viewState->setContextKey('sysModel.edit_' . $id);


        $itemSysModel = $this->sysModelService->find($id);


        $sysModules = $this->sysModuleService->all();
        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.widget.model_id', $id);


        $widgetService =  new WidgetService();
        $widgets_data =  $widgetService->paginate();
        $widgets_stats = $widgetService->getwidgetStats();
        $widgets_filters = $widgetService->getFieldsFilterable();
        $widget_instance =  $widgetService->createInstance();

        if (request()->ajax()) {
            return view('Core::sysModel._edit', compact('itemSysModel', 'sysColors', 'sysModules', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));
        }

        return view('Core::sysModel.edit', compact('itemSysModel', 'sysColors', 'sysModules', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('sysModel.edit_' . $id);


        $itemSysModel = $this->sysModelService->find($id);


        $sysModules = $this->sysModuleService->all();
        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.widget.model_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_data =  $widgetService->paginate();
        $widgets_stats = $widgetService->getwidgetStats();
        $this->viewState->set('stats.widget.stats'  , $widgets_stats);
        $widgets_filters = $widgetService->getFieldsFilterable();
        $widget_instance =  $widgetService->createInstance();

        if (request()->ajax()) {
            return view('Core::sysModel._edit', compact('itemSysModel', 'sysColors', 'sysModules', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));
        }

        return view('Core::sysModel.edit', compact('itemSysModel', 'sysColors', 'sysModules', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));

    }
    public function update(SysModelRequest $request, string $id) {

        $validatedData = $request->validated();
        $sysModel = $this->sysModelService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $sysModel->id]
            );
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
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')
                ])
        );

    }

    public function export($format)
    {
        $sysModels_data = $this->sysModelService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SysModelExport($sysModels_data,'csv'), 'sysModel_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SysModelExport($sysModels_data,'xlsx'), 'sysModel_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
