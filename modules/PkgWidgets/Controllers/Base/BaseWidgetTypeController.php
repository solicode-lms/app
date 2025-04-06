<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\WidgetTypeService;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgWidgets\App\Requests\WidgetTypeRequest;
use Modules\PkgWidgets\Models\WidgetType;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\WidgetTypeExport;
use Modules\PkgWidgets\App\Imports\WidgetTypeImport;
use Modules\Core\Services\ContextState;

class BaseWidgetTypeController extends AdminController
{
    protected $widgetTypeService;

    public function __construct(WidgetTypeService $widgetTypeService) {
        parent::__construct();
        $this->service  =  $widgetTypeService;
        $this->widgetTypeService = $widgetTypeService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('widgetType.index');
        
        $viewType = $this->viewState->get('view_type', 'table');
        $viewTypes = $this->getService()->getViewTypes();
        



        // Extraire les paramètres de recherche, page, et filtres
        $widgetTypes_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('widgetTypes_search', $this->viewState->get("filter.widgetType.widgetTypes_search"))],
            $request->except(['widgetTypes_search', 'page', 'sort'])
        );

        // Paginer les widgetTypes
        $widgetTypes_data = $this->widgetTypeService->paginate($widgetTypes_params);

        // Récupérer les statistiques et les champs filtrables
        $widgetTypes_stats = $this->widgetTypeService->getwidgetTypeStats();
        $this->viewState->set('stats.widgetType.stats'  , $widgetTypes_stats);
        $widgetTypes_filters = $this->widgetTypeService->getFieldsFilterable();
        $widgetType_instance =  $this->widgetTypeService->createInstance();
        
        $partialViewName =  $partialViewName = $this->getService()->getPartialViewName($viewType);

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($partialViewName, compact('viewTypes','widgetTypes_data', 'widgetTypes_stats', 'widgetTypes_filters','widgetType_instance'))->render();
        }

        return view('PkgWidgets::widgetType.index', compact('viewTypes','viewType','widgetTypes_data', 'widgetTypes_stats', 'widgetTypes_filters','widgetType_instance'));
    }
    public function create() {


        $itemWidgetType = $this->widgetTypeService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._fields', compact('itemWidgetType'));
        }
        return view('PkgWidgets::widgetType.create', compact('itemWidgetType'));
    }
    public function store(WidgetTypeRequest $request) {
        $validatedData = $request->validated();
        $widgetType = $this->widgetTypeService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $widgetType,
                'modelName' => __('PkgWidgets::widgetType.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $widgetType->id]
            );
        }

        return redirect()->route('widgetTypes.edit',['widgetType' => $widgetType->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widgetType,
                'modelName' => __('PkgWidgets::widgetType.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('widgetType.edit_' . $id);


        $itemWidgetType = $this->widgetTypeService->find($id);


        

        $this->viewState->set('scope.widget.type_id', $id);


        $widgetService =  new WidgetService();
        $widgets_data =  $widgetService->paginate();
        $widgets_stats = $widgetService->getwidgetStats();
        $widgets_filters = $widgetService->getFieldsFilterable();
        $widget_instance =  $widgetService->createInstance();

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._edit', compact('itemWidgetType', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));
        }

        return view('PkgWidgets::widgetType.edit', compact('itemWidgetType', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('widgetType.edit_' . $id);


        $itemWidgetType = $this->widgetTypeService->find($id);




        $this->viewState->set('scope.widget.type_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_data =  $widgetService->paginate();
        $widgets_stats = $widgetService->getwidgetStats();
        $this->viewState->set('stats.widget.stats'  , $widgets_stats);
        $widgets_filters = $widgetService->getFieldsFilterable();
        $widget_instance =  $widgetService->createInstance();

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._edit', compact('itemWidgetType', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));
        }

        return view('PkgWidgets::widgetType.edit', compact('itemWidgetType', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));

    }
    public function update(WidgetTypeRequest $request, string $id) {

        $validatedData = $request->validated();
        $widgetType = $this->widgetTypeService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $widgetType->id]
            );
        }

        return redirect()->route('widgetTypes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $widgetType = $this->widgetTypeService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('widgetTypes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')
                ])
        );

    }

    public function export($format)
    {
        $widgetTypes_data = $this->widgetTypeService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WidgetTypeExport($widgetTypes_data,'csv'), 'widgetType_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WidgetTypeExport($widgetTypes_data,'xlsx'), 'widgetType_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WidgetTypeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('widgetTypes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('widgetTypes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::widgetType.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgetTypes()
    {
        $widgetTypes = $this->widgetTypeService->all();
        return response()->json($widgetTypes);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $widgetType = $this->widgetTypeService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWidgetType = $this->widgetTypeService->dataCalcul($widgetType);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWidgetType
        ]);
    }
    

}