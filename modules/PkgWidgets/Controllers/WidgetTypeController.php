<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgWidgets\App\Requests\WidgetTypeRequest;
use Modules\PkgWidgets\Services\WidgetTypeService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\WidgetTypeExport;
use Modules\PkgWidgets\App\Imports\WidgetTypeImport;

class WidgetTypeController extends AdminController
{
    protected $widgetTypeService;

    public function __construct(WidgetTypeService $widgetTypeService)
    {
        parent::__construct();
        $this->widgetTypeService = $widgetTypeService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->widgetTypeService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgWidgets::widgetType._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgWidgets::widgetType.index', compact('data'));
    }

    public function create()
    {
        $item = $this->widgetTypeService->createInstance();
        return view('PkgWidgets::widgetType.create', compact('item'));
    }

    public function store(WidgetTypeRequest $request)
    {
        $validatedData = $request->validated();
        $widgetType = $this->widgetTypeService->create($validatedData);


        return redirect()->route('widgetTypes.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $widgetType,
            'modelName' => __('PkgWidgets::widgetType.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->widgetTypeService->find($id);
        return view('PkgWidgets::widgettype.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->widgetTypeService->find($id);
        return view('PkgWidgets::widgetType.edit', compact('item'));
    }

    public function update(WidgetTypeRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $widgettype = $this->widgetTypeService->update($id, $validatedData);



        return redirect()->route('widgetTypes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgettype,
                'modelName' =>  __('PkgWidgets::widgettype.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $widgettype = $this->widgetTypeService->destroy($id);
        return redirect()->route('widgetTypes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgettype,
                'modelName' =>  __('PkgWidgets::widgettype.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->widgetTypeService->all();
        return Excel::download(new WidgetTypeExport($data), 'widgetType_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new WidgetTypeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('widgetTypes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('widgetTypes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::widgettype.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgetTypes()
    {
        $widgetTypes = $this->widgetTypeService->all();
        return response()->json($widgetTypes);
    }
}
