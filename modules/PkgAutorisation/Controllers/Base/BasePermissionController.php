<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Controllers\Base;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\Core\Services\FeatureService;
use Modules\Core\Services\SysControllerService;
use Modules\PkgAutorisation\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutorisation\App\Requests\PermissionRequest;
use Modules\PkgAutorisation\Models\Permission;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutorisation\App\Exports\PermissionExport;
use Modules\PkgAutorisation\App\Imports\PermissionImport;
use Modules\Core\Services\ContextState;

class BasePermissionController extends AdminController
{
    protected $permissionService;
    protected $featureService;
    protected $sysControllerService;
    protected $roleService;

    public function __construct(PermissionService $permissionService, FeatureService $featureService, SysControllerService $sysControllerService, RoleService $roleService) {
        parent::__construct();
        $this->service  =  $permissionService;
        $this->permissionService = $permissionService;
        $this->featureService = $featureService;
        $this->sysControllerService = $sysControllerService;
        $this->roleService = $roleService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('permission.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $permissions_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'permissions_search',
                $this->viewState->get("filter.permission.permissions_search")
            )],
            $request->except(['permissions_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->permissionService->prepareDataForIndexView($permissions_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($permission_partialViewName, $permission_compact_value)->render();
        }

        return view('PkgAutorisation::permission.index', $permission_compact_value);
    }
    public function create() {


        $itemPermission = $this->permissionService->createInstance();
        

        $sysControllers = $this->sysControllerService->all();
        $features = $this->featureService->all();
        $roles = $this->roleService->all();

        if (request()->ajax()) {
            return view('PkgAutorisation::permission._fields', compact('itemPermission', 'features', 'roles', 'sysControllers'));
        }
        return view('PkgAutorisation::permission.create', compact('itemPermission', 'features', 'roles', 'sysControllers'));
    }
    public function store(PermissionRequest $request) {
        $validatedData = $request->validated();
        $permission = $this->permissionService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $permission,
                'modelName' => __('PkgAutorisation::permission.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $permission->id]
            );
        }

        return redirect()->route('permissions.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $permission,
                'modelName' => __('PkgAutorisation::permission.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('permission.edit_' . $id);


        $itemPermission = $this->permissionService->find($id);


        $sysControllers = $this->sysControllerService->all();
        $features = $this->featureService->all();
        $roles = $this->roleService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::permission._fields', array_merge(compact('itemPermission','features', 'roles', 'sysControllers'),));
        }

        return view('PkgAutorisation::permission.edit', array_merge(compact('itemPermission','features', 'roles', 'sysControllers'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('permission.edit_' . $id);


        $itemPermission = $this->permissionService->find($id);


        $sysControllers = $this->sysControllerService->all();
        $features = $this->featureService->all();
        $roles = $this->roleService->all();


        if (request()->ajax()) {
            return view('PkgAutorisation::permission._fields', array_merge(compact('itemPermission','features', 'roles', 'sysControllers'),));
        }

        return view('PkgAutorisation::permission.edit', array_merge(compact('itemPermission','features', 'roles', 'sysControllers'),));

    }
    public function update(PermissionRequest $request, string $id) {

        $validatedData = $request->validated();
        $permission = $this->permissionService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $permission->id]
            );
        }

        return redirect()->route('permissions.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $permission = $this->permissionService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('permissions.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $permission,
                'modelName' =>  __('PkgAutorisation::permission.singular')
                ])
        );

    }

    public function export($format)
    {
        $permissions_data = $this->permissionService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new PermissionExport($permissions_data,'csv'), 'permission_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new PermissionExport($permissions_data,'xlsx'), 'permission_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new PermissionImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('permissions.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('permissions.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutorisation::permission.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getPermissions()
    {
        $permissions = $this->permissionService->all();
        return response()->json($permissions);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $permission = $this->permissionService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedPermission = $this->permissionService->dataCalcul($permission);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedPermission
        ]);
    }
    

}