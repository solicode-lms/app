<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\ModuleRequest;
use Modules\PkgCompetences\Services\ModuleService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\ModuleExport;
use Modules\PkgCompetences\App\Imports\ModuleImport;

class ModuleController extends AdminController
{
    protected $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->moduleService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCompetences::module._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCompetences::module.index', compact('data'));
    }

    public function create()
    {
        $item = $this->moduleService->createInstance();
        return view('PkgCompetences::module.create', compact('item'));
    }

    public function store(ModuleRequest $request)
    {
        $validatedData = $request->validated();
        $module = $this->moduleService->create($validatedData);


        return redirect()->route('modules.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $module,
            'modelName' => __('PkgCompetences::module.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->moduleService->find($id);
        return view('PkgCompetences::module.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->moduleService->find($id);
        return view('PkgCompetences::module.edit', compact('item'));
    }

    public function update(ModuleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $module = $this->moduleService->update($id, $validatedData);



        return redirect()->route('modules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgCompetences::module.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $module = $this->moduleService->destroy($id);
        return redirect()->route('modules.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgCompetences::module.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->moduleService->all();
        return Excel::download(new ModuleExport($data), 'module_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ModuleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('modules.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('modules.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::module.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getModules()
    {
        $modules = $this->moduleService->all();
        return response()->json($modules);
    }
}
