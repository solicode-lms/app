<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysModelRequest;
use Modules\Core\Services\SysModelService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysModelExport;
use Modules\Core\App\Imports\SysModelImport;

class SysModelController extends AdminController
{
    protected $sysModelService;

    public function __construct(SysModelService $sysModelService)
    {
        parent::__construct();
        $this->sysModelService = $sysModelService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('searchValue', ''));
        $data = $this->sysModelService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('Core::sysModel._table', compact('data'))->render();
        }

        return view('Core::sysModel.index', compact('data'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemSysModel = $this->sysModelService->createInstance();

        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('itemSysModel'));
        }
        return view('Core::sysModel.create', compact('itemSysModel'));
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

        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('itemSysModel'));
        }

        return view('Core::sysmodel.show', compact('itemSysModel'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemSysModel = $this->sysModelService->find($id);

        if (request()->ajax()) {
            return view('Core::sysModel._fields', compact('itemSysModel'));
        }

        return view('Core::sysModel.edit', compact('itemSysModel'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(SysModelRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $sysmodel = $this->sysModelService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysmodel,
                'modelName' =>  __('Core::sysmodel.singular')])
            ]);
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysmodel,
                'modelName' =>  __('Core::sysmodel.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $sysmodel = $this->sysModelService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysmodel,
                'modelName' =>  __('Core::sysmodel.singular')])
            ]);
        }

        return redirect()->route('sysModels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysmodel,
                'modelName' =>  __('Core::sysmodel.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->sysModelService->all();
        return Excel::download(new SysModelExport($data), 'sysModel_export.xlsx');
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
            'modelNames' =>  __('Core::sysmodel.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysModels()
    {
        $sysModels = $this->sysModelService->all();
        return response()->json($sysModels);
    }
}
