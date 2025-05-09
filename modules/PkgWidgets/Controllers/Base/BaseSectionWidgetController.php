<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\SectionWidgetService;
use Modules\Core\Services\SysColorService;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgWidgets\App\Requests\SectionWidgetRequest;
use Modules\PkgWidgets\Models\SectionWidget;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\SectionWidgetExport;
use Modules\PkgWidgets\App\Imports\SectionWidgetImport;
use Modules\Core\Services\ContextState;

class BaseSectionWidgetController extends AdminController
{
    protected $sectionWidgetService;
    protected $sysColorService;

    public function __construct(SectionWidgetService $sectionWidgetService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $sectionWidgetService;
        $this->sectionWidgetService = $sectionWidgetService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('sectionWidget.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('sectionWidget');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $sectionWidgets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'sectionWidgets_search',
                $this->viewState->get("filter.sectionWidget.sectionWidgets_search")
            )],
            $request->except(['sectionWidgets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->sectionWidgetService->prepareDataForIndexView($sectionWidgets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgWidgets::sectionWidget._index', $sectionWidget_compact_value)->render();
            }else{
                return view($sectionWidget_partialViewName, $sectionWidget_compact_value)->render();
            }
        }

        return view('PkgWidgets::sectionWidget.index', $sectionWidget_compact_value);
    }
    /**
     */
    public function create() {


        $itemSectionWidget = $this->sectionWidgetService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        if (request()->ajax()) {
            return view('PkgWidgets::sectionWidget._fields', compact('itemSectionWidget', 'sysColors'));
        }
        return view('PkgWidgets::sectionWidget.create', compact('itemSectionWidget', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $sectionWidget_ids = $request->input('ids', []);

        if (!is_array($sectionWidget_ids) || count($sectionWidget_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemSectionWidget = $this->sectionWidgetService->find($sectionWidget_ids[0]);
         
 
        $sysColors = $this->sysColorService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemSectionWidget = $this->sectionWidgetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgWidgets::sectionWidget._fields', compact('bulkEdit', 'sectionWidget_ids', 'itemSectionWidget', 'sysColors'));
        }
        return view('PkgWidgets::sectionWidget.bulk-edit', compact('bulkEdit', 'sectionWidget_ids', 'itemSectionWidget', 'sysColors'));
    }
    /**
     */
    public function store(SectionWidgetRequest $request) {
        $validatedData = $request->validated();
        $sectionWidget = $this->sectionWidgetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' => __('PkgWidgets::sectionWidget.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $sectionWidget->id]
            );
        }

        return redirect()->route('sectionWidgets.edit',['sectionWidget' => $sectionWidget->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' => __('PkgWidgets::sectionWidget.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('sectionWidget.show_' . $id);

        $itemSectionWidget = $this->sectionWidgetService->edit($id);


        $this->viewState->set('scope.widget.section_widget_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        if (request()->ajax()) {
            return view('PkgWidgets::sectionWidget._show', array_merge(compact('itemSectionWidget'),$widget_compact_value));
        }

        return view('PkgWidgets::sectionWidget.show', array_merge(compact('itemSectionWidget'),$widget_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('sectionWidget.edit_' . $id);


        $itemSectionWidget = $this->sectionWidgetService->edit($id);


        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.widget.section_widget_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        if (request()->ajax()) {
            return view('PkgWidgets::sectionWidget._edit', array_merge(compact('itemSectionWidget','sysColors'),$widget_compact_value));
        }

        return view('PkgWidgets::sectionWidget.edit', array_merge(compact('itemSectionWidget','sysColors'),$widget_compact_value));


    }
    /**
     */
    public function update(SectionWidgetRequest $request, string $id) {

        $validatedData = $request->validated();
        $sectionWidget = $this->sectionWidgetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' =>  __('PkgWidgets::sectionWidget.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $sectionWidget->id]
            );
        }

        return redirect()->route('sectionWidgets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' =>  __('PkgWidgets::sectionWidget.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $sectionWidget_ids = $request->input('sectionWidget_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($sectionWidget_ids) || count($sectionWidget_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($sectionWidget_ids as $id) {
            $entity = $this->sectionWidgetService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->sectionWidgetService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->sectionWidgetService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $sectionWidget = $this->sectionWidgetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' =>  __('PkgWidgets::sectionWidget.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('sectionWidgets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' =>  __('PkgWidgets::sectionWidget.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $sectionWidget_ids = $request->input('ids', []);
        if (!is_array($sectionWidget_ids) || count($sectionWidget_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($sectionWidget_ids as $id) {
            $entity = $this->sectionWidgetService->find($id);
            $this->sectionWidgetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($sectionWidget_ids) . ' éléments',
            'modelName' => __('PkgWidgets::sectionWidget.plural')
        ]));
    }

    public function export($format)
    {
        $sectionWidgets_data = $this->sectionWidgetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SectionWidgetExport($sectionWidgets_data,'csv'), 'sectionWidget_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SectionWidgetExport($sectionWidgets_data,'xlsx'), 'sectionWidget_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SectionWidgetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sectionWidgets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sectionWidgets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::sectionWidget.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSectionWidgets()
    {
        $sectionWidgets = $this->sectionWidgetService->all();
        return response()->json($sectionWidgets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $sectionWidget = $this->sectionWidgetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedSectionWidget = $this->sectionWidgetService->dataCalcul($sectionWidget);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedSectionWidget
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
        $sectionWidgetRequest = new SectionWidgetRequest();
        $fullRules = $sectionWidgetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:section_widgets,id'];
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