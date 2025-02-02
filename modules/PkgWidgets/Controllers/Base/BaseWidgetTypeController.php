<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\WidgetTypeService;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgWidgets\App\Requests\WidgetTypeRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\WidgetTypeExport;
use Modules\PkgWidgets\App\Imports\WidgetTypeImport;
use Modules\Core\Services\ContextState;

class BaseWidgetTypeController extends AdminController
{
    protected $widgetTypeService;

    public function __construct(WidgetTypeService $widgetTypeService) {
        parent::__construct();
        $this->widgetTypeService = $widgetTypeService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $widgetTypes_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('widgetTypes_search', '')],
            $request->except(['widgetTypes_search', 'page', 'sort'])
        );

        // Paginer les widgetTypes
        $widgetTypes_data = $this->widgetTypeService->paginate($widgetTypes_params);

        // Récupérer les statistiques et les champs filtrables
        $widgetTypes_stats = $this->widgetTypeService->getwidgetTypeStats();
        $widgetTypes_filters = $this->widgetTypeService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgWidgets::widgetType._table', compact('widgetTypes_data', 'widgetTypes_stats', 'widgetTypes_filters'))->render();
        }

        return view('PkgWidgets::widgetType.index', compact('widgetTypes_data', 'widgetTypes_stats', 'widgetTypes_filters'));
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
            return response()->json(['success' => true, 
            'widget_type_id' => $widgetType->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $widgetType,
                'modelName' => __('PkgWidgets::widgetType.singular')])
            ]);
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

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('widget_type_id', $id);
        
        $itemWidgetType = $this->widgetTypeService->find($id);
        $widgetService =  new WidgetService();
        $widgets_data =  $itemWidgetType->widgets()->paginate(10);
        $widgets_stats = $widgetService->getwidgetStats();
        $widgets_filters = $widgetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._fields', compact('itemWidgetType', 'widgets_data', 'widgets_stats', 'widgets_filters'));
        }

        return view('PkgWidgets::widgetType.edit', compact('itemWidgetType', 'widgets_data', 'widgets_stats', 'widgets_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('widget_type_id', $id);
        
        $itemWidgetType = $this->widgetTypeService->find($id);
        $widgetService =  new WidgetService();
        $widgets_data =  $itemWidgetType->widgets()->paginate(10);
        $widgets_stats = $widgetService->getwidgetStats();
        $widgets_filters = $widgetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._fields', compact('itemWidgetType', 'widgets_data', 'widgets_stats', 'widgets_filters'));
        }

        return view('PkgWidgets::widgetType.edit', compact('itemWidgetType', 'widgets_data', 'widgets_stats', 'widgets_filters'));

    }
    public function update(WidgetTypeRequest $request, string $id) {

        $validatedData = $request->validated();
        $widgetType = $this->widgetTypeService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')])
            ]);
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
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')])
            ]);
        }

        return redirect()->route('widgetTypes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')
                ])
        );

    }

    public function export()
    {
        $widgetTypes_data = $this->widgetTypeService->all();
        return Excel::download(new WidgetTypeExport($widgetTypes_data), 'widgetType_export.xlsx');
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

}
