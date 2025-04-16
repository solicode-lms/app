<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\ApprenantKonosyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\ApprenantKonosyRequest;
use Modules\PkgApprenants\Models\ApprenantKonosy;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprenants\App\Exports\ApprenantKonosyExport;
use Modules\PkgApprenants\App\Imports\ApprenantKonosyImport;
use Modules\Core\Services\ContextState;

class BaseApprenantKonosyController extends AdminController
{
    protected $apprenantKonosyService;

    public function __construct(ApprenantKonosyService $apprenantKonosyService) {
        parent::__construct();
        $this->service  =  $apprenantKonosyService;
        $this->apprenantKonosyService = $apprenantKonosyService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('apprenantKonosy.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('apprenantKonosy');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $apprenantKonosies_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'apprenantKonosies_search',
                $this->viewState->get("filter.apprenantKonosy.apprenantKonosies_search")
            )],
            $request->except(['apprenantKonosies_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->apprenantKonosyService->prepareDataForIndexView($apprenantKonosies_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprenants::apprenantKonosy._index', $apprenantKonosy_compact_value)->render();
            }else{
                return view($apprenantKonosy_partialViewName, $apprenantKonosy_compact_value)->render();
            }
        }

        return view('PkgApprenants::apprenantKonosy.index', $apprenantKonosy_compact_value);
    }
    /**
     */
    public function create() {


        $itemApprenantKonosy = $this->apprenantKonosyService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgApprenants::apprenantKonosy._fields', compact('itemApprenantKonosy'));
        }
        return view('PkgApprenants::apprenantKonosy.create', compact('itemApprenantKonosy'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $apprenantKonosy_ids = $request->input('ids', []);

        if (!is_array($apprenantKonosy_ids) || count($apprenantKonosy_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemApprenantKonosy = $this->apprenantKonosyService->find($apprenantKonosy_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemApprenantKonosy = $this->apprenantKonosyService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprenants::apprenantKonosy._fields', compact('bulkEdit', 'apprenantKonosy_ids', 'itemApprenantKonosy'));
        }
        return view('PkgApprenants::apprenantKonosy.bulk-edit', compact('bulkEdit', 'apprenantKonosy_ids', 'itemApprenantKonosy'));
    }
    /**
     */
    public function store(ApprenantKonosyRequest $request) {
        $validatedData = $request->validated();
        $apprenantKonosy = $this->apprenantKonosyService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' => __('PkgApprenants::apprenantKonosy.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $apprenantKonosy->id]
            );
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' => __('PkgApprenants::apprenantKonosy.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('apprenantKonosy.edit_' . $id);


        $itemApprenantKonosy = $this->apprenantKonosyService->edit($id);




        if (request()->ajax()) {
            return view('PkgApprenants::apprenantKonosy._fields', array_merge(compact('itemApprenantKonosy',),));
        }

        return view('PkgApprenants::apprenantKonosy.edit', array_merge(compact('itemApprenantKonosy',),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('apprenantKonosy.edit_' . $id);


        $itemApprenantKonosy = $this->apprenantKonosyService->edit($id);




        if (request()->ajax()) {
            return view('PkgApprenants::apprenantKonosy._fields', array_merge(compact('itemApprenantKonosy',),));
        }

        return view('PkgApprenants::apprenantKonosy.edit', array_merge(compact('itemApprenantKonosy',),));


    }
    /**
     */
    public function update(ApprenantKonosyRequest $request, string $id) {

        $validatedData = $request->validated();
        $apprenantKonosy = $this->apprenantKonosyService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' =>  __('PkgApprenants::apprenantKonosy.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $apprenantKonosy->id]
            );
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' =>  __('PkgApprenants::apprenantKonosy.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $apprenantKonosy_ids = $request->input('apprenantKonosy_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($apprenantKonosy_ids) || count($apprenantKonosy_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($apprenantKonosy_ids as $id) {
            $entity = $this->apprenantKonosyService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->apprenantKonosyService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->apprenantKonosyService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $apprenantKonosy = $this->apprenantKonosyService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' =>  __('PkgApprenants::apprenantKonosy.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' =>  __('PkgApprenants::apprenantKonosy.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $apprenantKonosy_ids = $request->input('ids', []);
        if (!is_array($apprenantKonosy_ids) || count($apprenantKonosy_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($apprenantKonosy_ids as $id) {
            $entity = $this->apprenantKonosyService->find($id);
            $this->apprenantKonosyService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($apprenantKonosy_ids) . ' éléments',
            'modelName' => __('PkgApprenants::apprenantKonosy.plural')
        ]));
    }

    public function export($format)
    {
        $apprenantKonosies_data = $this->apprenantKonosyService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ApprenantKonosyExport($apprenantKonosies_data,'csv'), 'apprenantKonosy_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ApprenantKonosyExport($apprenantKonosies_data,'xlsx'), 'apprenantKonosy_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ApprenantKonosyImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('apprenantKonosies.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::apprenantKonosy.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getApprenantKonosies()
    {
        $apprenantKonosies = $this->apprenantKonosyService->all();
        return response()->json($apprenantKonosies);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $apprenantKonosy = $this->apprenantKonosyService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedApprenantKonosy = $this->apprenantKonosyService->dataCalcul($apprenantKonosy);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedApprenantKonosy
        ]);
    }
    

}