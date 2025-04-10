<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysControllerService;
use Modules\Core\Services\SysModuleService;
use Modules\PkgAutorisation\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\App\Requests\SysControllerRequest;
use Modules\Core\Models\SysController;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysControllerExport;
use Modules\Core\App\Imports\SysControllerImport;
use Modules\Core\Services\ContextState;

class BaseSysControllerController extends AdminController
{
    protected $sysControllerService;
    protected $sysModuleService;

    public function __construct(SysControllerService $sysControllerService, SysModuleService $sysModuleService) {
        parent::__construct();
        $this->service  =  $sysControllerService;
        $this->sysControllerService = $sysControllerService;
        $this->sysModuleService = $sysModuleService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('sysController.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $sysControllers_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'sysControllers_search',
                $this->viewState->get("filter.sysController.sysControllers_search")
            )],
            $request->except(['sysControllers_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->sysControllerService->prepareDataForIndexView($sysControllers_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($sysController_partialViewName, $sysController_compact_value)->render();
        }

        return view('Core::sysController.index', $sysController_compact_value);
    }
    public function create() {


        $itemSysController = $this->sysControllerService->createInstance();
        

        $sysModules = $this->sysModuleService->all();

        if (request()->ajax()) {
            return view('Core::sysController._fields', compact('itemSysController', 'sysModules'));
        }
        return view('Core::sysController.create', compact('itemSysController', 'sysModules'));
    }
    public function store(SysControllerRequest $request) {
        $validatedData = $request->validated();
        $sysController = $this->sysControllerService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sysController,
                'modelName' => __('Core::sysController.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $sysController->id]
            );
        }

        return redirect()->route('sysControllers.edit',['sysController' => $sysController->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysController,
                'modelName' => __('Core::sysController.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('sysController.edit_' . $id);


        $itemSysController = $this->sysControllerService->edit($id);


        $sysModules = $this->sysModuleService->all();


        $this->viewState->set('scope.permission.controller_id', $id);
        

        $permissionService =  new PermissionService();
        $permissions_view_data = $permissionService->prepareDataForIndexView();
        extract($permissions_view_data);

        if (request()->ajax()) {
            return view('Core::sysController._edit', array_merge(compact('itemSysController','sysModules'),$permission_compact_value));
        }

        return view('Core::sysController.edit', array_merge(compact('itemSysController','sysModules'),$permission_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('sysController.edit_' . $id);


        $itemSysController = $this->sysControllerService->edit($id);


        $sysModules = $this->sysModuleService->all();


        $this->viewState->set('scope.permission.controller_id', $id);
        

        $permissionService =  new PermissionService();
        $permissions_view_data = $permissionService->prepareDataForIndexView();
        extract($permissions_view_data);

        if (request()->ajax()) {
            return view('Core::sysController._edit', array_merge(compact('itemSysController','sysModules'),$permission_compact_value));
        }

        return view('Core::sysController.edit', array_merge(compact('itemSysController','sysModules'),$permission_compact_value));


    }
    public function update(SysControllerRequest $request, string $id) {

        $validatedData = $request->validated();
        $sysController = $this->sysControllerService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $sysController->id]
            );
        }

        return redirect()->route('sysControllers.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $sysController = $this->sysControllerService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('sysControllers.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')
                ])
        );

    }

    public function export($format)
    {
        $sysControllers_data = $this->sysControllerService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SysControllerExport($sysControllers_data,'csv'), 'sysController_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SysControllerExport($sysControllers_data,'xlsx'), 'sysController_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SysControllerImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysControllers.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysControllers.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysController.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysControllers()
    {
        $sysControllers = $this->sysControllerService->all();
        return response()->json($sysControllers);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $sysController = $this->sysControllerService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedSysController = $this->sysControllerService->dataCalcul($sysController);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedSysController
        ]);
    }
    

}