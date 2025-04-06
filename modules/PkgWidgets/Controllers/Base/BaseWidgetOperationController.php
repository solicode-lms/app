<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\WidgetOperationService;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgWidgets\App\Requests\WidgetOperationRequest;
use Modules\PkgWidgets\Models\WidgetOperation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\WidgetOperationExport;
use Modules\PkgWidgets\App\Imports\WidgetOperationImport;
use Modules\Core\Services\ContextState;

class BaseWidgetOperationController extends AdminController
{
    protected $widgetOperationService;

    public function __construct(WidgetOperationService $widgetOperationService) {
        parent::__construct();
        $this->service  =  $widgetOperationService;
        $this->widgetOperationService = $widgetOperationService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('widgetOperation.index');
        
        $viewType = $this->viewState->get('view_type', 'table');
        $viewTypes = $this->getService()->getViewTypes();
        



        // Extraire les paramètres de recherche, page, et filtres
        $widgetOperations_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('widgetOperations_search', $this->viewState->get("filter.widgetOperation.widgetOperations_search"))],
            $request->except(['widgetOperations_search', 'page', 'sort'])
        );

        // Paginer les widgetOperations
        $widgetOperations_data = $this->widgetOperationService->paginate($widgetOperations_params);

        // Récupérer les statistiques et les champs filtrables
        $widgetOperations_stats = $this->widgetOperationService->getwidgetOperationStats();
        $this->viewState->set('stats.widgetOperation.stats'  , $widgetOperations_stats);
        $widgetOperations_filters = $this->widgetOperationService->getFieldsFilterable();
        $widgetOperation_instance =  $this->widgetOperationService->createInstance();
        
        $partialViewName =  $partialViewName = $this->getService()->getPartialViewName($viewType);

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($partialViewName, compact('viewTypes','widgetOperations_data', 'widgetOperations_stats', 'widgetOperations_filters','widgetOperation_instance'))->render();
        }

        return view('PkgWidgets::widgetOperation.index', compact('viewTypes','viewType','widgetOperations_data', 'widgetOperations_stats', 'widgetOperations_filters','widgetOperation_instance'));
    }
    public function create() {


        $itemWidgetOperation = $this->widgetOperationService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._fields', compact('itemWidgetOperation'));
        }
        return view('PkgWidgets::widgetOperation.create', compact('itemWidgetOperation'));
    }
    public function store(WidgetOperationRequest $request) {
        $validatedData = $request->validated();
        $widgetOperation = $this->widgetOperationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' => __('PkgWidgets::widgetOperation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $widgetOperation->id]
            );
        }

        return redirect()->route('widgetOperations.edit',['widgetOperation' => $widgetOperation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' => __('PkgWidgets::widgetOperation.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('widgetOperation.edit_' . $id);


        $itemWidgetOperation = $this->widgetOperationService->find($id);


        

        $this->viewState->set('scope.widget.operation_id', $id);


        $widgetService =  new WidgetService();
        $widgets_data =  $widgetService->paginate();
        $widgets_stats = $widgetService->getwidgetStats();
        $widgets_filters = $widgetService->getFieldsFilterable();
        $widget_instance =  $widgetService->createInstance();

        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._edit', compact('itemWidgetOperation', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));
        }

        return view('PkgWidgets::widgetOperation.edit', compact('itemWidgetOperation', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('widgetOperation.edit_' . $id);


        $itemWidgetOperation = $this->widgetOperationService->find($id);




        $this->viewState->set('scope.widget.operation_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_data =  $widgetService->paginate();
        $widgets_stats = $widgetService->getwidgetStats();
        $this->viewState->set('stats.widget.stats'  , $widgets_stats);
        $widgets_filters = $widgetService->getFieldsFilterable();
        $widget_instance =  $widgetService->createInstance();

        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._edit', compact('itemWidgetOperation', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));
        }

        return view('PkgWidgets::widgetOperation.edit', compact('itemWidgetOperation', 'widgets_data', 'widgets_stats', 'widgets_filters', 'widget_instance'));

    }
    public function update(WidgetOperationRequest $request, string $id) {

        $validatedData = $request->validated();
        $widgetOperation = $this->widgetOperationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $widgetOperation->id]
            );
        }

        return redirect()->route('widgetOperations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $widgetOperation = $this->widgetOperationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('widgetOperations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')
                ])
        );

    }

    public function export($format)
    {
        $widgetOperations_data = $this->widgetOperationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WidgetOperationExport($widgetOperations_data,'csv'), 'widgetOperation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WidgetOperationExport($widgetOperations_data,'xlsx'), 'widgetOperation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WidgetOperationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('widgetOperations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('widgetOperations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::widgetOperation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgetOperations()
    {
        $widgetOperations = $this->widgetOperationService->all();
        return response()->json($widgetOperations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $widgetOperation = $this->widgetOperationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWidgetOperation = $this->widgetOperationService->dataCalcul($widgetOperation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWidgetOperation
        ]);
    }
    

}