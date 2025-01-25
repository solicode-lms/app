<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysColorService;
use Modules\Core\Services\SysModelService;
use Modules\Core\Services\SysModuleService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysColorRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysColorExport;
use Modules\Core\App\Imports\SysColorImport;
use Modules\Core\Services\ContextState;

class BaseSysColorController extends AdminController
{
    protected $sysColorService;

    public function __construct(SysColorService $sysColorService) {
        parent::__construct();
        $this->sysColorService = $sysColorService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $sysColors_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('sysColors_search', '')],
            $request->except(['sysColors_search', 'page', 'sort'])
        );

        // Paginer les sysColors
        $sysColors_data = $this->sysColorService->paginate($sysColors_params);

        // Récupérer les statistiques et les champs filtrables
        $sysColors_stats = $this->sysColorService->getsysColorStats();
        $sysColors_filters = $this->sysColorService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('Core::sysColor._table', compact('sysColors_data', 'sysColors_stats', 'sysColors_filters'))->render();
        }

        return view('Core::sysColor.index', compact('sysColors_data', 'sysColors_stats', 'sysColors_filters'));
    }
    public function create() {
        $itemSysColor = $this->sysColorService->createInstance();


        if (request()->ajax()) {
            return view('Core::sysColor._fields', compact('itemSysColor'));
        }
        return view('Core::sysColor.create', compact('itemSysColor'));
    }
    public function store(SysColorRequest $request) {
        $validatedData = $request->validated();
        $sysColor = $this->sysColorService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $sysColor,
                'modelName' => __('Core::sysColor.singular')])
            ]);
        }

        return redirect()->route('sysColors.edit',['sysColor' => $sysColor->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysColor,
                'modelName' => __('Core::sysColor.singular')
            ])
        );
    }
    public function show(string $id) {
        $itemSysColor = $this->sysColorService->find($id);


        if (request()->ajax()) {
            return view('Core::sysColor._fields', compact('itemSysColor'));
        }

        return view('Core::sysColor.show', compact('itemSysColor'));

    }
    public function edit(string $id) {

        $itemSysColor = $this->sysColorService->find($id);
        $sysModelService =  new SysModelService();
        $sysModels_data =  $itemSysColor->sysModels()->paginate(10);
        $sysModels_stats = $sysModelService->getsysModelStats();
        $sysModels_filters = $sysModelService->getFieldsFilterable();
        
        $sysModuleService =  new SysModuleService();
        $sysModules_data =  $itemSysColor->sysModules()->paginate(10);
        $sysModules_stats = $sysModuleService->getsysModuleStats();
        $sysModules_filters = $sysModuleService->getFieldsFilterable();
        

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('sysColor_id', $id);


        if (request()->ajax()) {
            return view('Core::sysColor._fields', compact('itemSysColor', 'sysModels_data', 'sysModules_data', 'sysModels_stats', 'sysModules_stats', 'sysModels_filters', 'sysModules_filters'));
        }

        return view('Core::sysColor.edit', compact('itemSysColor', 'sysModels_data', 'sysModules_data', 'sysModels_stats', 'sysModules_stats', 'sysModels_filters', 'sysModules_filters'));

    }
    public function update(SysColorRequest $request, string $id) {

        $validatedData = $request->validated();
        $sysColor = $this->sysColorService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')])
            ]);
        }

        return redirect()->route('sysColors.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $sysColor = $this->sysColorService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')])
            ]);
        }

        return redirect()->route('sysColors.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')
                ])
        );

    }

    public function export()
    {
        $sysColors_data = $this->sysColorService->all();
        return Excel::download(new SysColorExport($sysColors_data), 'sysColor_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SysColorImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysColors.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysColors.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysColor.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysColors()
    {
        $sysColors = $this->sysColorService->all();
        return response()->json($sysColors);
    }

}
