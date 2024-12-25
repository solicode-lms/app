<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysControllerRequest;
use Modules\Core\Services\SysControllerService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysControllerExport;
use Modules\Core\App\Imports\SysControllerImport;

class SysControllerController extends AdminController
{
    protected $sysControllerService;

    public function __construct(SysControllerService $sysControllerService)
    {
        parent::__construct();
        $this->sysControllerService = $sysControllerService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->sysControllerService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('Core::sysController._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('Core::sysController.index', compact('data'));
    }

    public function create()
    {
        $item = $this->sysControllerService->createInstance();
        return view('Core::sysController.create', compact('item'));
    }

    public function store(SysControllerRequest $request)
    {
        $validatedData = $request->validated();
        $sysController = $this->sysControllerService->create($validatedData);


        return redirect()->route('sysControllers.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $sysController,
            'modelName' => __('Core::sysController.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->sysControllerService->find($id);
        return view('Core::syscontroller.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->sysControllerService->find($id);
        return view('Core::sysController.edit', compact('item'));
    }

    public function update(SysControllerRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $syscontroller = $this->sysControllerService->update($id, $validatedData);



        return redirect()->route('sysControllers.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $syscontroller,
                'modelName' =>  __('Core::syscontroller.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $syscontroller = $this->sysControllerService->destroy($id);
        return redirect()->route('sysControllers.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $syscontroller,
                'modelName' =>  __('Core::syscontroller.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->sysControllerService->all();
        return Excel::download(new SysControllerExport($data), 'sysController_export.xlsx');
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
            'modelNames' =>  __('Core::syscontroller.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysControllers()
    {
        $sysControllers = $this->sysControllerService->all();
        return response()->json($sysControllers);
    }
}
