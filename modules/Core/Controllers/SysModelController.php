<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysModelRequest;
use Modules\Core\Services\SysModelService;
use Modules\Core\Services\SysColorService;
use Modules\Core\Services\SysModuleService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysModelExport;
use Modules\Core\App\Imports\SysModelImport;

class SysModelController extends AdminController
{
    protected $sysModelService;
    protected $sysColorService;
    protected $sysModuleService;

    public function __construct(SysModelService $sysModelService, SysColorService $sysColorService, SysModuleService $sysModuleService)
    {
        parent::__construct();
        $this->sysModelService = $sysModelService;
        $this->sysColorService = $sysColorService;
        $this->sysModuleService = $sysModuleService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $sysModel_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $sysModels_data = $this->sysModelService->paginate($sysModel_searchQuery);

        if ($request->ajax()) {
            return view('Core::sysModel._table', compact('sysModels_data'))->render();
        }

        return view('Core::sysModel.index', compact('sysModels_data','sysModel_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemSysModel = $this->sysModelService->createInstance();
        $sysColors = $this->sysColorService->all();
        $sysModules = $this->sysModuleService->all();


        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('itemSysModel', 'sysColors', 'sysModules'));
        }
        return view('Core::sysModel.create', compact('itemSysModel', 'sysColors', 'sysModules'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(SysModelRequest $request)
    {
        $validatedData = $request->validated();
        $sysModel = $this->sysModelService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $sysModel,
                'modelName' => __('Core::sysModel.singular')])
            ]);
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysModel,
                'modelName' => __('Core::sysModel.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemSysModel = $this->sysModelService->find($id);
        $sysColors = $this->sysColorService->all();
        $sysModules = $this->sysModuleService->all();


        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('itemSysModel', 'sysColors', 'sysModules'));
        }

        return view('Core::sysModel.show', compact('itemSysModel'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemSysModel = $this->sysModelService->find($id);
        $sysColors = $this->sysColorService->all();
        $sysModules = $this->sysModuleService->all();

        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('itemSysModel', 'sysColors', 'sysModules'));
        }

        return view('Core::sysModel.edit', compact('itemSysModel', 'sysColors', 'sysModules'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(SysModelRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $sysModel = $this->sysModelService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')])
            ]);
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $sysModel = $this->sysModelService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')])
            ]);
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysModel,
                'modelName' =>  __('Core::sysModel.singular')
                ])
        );
    }

    public function export()
    {
        $sysModels_data = $this->sysModelService->all();
        return Excel::download(new SysModelExport($sysModels_data), 'sysModel_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SysModelImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysModels.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysModels.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysModel.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysModels()
    {
        $sysModels = $this->sysModelService->all();
        return response()->json($sysModels);
    }
}
