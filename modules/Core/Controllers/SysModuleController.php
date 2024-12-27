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


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('searchValue', ''));
        $data = $this->sysModuleService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('Core::sysModule._table', compact('data'))->render();
        }

        return view('Core::sysModule.index', compact('data'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemSysModule = $this->sysModuleService->createInstance();

        if (request()->ajax()) {
            return view('Core::sysModule._fields', compact('itemSysModule'));
        }
        return view('Core::sysModule.create', compact('itemSysModule'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(SysModuleRequest $request)
    {
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

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemSysModule = $this->sysModuleService->find($id);

        if (request()->ajax()) {
            return view('Core::sysmodule._fields', compact('itemSysModule'));
        }

        return view('Core::sysmodule.show', compact('itemSysModule'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemSysModule = $this->sysModuleService->find($id);

        if (request()->ajax()) {
            return view('Core::sysModule._fields', compact('itemSysModule'));
        }

        return view('Core::sysModule.edit', compact('itemSysModule'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(SysModuleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $sysmodule = $this->sysModuleService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysmodule,
                'modelName' =>  __('Core::sysmodule.singular')])
            ]);
        }

        return redirect()->route('sysModules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysmodule,
                'modelName' =>  __('Core::sysmodule.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $sysmodule = $this->sysModuleService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysmodule,
                'modelName' =>  __('Core::sysmodule.singular')])
            ]);
        }

        return redirect()->route('sysModules.index')->with(
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
