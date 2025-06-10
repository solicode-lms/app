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

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('widgetType.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('widgetType');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $widgetTypes_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'widgetTypes_search',
                $this->viewState->get("filter.widgetType.widgetTypes_search")
            )],
            $request->except(['widgetTypes_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->widgetTypeService->prepareDataForIndexView($widgetTypes_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgWidgets::widgetType._index', $widgetType_compact_value)->render();
            }else{
                return view($widgetType_partialViewName, $widgetType_compact_value)->render();
            }
        }

        return view('PkgWidgets::widgetType.index', $widgetType_compact_value);
    }
    /**
     */
    public function create() {


        $itemWidgetType = $this->widgetTypeService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._fields', compact('itemWidgetType'));
        }
        return view('PkgWidgets::widgetType.create', compact('itemWidgetType'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $widgetType_ids = $request->input('ids', []);

        if (!is_array($widgetType_ids) || count($widgetType_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemWidgetType = $this->widgetTypeService->find($widgetType_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemWidgetType = $this->widgetTypeService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._fields', compact('bulkEdit', 'widgetType_ids', 'itemWidgetType'));
        }
        return view('PkgWidgets::widgetType.bulk-edit', compact('bulkEdit', 'widgetType_ids', 'itemWidgetType'));
    }
    /**
     */
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
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('widgetType.show_' . $id);

        $itemWidgetType = $this->widgetTypeService->edit($id);


        $this->viewState->set('scope.widget.type_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._show', array_merge(compact('itemWidgetType'),$widget_compact_value));
        }

        return view('PkgWidgets::widgetType.show', array_merge(compact('itemWidgetType'),$widget_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('widgetType.edit_' . $id);


        $itemWidgetType = $this->widgetTypeService->edit($id);




        $this->viewState->set('scope.widget.type_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._edit', array_merge(compact('bulkEdit' , 'itemWidgetType',),$widget_compact_value));
        }

        return view('PkgWidgets::widgetType.edit', array_merge(compact('bulkEdit' ,'itemWidgetType',),$widget_compact_value));


    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $widgetType_ids = $request->input('widgetType_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($widgetType_ids) || count($widgetType_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($widgetType_ids as $id) {
            $entity = $this->widgetTypeService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->widgetTypeService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->widgetTypeService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $widgetType_ids = $request->input('ids', []);
        if (!is_array($widgetType_ids) || count($widgetType_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($widgetType_ids as $id) {
            $entity = $this->widgetTypeService->find($id);
            $this->widgetTypeService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($widgetType_ids) . ' éléments',
            'modelName' => __('PkgWidgets::widgetType.plural')
        ]));
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
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $widgetTypeRequest = new WidgetTypeRequest();
        $fullRules = $widgetTypeRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:widget_types,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}