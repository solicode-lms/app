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
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
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

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('widgetOperation.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('widgetOperation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $widgetOperations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'widgetOperations_search',
                $this->viewState->get("filter.widgetOperation.widgetOperations_search")
            )],
            $request->except(['widgetOperations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->widgetOperationService->prepareDataForIndexView($widgetOperations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgWidgets::widgetOperation._index', $widgetOperation_compact_value)->render();
            }else{
                return view($widgetOperation_partialViewName, $widgetOperation_compact_value)->render();
            }
        }

        return view('PkgWidgets::widgetOperation.index', $widgetOperation_compact_value);
    }
    /**
     */
    public function create() {


        $itemWidgetOperation = $this->widgetOperationService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._fields', compact('bulkEdit' ,'itemWidgetOperation'));
        }
        return view('PkgWidgets::widgetOperation.create', compact('bulkEdit' ,'itemWidgetOperation'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $widgetOperation_ids = $request->input('ids', []);

        if (!is_array($widgetOperation_ids) || count($widgetOperation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemWidgetOperation = $this->widgetOperationService->find($widgetOperation_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemWidgetOperation = $this->widgetOperationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._fields', compact('bulkEdit', 'widgetOperation_ids', 'itemWidgetOperation'));
        }
        return view('PkgWidgets::widgetOperation.bulk-edit', compact('bulkEdit', 'widgetOperation_ids', 'itemWidgetOperation'));
    }
    /**
     */
    public function store(WidgetOperationRequest $request) {
        $validatedData = $request->validated();
        $widgetOperation = $this->widgetOperationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' => __('PkgWidgets::widgetOperation.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $widgetOperation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('widgetOperations.edit', ['widgetOperation' => $widgetOperation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' => __('PkgWidgets::widgetOperation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('widgetOperation.show_' . $id);

        $itemWidgetOperation = $this->widgetOperationService->edit($id);


        $this->viewState->set('scope.widget.operation_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._show', array_merge(compact('itemWidgetOperation'),$widget_compact_value));
        }

        return view('PkgWidgets::widgetOperation.show', array_merge(compact('itemWidgetOperation'),$widget_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('widgetOperation.edit_' . $id);


        $itemWidgetOperation = $this->widgetOperationService->edit($id);




        $this->viewState->set('scope.widget.operation_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._edit', array_merge(compact('bulkEdit' , 'itemWidgetOperation',),$widget_compact_value));
        }

        return view('PkgWidgets::widgetOperation.edit', array_merge(compact('bulkEdit' ,'itemWidgetOperation',),$widget_compact_value));


    }
    /**
     */
    public function update(WidgetOperationRequest $request, string $id) {

        $validatedData = $request->validated();
        $widgetOperation = $this->widgetOperationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $widgetOperation->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $widgetOperation_ids = $request->input('widgetOperation_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($widgetOperation_ids) || count($widgetOperation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }

        // 🔹 Récupérer les valeurs de ces champs
        $valeursChamps = [];
        foreach ($champsCoches as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob",$this->service->modelName,$this->service->moduleName);
         
        dispatch(new BulkEditJob(
            Auth::id(),
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $widgetOperation_ids,
            $champsCoches,
            $valeursChamps
        ));

       
        return JsonResponseHelper::success(
             __('Mise à jour en masse effectuée avec succès.'),
                ['traitement_token' => $jobManager->getToken()]
        );

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $widgetOperation = $this->widgetOperationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $widgetOperation_ids = $request->input('ids', []);
        if (!is_array($widgetOperation_ids) || count($widgetOperation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($widgetOperation_ids as $id) {
            $entity = $this->widgetOperationService->find($id);
            $this->widgetOperationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($widgetOperation_ids) . ' éléments',
            'modelName' => __('PkgWidgets::widgetOperation.plural')
        ]));
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

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (WidgetOperation) par ID, en format JSON.
     */
    public function getWidgetOperation(Request $request, $id)
    {
        try {
            $widgetOperation = $this->widgetOperationService->find($id);
            return response()->json($widgetOperation);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Entité non trouvée ou erreur.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    public function dataCalcul(Request $request)
    {
        $data = $request->all();

        // Traitement métier personnalisé (ne modifie pas la base)
        $updatedWidgetOperation = $this->widgetOperationService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedWidgetOperation],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
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
        $widgetOperationRequest = new WidgetOperationRequest();
        $fullRules = $widgetOperationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:widget_operations,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(
             __('Mise à jour réussie.'),
                array_merge(
                    ['entity_id' => $validated['id']],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
        );
    }
}