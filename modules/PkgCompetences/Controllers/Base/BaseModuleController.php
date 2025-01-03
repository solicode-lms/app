<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\ModuleRequest;
use Modules\PkgCompetences\Services\ModuleService;
use Modules\PkgCompetences\Services\FiliereService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\ModuleExport;
use Modules\PkgCompetences\App\Imports\ModuleImport;
use Modules\Core\Services\ContextState;

class BaseModuleController extends AdminController
{
    protected $moduleService;
    protected $filiereService;

    public function __construct(ModuleService $moduleService, FiliereService $filiereService)
    {
        parent::__construct();
        $this->moduleService = $moduleService;
        $this->filiereService = $filiereService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $module_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $modules_data = $this->moduleService->paginate($module_searchQuery);

        if ($request->ajax()) {
            return view('PkgCompetences::module._table', compact('modules_data'))->render();
        }

        return view('PkgCompetences::module.index', compact('modules_data','module_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemModule = $this->moduleService->createInstance();
        $filieres = $this->filiereService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::module._fields', compact('itemModule', 'filieres'));
        }
        return view('PkgCompetences::module.create', compact('itemModule', 'filieres'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(ModuleRequest $request)
    {
        $validatedData = $request->validated();
        $module = $this->moduleService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $module,
                'modelName' => __('PkgCompetences::module.singular')])
            ]);
        }

        return redirect()->route('modules.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $module,
                'modelName' => __('PkgCompetences::module.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemModule = $this->moduleService->find($id);
        $filieres = $this->filiereService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::module._fields', compact('itemModule', 'filieres'));
        }

        return view('PkgCompetences::module.show', compact('itemModule'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemModule = $this->moduleService->find($id);
        $filieres = $this->filiereService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('module_id', $id);


        if (request()->ajax()) {
            return view('PkgCompetences::module._fields', compact('itemModule', 'filieres'));
        }

        return view('PkgCompetences::module.edit', compact('itemModule', 'filieres'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(ModuleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $module = $this->moduleService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgCompetences::module.singular')])
            ]);
        }

        return redirect()->route('modules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgCompetences::module.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $module = $this->moduleService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgCompetences::module.singular')])
            ]);
        }

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
        $modules_data = $this->moduleService->all();
        return Excel::download(new ModuleExport($modules_data), 'module_export.xlsx');
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
