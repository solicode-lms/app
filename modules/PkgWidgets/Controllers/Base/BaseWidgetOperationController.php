<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\WidgetOperationService;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgWidgets\App\Requests\WidgetOperationRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\WidgetOperationExport;
use Modules\PkgWidgets\App\Imports\WidgetOperationImport;
use Modules\Core\Services\ContextState;

class BaseWidgetOperationController extends AdminController
{
    protected $widgetOperationService;

    public function __construct(WidgetOperationService $widgetOperationService) {
        parent::__construct();
        $this->widgetOperationService = $widgetOperationService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $widgetOperations_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('widgetOperations_search', '')],
            $request->except(['widgetOperations_search', 'page', 'sort'])
        );

        // Paginer les widgetOperations
        $widgetOperations_data = $this->widgetOperationService->paginate($widgetOperations_params);

        // Récupérer les statistiques et les champs filtrables
        $widgetOperations_stats = $this->widgetOperationService->getwidgetOperationStats();
        $widgetOperations_filters = $this->widgetOperationService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgWidgets::widgetOperation._table', compact('widgetOperations_data', 'widgetOperations_stats', 'widgetOperations_filters'))->render();
        }

        return view('PkgWidgets::widgetOperation.index', compact('widgetOperations_data', 'widgetOperations_stats', 'widgetOperations_filters'));
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
            return response()->json(['success' => true, 
            'widget_operation_id' => $widgetOperation->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' => __('PkgWidgets::widgetOperation.singular')])
            ]);
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
        $itemWidgetOperation = $this->widgetOperationService->find($id);


        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._fields', compact('itemWidgetOperation'));
        }

        return view('PkgWidgets::widgetOperation.show', compact('itemWidgetOperation'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('widget_operation_id', $id);
        
        $itemWidgetOperation = $this->widgetOperationService->find($id);
        $widgetService =  new WidgetService();
        $widgets_data =  $itemWidgetOperation->widgets()->paginate(10);
        $widgets_stats = $widgetService->getwidgetStats();
        $widgets_filters = $widgetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._fields', compact('itemWidgetOperation', 'widgets_data', 'widgets_stats', 'widgets_filters'));
        }

        return view('PkgWidgets::widgetOperation.edit', compact('itemWidgetOperation', 'widgets_data', 'widgets_stats', 'widgets_filters'));

    }
    public function update(WidgetOperationRequest $request, string $id) {

        $validatedData = $request->validated();
        $widgetOperation = $this->widgetOperationService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')])
            ]);
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
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')])
            ]);
        }

        return redirect()->route('widgetOperations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')
                ])
        );

    }

    public function export()
    {
        $widgetOperations_data = $this->widgetOperationService->all();
        return Excel::download(new WidgetOperationExport($widgetOperations_data), 'widgetOperation_export.xlsx');
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

}
