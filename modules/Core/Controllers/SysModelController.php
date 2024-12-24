<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysModelRequest;
use Modules\Core\Services\SysModelService;
use Modules\default\Services\SysColorService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysModelExport;
use Modules\Core\App\Imports\SysModelImport;

class SysModelController extends AdminController
{
    protected $sysModelService;
    protected $sysColorService;

    public function __construct(SysModelService $sysModelService, SysColorService $sysColorService)
    {
        parent::__construct();
        $this->sysModelService = $sysModelService;
        $this->sysColorService = $sysColorService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->sysModelService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('Core::sysModel._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('Core::sysModel.index', compact('data'));
    }

    public function create()
    {
        $item = $this->sysModelService->createInstance();
        $sysColors = $this->sysColorService->all();
        return view('Core::sysModel.create', compact('item', 'sysColors'));
    }

    public function store(SysModelRequest $request)
    {
        $validatedData = $request->validated();
        $sysModel = $this->sysModelService->create($validatedData);

        if ($request->has('syscolors')) {
            $sysModel->syscolors()->sync($request->input('syscolors'));
        }

        return redirect()->route('sysModels.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $sysModel,
            'modelName' => __('Core::sysModel.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->sysModelService->find($id);
        return view('Core::sysmodel.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->sysModelService->find($id);
        $sysColors = $this->sysColorService->all();
        return view('Core::sysModel.edit', compact('item', 'sysColors'));
    }

    public function update(SysModelRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $sysmodel = $this->sysModelService->update($id, $validatedData);


        if ($request->has('syscolors')) {
            $sysModel->syscolors()->sync($request->input('syscolors'));
        }

        return redirect()->route('sysmodels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysmodel,
                'modelName' =>  __('Core::sysmodel.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $sysmodel = $this->sysModelService->destroy($id);
        return redirect()->route('sysmodels.index')->with(
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
