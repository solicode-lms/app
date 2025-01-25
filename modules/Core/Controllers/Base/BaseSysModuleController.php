<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysModuleService;
use Modules\Core\Services\SysColorService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysModuleRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysModuleExport;
use Modules\Core\App\Imports\SysModuleImport;
use Modules\Core\Services\ContextState;

class BaseSysModuleController extends AdminController
{
    protected $sysModuleService;
    protected $sysColorService;

    public function __construct(SysModuleService $sysModuleService, SysColorService $sysColorService) {
        parent::__construct();
        $this->sysModuleService = $sysModuleService;
        $this->sysColorService = $sysColorService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $sysModules_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('sysModules_search', '')],
            $request->except(['sysModules_search', 'page', 'sort'])
        );

        // Paginer les sysModules
        $sysModules_data = $this->sysModuleService->paginate($sysModules_params);

        // Récupérer les statistiques et les champs filtrables
        $sysModules_stats = $this->sysModuleService->getsysModuleStats();
        $sysModules_filters = $this->sysModuleService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('Core::sysModule._table', compact('sysModules_data', 'sysModules_stats', 'sysModules_filters'))->render();
        }

        return view('Core::sysModule.index', compact('sysModules_data', 'sysModules_stats', 'sysModules_filters'));
    }
    public function create() {
        $itemSysModule = $this->sysModuleService->createInstance();
        $sysColors = $this->sysColorService->all();


        if (request()->ajax()) {
            return view('Core::sysModule._fields', compact('itemSysModule', 'sysColors'));
        }
        return view('Core::sysModule.create', compact('itemSysModule', 'sysColors'));
    }
    public function store(SysModuleRequest $request) {
        $validatedData = $request->validated();
        $sysModule = $this->sysModuleService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $sysModule,
                'modelName' => __('Core::sysModule.singular')])
            ]);
        }

        return redirect()->route('sysModules.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysModule,
                'modelName' => __('Core::sysModule.singular')
            ])
        );
    }
    public function show(string $id) {
        $itemSysModule = $this->sysModuleService->find($id);
        $sysColors = $this->sysColorService->all();


        if (request()->ajax()) {
            return view('Core::sysModule._fields', compact('itemSysModule', 'sysColors'));
        }

        return view('Core::sysModule.show', compact('itemSysModule'));

    }
    public function edit(string $id) {

        $itemSysModule = $this->sysModuleService->find($id);
        $sysColors = $this->sysColorService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('sysModule_id', $id);


        if (request()->ajax()) {
            return view('Core::sysModule._fields', compact('itemSysModule', 'sysColors'));
        }

        return view('Core::sysModule.edit', compact('itemSysModule', 'sysColors'));

    }
    public function update(SysModuleRequest $request, string $id) {

        $validatedData = $request->validated();
        $sysModule = $this->sysModuleService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysModule,
                'modelName' =>  __('Core::sysModule.singular')])
            ]);
        }

        return redirect()->route('sysModules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysModule,
                'modelName' =>  __('Core::sysModule.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $sysModule = $this->sysModuleService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModule,
                'modelName' =>  __('Core::sysModule.singular')])
            ]);
        }

        return redirect()->route('sysModules.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModule,
                'modelName' =>  __('Core::sysModule.singular')
                ])
        );

    }

    public function export()
    {
        $sysModules_data = $this->sysModuleService->all();
        return Excel::download(new SysModuleExport($sysModules_data), 'sysModule_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SysModuleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysModules.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysModules.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysModule.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysModules()
    {
        $sysModules = $this->sysModuleService->all();
        return response()->json($sysModules);
    }

}
