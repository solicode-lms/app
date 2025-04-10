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
        



         // Extraire les paramètres de recherche, pagination, filtres
        $widgetTypes_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'widgetTypes_search',
                $this->viewState->get("filter.widgetType.widgetTypes_search")
            )],
            $request->except(['widgetTypes_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->widgetTypeService->prepareDataForIndexView($widgetTypes_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($widgetType_partialViewName, $widgetType_compact_value)->render();
        }

        return view('PkgWidgets::widgetType.index', $widgetType_compact_value);
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


        $itemWidgetType = $this->widgetTypeService->edit($id);




        $this->viewState->set('scope.widget.type_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._edit', array_merge(compact('itemWidgetType',),$widget_compact_value));
        }

        return view('PkgWidgets::widgetType.edit', array_merge(compact('itemWidgetType',),$widget_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('widgetType.edit_' . $id);


        $itemWidgetType = $this->widgetTypeService->edit($id);




        $this->viewState->set('scope.widget.type_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._edit', array_merge(compact('itemWidgetType',),$widget_compact_value));
        }

        return view('PkgWidgets::widgetType.edit', array_merge(compact('itemWidgetType',),$widget_compact_value));


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