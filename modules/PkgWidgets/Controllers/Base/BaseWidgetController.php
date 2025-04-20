<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\WidgetService;
use Modules\PkgAutorisation\Services\RoleService;
use Modules\Core\Services\SysModelService;
use Modules\PkgWidgets\Services\WidgetOperationService;
use Modules\PkgWidgets\Services\SectionWidgetService;
use Modules\Core\Services\SysColorService;
use Modules\PkgWidgets\Services\WidgetTypeService;
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
    protected $roleService;
    protected $sysModelService;
    protected $widgetOperationService;
    protected $sectionWidgetService;
    protected $sysColorService;
    protected $widgetTypeService;

    public function __construct(WidgetService $widgetService, RoleService $roleService, SysModelService $sysModelService, WidgetOperationService $widgetOperationService, SectionWidgetService $sectionWidgetService, SysColorService $sysColorService, WidgetTypeService $widgetTypeService) {
        parent::__construct();
        $this->service  =  $widgetService;
        $this->widgetService = $widgetService;
        $this->roleService = $roleService;
        $this->sysModelService = $sysModelService;
        $this->widgetOperationService = $widgetOperationService;
        $this->sectionWidgetService = $sectionWidgetService;
        $this->sysColorService = $sysColorService;
        $this->widgetTypeService = $widgetTypeService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('widget.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('widget');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $widgets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'widgets_search',
                $this->viewState->get("filter.widget.widgets_search")
            )],
            $request->except(['widgets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->widgetService->prepareDataForIndexView($widgets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgWidgets::widget._index', $widget_compact_value)->render();
            }else{
                return view($widget_partialViewName, $widget_compact_value)->render();
            }
        }

        return view('PkgWidgets::widget.index', $widget_compact_value);
    }
    /**
     */
    public function create() {


        $itemWidget = $this->widgetService->createInstance();
        

        $widgetTypes = $this->widgetTypeService->all();
        $sysModels = $this->sysModelService->all();
        $widgetOperations = $this->widgetOperationService->all();
        $sysColors = $this->sysColorService->all();
        $roles = $this->roleService->all();
        $sectionWidgets = $this->sectionWidgetService->all();

        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', compact('itemWidget', 'roles', 'sysModels', 'widgetOperations', 'sectionWidgets', 'sysColors', 'widgetTypes'));
        }
        return view('PkgWidgets::widget.create', compact('itemWidget', 'roles', 'sysModels', 'widgetOperations', 'sectionWidgets', 'sysColors', 'widgetTypes'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $widget_ids = $request->input('ids', []);

        if (!is_array($widget_ids) || count($widget_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemWidget = $this->widgetService->find($widget_ids[0]);
         
 
        $widgetTypes = $this->widgetTypeService->all();
        $sysModels = $this->sysModelService->all();
        $widgetOperations = $this->widgetOperationService->all();
        $sysColors = $this->sysColorService->all();
        $roles = $this->roleService->all();
        $sectionWidgets = $this->sectionWidgetService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemWidget = $this->widgetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', compact('bulkEdit', 'widget_ids', 'itemWidget', 'roles', 'sysModels', 'widgetOperations', 'sectionWidgets', 'sysColors', 'widgetTypes'));
        }
        return view('PkgWidgets::widget.bulk-edit', compact('bulkEdit', 'widget_ids', 'itemWidget', 'roles', 'sysModels', 'widgetOperations', 'sectionWidgets', 'sysColors', 'widgetTypes'));
    }
    /**
     */
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

        return redirect()->route('widgets.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widget,
                'modelName' => __('PkgWidgets::widget.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('widget.edit_' . $id);


        $itemWidget = $this->widgetService->edit($id);


        $widgetTypes = $this->widgetTypeService->all();
        $sysModels = $this->sysModelService->all();
        $widgetOperations = $this->widgetOperationService->all();
        $sysColors = $this->sysColorService->all();
        $roles = $this->roleService->all();
        $sectionWidgets = $this->sectionWidgetService->all();


        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', array_merge(compact('itemWidget','roles', 'sysModels', 'widgetOperations', 'sectionWidgets', 'sysColors', 'widgetTypes'),));
        }

        return view('PkgWidgets::widget.edit', array_merge(compact('itemWidget','roles', 'sysModels', 'widgetOperations', 'sectionWidgets', 'sysColors', 'widgetTypes'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('widget.edit_' . $id);


        $itemWidget = $this->widgetService->edit($id);


        $widgetTypes = $this->widgetTypeService->all();
        $sysModels = $this->sysModelService->all();
        $widgetOperations = $this->widgetOperationService->all();
        $sysColors = $this->sysColorService->all();
        $roles = $this->roleService->all();
        $sectionWidgets = $this->sectionWidgetService->all();


        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', array_merge(compact('itemWidget','roles', 'sysModels', 'widgetOperations', 'sectionWidgets', 'sysColors', 'widgetTypes'),));
        }

        return view('PkgWidgets::widget.edit', array_merge(compact('itemWidget','roles', 'sysModels', 'widgetOperations', 'sectionWidgets', 'sysColors', 'widgetTypes'),));


    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $widget_ids = $request->input('widget_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($widget_ids) || count($widget_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($widget_ids as $id) {
            $entity = $this->widgetService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->widgetService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->widgetService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $widget_ids = $request->input('ids', []);
        if (!is_array($widget_ids) || count($widget_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($widget_ids as $id) {
            $entity = $this->widgetService->find($id);
            $this->widgetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($widget_ids) . ' éléments',
            'modelName' => __('PkgWidgets::widget.plural')
        ]));
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
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $widgetRequest = new WidgetRequest();
        $fullRules = $widgetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:widgets,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.', 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}