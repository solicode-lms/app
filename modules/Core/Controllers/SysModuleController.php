<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysModuleRequest;
use Modules\Core\Services\SysModuleService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysModuleExport;
use Modules\Core\App\Imports\SysModuleImport;

class SysModuleController extends AdminController
{
    protected $sysModuleService;

    public function __construct(SysModuleService $sysModuleService)
    {
        parent::__construct();
        $this->sysModuleService = $sysModuleService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->sysModuleService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('Core::sysModule._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('Core::sysModule.index', compact('data'));
    }

    public function create()
    {
        $item = $this->sysModuleService->createInstance();
        return view('Core::sysModule.create', compact('item'));
    }

    public function store(SysModuleRequest $request)
    {
        $validatedData = $request->validated();
        $sysModule = $this->sysModuleService->create($validatedData);


        return redirect()->route('sysModules.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $sysModule,
            'modelName' => __('Core::sysModule.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->sysModuleService->find($id);
        return view('Core::sysmodule.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->sysModuleService->find($id);
        return view('Core::sysModule.edit', compact('item'));
    }

    public function update(SysModuleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $sysmodule = $this->sysModuleService->update($id, $validatedData);



        return redirect()->route('sysmodules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysmodule,
                'modelName' =>  __('Core::sysmodule.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $sysmodule = $this->sysModuleService->destroy($id);
        return redirect()->route('sysmodules.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysmodule,
                'modelName' =>  __('Core::sysmodule.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->sysModuleService->all();
        return Excel::download(new SysModuleExport($data), 'sysModule_export.xlsx');
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
            'modelNames' =>  __('Core::sysmodule.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysModules()
    {
        $sysModules = $this->sysModuleService->all();
        return response()->json($sysModules);
    }
}
