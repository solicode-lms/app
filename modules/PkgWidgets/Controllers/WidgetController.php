<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgWidgets\App\Requests\WidgetRequest;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\WidgetExport;
use Modules\PkgWidgets\App\Imports\WidgetImport;

class WidgetController extends AdminController
{
    protected $widgetService;

    public function __construct(WidgetService $widgetService)
    {
        parent::__construct();
        $this->widgetService = $widgetService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->widgetService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgWidgets::widget._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgWidgets::widget.index', compact('data'));
    }

    public function create()
    {
        $item = $this->widgetService->createInstance();
        return view('PkgWidgets::widget.create', compact('item'));
    }

    public function store(WidgetRequest $request)
    {
        $validatedData = $request->validated();
        $widget = $this->widgetService->create($validatedData);


        return redirect()->route('widgets.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $widget,
            'modelName' => __('PkgWidgets::widget.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->widgetService->find($id);
        return view('PkgWidgets::widget.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->widgetService->find($id);
        return view('PkgWidgets::widget.edit', compact('item'));
    }

    public function update(WidgetRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $widget = $this->widgetService->update($id, $validatedData);



        return redirect()->route('widgets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $widget = $this->widgetService->destroy($id);
        return redirect()->route('widgets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->widgetService->all();
        return Excel::download(new WidgetExport($data), 'widget_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new WidgetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('widgets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('widgets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::widget.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgets()
    {
        $widgets = $this->widgetService->all();
        return response()->json($widgets);
    }
}
