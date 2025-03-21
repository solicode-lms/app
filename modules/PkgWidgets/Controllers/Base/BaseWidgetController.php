<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\WidgetService;
use Modules\Core\Services\SysModelService;
use Modules\PkgWidgets\Services\WidgetOperationService;
use Modules\PkgWidgets\Services\WidgetTypeService;
use Modules\PkgWidgets\Services\WidgetUtilisateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgWidgets\App\Requests\WidgetRequest;
use Modules\PkgWidgets\Models\Widget;
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
        $this->service  =  $widgetService;
        $this->widgetService = $widgetService;
        $this->sysModelService = $sysModelService;
        $this->widgetOperationService = $widgetOperationService;
        $this->widgetTypeService = $widgetTypeService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('widget.index');



        // Extraire les paramètres de recherche, page, et filtres
        $widgets_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('widgets_search', $this->viewState->get("filter.widget.widgets_search"))],
            $request->except(['widgets_search', 'page', 'sort'])
        );

        // Paginer les widgets
        $widgets_data = $this->widgetService->paginate($widgets_params);

        // Récupérer les statistiques et les champs filtrables
        $widgets_stats = $this->widgetService->getwidgetStats();
        $this->viewState->set('stats.widget.stats'  , $widgets_stats);
        $widgets_filters = $this->widgetService->getFieldsFilterable();
        $widget_instance =  $this->widgetService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgWidgets::widget._table', compact('widgets_data', 'widgets_stats', 'widgets_filters','widget_instance'))->render();
        }

        return view('PkgWidgets::widget.index', compact('widgets_data', 'widgets_stats', 'widgets_filters','widget_instance'));
    }
    public function create() {


        $itemWidget = $this->widgetService->createInstance();
        

        $sysModels = $this->sysModelService->all();
        $widgetTypes = $this->widgetTypeService->all();
        $widgetOperations = $this->widgetOperationService->all();

        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes'));
        }
        return view('PkgWidgets::widget.create', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes'));
    }
    public function store(WidgetRequest $request) {
        $validatedData = $request->validated();
        $widget = $this->widgetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $widget,
                'modelName' => __('PkgWidgets::widget.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $widget->id]
            );
        }

        return redirect()->route('widgets.edit',['widget' => $widget->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widget,
                'modelName' => __('PkgWidgets::widget.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('widget.edit_' . $id);


        $itemWidget = $this->widgetService->find($id);


        $sysModels = $this->sysModelService->all();
        $widgetTypes = $this->widgetTypeService->all();
        $widgetOperations = $this->widgetOperationService->all();


        $this->viewState->set('scope.widgetUtilisateur.widget_id', $id);


        $widgetUtilisateurService =  new WidgetUtilisateurService();
        $widgetUtilisateurs_data =  $widgetUtilisateurService->paginate();
        $widgetUtilisateurs_stats = $widgetUtilisateurService->getwidgetUtilisateurStats();
        $widgetUtilisateurs_filters = $widgetUtilisateurService->getFieldsFilterable();
        $widgetUtilisateur_instance =  $widgetUtilisateurService->createInstance();

        if (request()->ajax()) {
            return view('PkgWidgets::widget._edit', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes', 'widgetUtilisateurs_data', 'widgetUtilisateurs_stats', 'widgetUtilisateurs_filters', 'widgetUtilisateur_instance'));
        }

        return view('PkgWidgets::widget.edit', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes', 'widgetUtilisateurs_data', 'widgetUtilisateurs_stats', 'widgetUtilisateurs_filters', 'widgetUtilisateur_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('widget.edit_' . $id);


        $itemWidget = $this->widgetService->find($id);


        $sysModels = $this->sysModelService->all();
        $widgetTypes = $this->widgetTypeService->all();
        $widgetOperations = $this->widgetOperationService->all();


        $this->viewState->set('scope.widgetUtilisateur.widget_id', $id);
        

        $widgetUtilisateurService =  new WidgetUtilisateurService();
        $widgetUtilisateurs_data =  $widgetUtilisateurService->paginate();
        $widgetUtilisateurs_stats = $widgetUtilisateurService->getwidgetUtilisateurStats();
        $this->viewState->set('stats.widgetUtilisateur.stats'  , $widgetUtilisateurs_stats);
        $widgetUtilisateurs_filters = $widgetUtilisateurService->getFieldsFilterable();
        $widgetUtilisateur_instance =  $widgetUtilisateurService->createInstance();

        if (request()->ajax()) {
            return view('PkgWidgets::widget._edit', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes', 'widgetUtilisateurs_data', 'widgetUtilisateurs_stats', 'widgetUtilisateurs_filters', 'widgetUtilisateur_instance'));
        }

        return view('PkgWidgets::widget.edit', compact('itemWidget', 'sysModels', 'widgetOperations', 'widgetTypes', 'widgetUtilisateurs_data', 'widgetUtilisateurs_stats', 'widgetUtilisateurs_filters', 'widgetUtilisateur_instance'));

    }
    public function update(WidgetRequest $request, string $id) {

        $validatedData = $request->validated();
        $widget = $this->widgetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $widget->id]
            );
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
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('widgets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')
                ])
        );

    }

    public function export($format)
    {
        $widgets_data = $this->widgetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WidgetExport($widgets_data,'csv'), 'widget_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WidgetExport($widgets_data,'xlsx'), 'widget_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
