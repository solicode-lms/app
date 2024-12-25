<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgWidgets\App\Requests\WidgetOperationRequest;
use Modules\PkgWidgets\Services\WidgetOperationService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\WidgetOperationExport;
use Modules\PkgWidgets\App\Imports\WidgetOperationImport;

class WidgetOperationController extends AdminController
{
    protected $widgetOperationService;

    public function __construct(WidgetOperationService $widgetOperationService)
    {
        parent::__construct();
        $this->widgetOperationService = $widgetOperationService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->widgetOperationService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgWidgets::widgetOperation._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgWidgets::widgetOperation.index', compact('data'));
    }

    public function create()
    {
        $item = $this->widgetOperationService->createInstance();
        return view('PkgWidgets::widgetOperation.create', compact('item'));
    }

    public function store(WidgetOperationRequest $request)
    {
        $validatedData = $request->validated();
        $widgetOperation = $this->widgetOperationService->create($validatedData);


        return redirect()->route('widgetOperations.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $widgetOperation,
            'modelName' => __('PkgWidgets::widgetOperation.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->widgetOperationService->find($id);
        return view('PkgWidgets::widgetoperation.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->widgetOperationService->find($id);
        return view('PkgWidgets::widgetOperation.edit', compact('item'));
    }

    public function update(WidgetOperationRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $widgetoperation = $this->widgetOperationService->update($id, $validatedData);



        return redirect()->route('widgetOperations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetoperation,
                'modelName' =>  __('PkgWidgets::widgetoperation.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $widgetoperation = $this->widgetOperationService->destroy($id);
        return redirect()->route('widgetOperations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetoperation,
                'modelName' =>  __('PkgWidgets::widgetoperation.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->widgetOperationService->all();
        return Excel::download(new WidgetOperationExport($data), 'widgetOperation_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new WidgetOperationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('widgetOperations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('widgetOperations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::widgetoperation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgetOperations()
    {
        $widgetOperations = $this->widgetOperationService->all();
        return response()->json($widgetOperations);
    }
}
