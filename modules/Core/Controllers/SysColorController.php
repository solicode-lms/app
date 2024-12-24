<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysColorRequest;
use Modules\Core\Services\SysColorService;
use Modules\Core\Services\SysModuleService;
use Modules\Core\Services\SysModelService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysColorExport;
use Modules\Core\App\Imports\SysColorImport;

class SysColorController extends AdminController
{
    protected $sysColorService;
    protected $sysModuleService;
    protected $sysModelService;

    public function __construct(SysColorService $sysColorService, SysModuleService $sysModuleService, SysModelService $sysModelService)
    {
        parent::__construct();
        $this->sysColorService = $sysColorService;
        $this->sysModuleService = $sysModuleService;
        $this->sysModelService = $sysModelService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->sysColorService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('Core::sysColor._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('Core::sysColor.index', compact('data'));
    }

    public function create()
    {
        $item = $this->sysColorService->createInstance();
        $sysModules = $this->sysModuleService->all();
        $sysModels = $this->sysModelService->all();
        return view('Core::sysColor.create', compact('item', 'sysModules', 'sysModels'));
    }

    public function store(SysColorRequest $request)
    {
        $validatedData = $request->validated();
        $sysColor = $this->sysColorService->create($validatedData);

        if ($request->has('sysmodules')) {
            $sysColor->sysmodules()->sync($request->input('sysmodules'));
        }
        if ($request->has('sysmodels')) {
            $sysColor->sysmodels()->sync($request->input('sysmodels'));
        }

        return redirect()->route('sysColors.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $sysColor,
            'modelName' => __('Core::sysColor.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->sysColorService->find($id);
        return view('Core::syscolor.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->sysColorService->find($id);
        $sysModules = $this->sysModuleService->all();
        $sysModels = $this->sysModelService->all();
        return view('Core::sysColor.edit', compact('item', 'sysModules', 'sysModels'));
    }

    public function update(SysColorRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $syscolor = $this->sysColorService->update($id, $validatedData);


        if ($request->has('sysmodules')) {
            $sysColor->sysmodules()->sync($request->input('sysmodules'));
        }
        if ($request->has('sysmodels')) {
            $sysColor->sysmodels()->sync($request->input('sysmodels'));
        }

        return redirect()->route('syscolors.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $syscolor,
                'modelName' =>  __('Core::syscolor.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $syscolor = $this->sysColorService->destroy($id);
        return redirect()->route('syscolors.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $syscolor,
                'modelName' =>  __('Core::syscolor.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->sysColorService->all();
        return Excel::download(new SysColorExport($data), 'sysColor_export.xlsx');
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
            'modelNames' =>  __('Core::syscolor.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysColors()
    {
        $sysColors = $this->sysColorService->all();
        return response()->json($sysColors);
    }
}
