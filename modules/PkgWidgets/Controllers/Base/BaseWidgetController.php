<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\WidgetService;
use Modules\Core\Services\SysModelService;
use Modules\PkgWidgets\Services\WidgetOperationService;
use Modules\PkgWidgets\Services\WidgetTypeService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgWidgets\App\Requests\WidgetRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\WidgetExport;
use Modules\PkgWidgets\App\Imports\WidgetImport;
use Modules\Core\Services\ContextState;

class BaseWidgetController extends AdminController
{
    protected $widgetService;
    protected $sysModelService;
    protected $widgetOperationService;
    protected $widgetTypeService;

    public function __construct(WidgetService $widgetService, SysModelService $sysModelService, WidgetOperationService $widgetOperationService, WidgetTypeService $widgetTypeService) {
        parent::__construct();
        $this->widgetService = $widgetService;
        $this->sysModelService = $sysModelService;
        $this->widgetOperationService = $widgetOperationService;
        $this->widgetTypeService = $widgetTypeService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $widgets_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('widgets_search', '')],
            $request->except(['widgets_search', 'page', 'sort'])
        );

        // Paginer les widgets
        $widgets_data = $this->widgetService->paginate($widgets_params);

        // Récupérer les statistiques et les champs filtrables
        $widgets_stats = $this->widgetService->getwidgetStats();
        $widgets_filters = $this->widgetService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgWidgets::widget._table', compact('widgets_data', 'widgets_stats', 'widgets_filters'))->render();
        }

        return view('PkgWidgets::widget.index', compact('widgets_data', 'widgets_stats', 'widgets_filters'));
    }
    public function create() {
        $itemWidget = $this->widgetService->createInstance();
        $sysModels = $this->sysModelService->all();
        $widgetOperations = $this->widgetOperationService->all();
        $widgetTypes = $this->widgetTypeService->all();


        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes'));
        }
        return view('PkgWidgets::widget.create', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes'));
    }
    public function store(WidgetRequest $request) {
        $validatedData = $request->validated();
        $widget = $this->widgetService->create($validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'entity_id' => $widget->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $widget,
                'modelName' => __('PkgWidgets::widget.singular')])
            ]);
        }

        return redirect()->route('widgets.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widget,
                'modelName' => __('PkgWidgets::widget.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('widget_id', $id);
        
        $itemWidget = $this->widgetService->find($id);
        $sysModels = $this->sysModelService->all();
        $widgetOperations = $this->widgetOperationService->all();
        $widgetTypes = $this->widgetTypeService->all();

        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes'));
        }

        return view('PkgWidgets::widget.edit', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('widget_id', $id);
        
        $itemWidget = $this->widgetService->find($id);
        $sysModels = $this->sysModelService->all();
        $widgetOperations = $this->widgetOperationService->all();
        $widgetTypes = $this->widgetTypeService->all();

        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes'));
        }

        return view('PkgWidgets::widget.edit', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes'));

    }
    public function update(WidgetRequest $request, string $id) {

        $validatedData = $request->validated();
        $widget = $this->widgetService->update($id, $validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')])
            ]);
        }

        return redirect()->route('widgets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $widget = $this->widgetService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')])
            ]);
        }

        return redirect()->route('widgets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')
                ])
        );

    }

    public function export()
    {
        $widgets_data = $this->widgetService->all();
        return Excel::download(new WidgetExport($widgets_data), 'widget_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new WidgetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('widgets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('widgets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::widget.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgets()
    {
        $widgets = $this->widgetService->all();
        return response()->json($widgets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $widget = $this->widgetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWidget = $this->widgetService->dataCalcul($widget);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWidget
        ]);
    }
    


}
