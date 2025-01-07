<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysControllerRequest;
use Modules\Core\Services\SysControllerService;
use Modules\Core\Services\SysModuleService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysControllerExport;
use Modules\Core\App\Imports\SysControllerImport;
use Modules\Core\Services\ContextState;

class BaseSysControllerController extends AdminController
{
    protected $sysControllerService;
    protected $sysModuleService;

    public function __construct(SysControllerService $sysControllerService, SysModuleService $sysModuleService)
    {
        parent::__construct();
        $this->sysControllerService = $sysControllerService;
        $this->sysModuleService = $sysModuleService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $sysControllers_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('sysControllers_search', '')],
            $request->except(['sysControllers_search', 'page', 'sort'])
        );
    
        // Paginer les sysControllers
        $sysControllers_data = $this->sysControllerService->paginate($sysControllers_params);
    
        // Récupérer les statistiques et les champs filtrables
        $sysControllers_stats = $this->sysControllerService->getsysControllerStats();
        $sysControllers_filters = $this->sysControllerService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('Core::sysController._table', compact('sysControllers_data', 'sysControllers_stats', 'sysControllers_filters'))->render();
        }
    
        return view('Core::sysController.index', compact('sysControllers_data', 'sysControllers_stats', 'sysControllers_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemSysController = $this->sysControllerService->createInstance();
        $sysModules = $this->sysModuleService->all();


        if (request()->ajax()) {
            return view('Core::sysController._fields', compact('itemSysController', 'sysModules'));
        }
        return view('Core::sysController.create', compact('itemSysController', 'sysModules'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(SysControllerRequest $request)
    {
        $validatedData = $request->validated();
        $sysController = $this->sysControllerService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $sysController,
                'modelName' => __('Core::sysController.singular')])
            ]);
        }

        return redirect()->route('sysControllers.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysController,
                'modelName' => __('Core::sysController.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemSysController = $this->sysControllerService->find($id);
        $sysModules = $this->sysModuleService->all();


        if (request()->ajax()) {
            return view('Core::sysController._fields', compact('itemSysController', 'sysModules'));
        }

        return view('Core::sysController.show', compact('itemSysController'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemSysController = $this->sysControllerService->find($id);
        $sysModules = $this->sysModuleService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('sysController_id', $id);


        if (request()->ajax()) {
            return view('Core::sysController._fields', compact('itemSysController', 'sysModules'));
        }

        return view('Core::sysController.edit', compact('itemSysController', 'sysModules'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(SysControllerRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $sysController = $this->sysControllerService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')])
            ]);
        }

        return redirect()->route('sysControllers.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $sysController = $this->sysControllerService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')])
            ]);
        }

        return redirect()->route('sysControllers.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysController,
                'modelName' =>  __('Core::sysController.singular')
                ])
        );
    }

    public function export()
    {
        $sysControllers_data = $this->sysControllerService->all();
        return Excel::download(new SysControllerExport($sysControllers_data), 'sysController_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SysControllerImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysControllers.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysControllers.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysController.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysControllers()
    {
        $sysControllers = $this->sysControllerService->all();
        return response()->json($sysControllers);
    }
}
