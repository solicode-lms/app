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


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->widgetService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgWidgets::widget._table', compact('data'))->render();
        }

        return view('PkgWidgets::widget.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemWidget = $this->widgetService->createInstance();

        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', compact('itemWidget'));
        }
        return view('PkgWidgets::widget.create', compact('itemWidget'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(WidgetRequest $request)
    {
        $validatedData = $request->validated();
        $widget = $this->widgetService->create($validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $widget,
                'modelName' => __('PkgWidgets::widget.singular')])
            ]);
        }

        return redirect()->route('widgets.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widget,
                'modelName' => __('PkgWidgets::widget.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemWidget = $this->widgetService->find($id);

        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', compact('itemWidget'));
        }

        return view('PkgWidgets::widget.show', compact('itemWidget'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemWidget = $this->widgetService->find($id);

        if (request()->ajax()) {
            return view('PkgWidgets::widget._fields', compact('itemWidget'));
        }

        return view('PkgWidgets::widget.edit', compact('itemWidget'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(WidgetRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $widget = $this->widgetService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')])
            ]);
        }

        return redirect()->route('widgets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $widget = $this->widgetService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widget,
                'modelName' =>  __('PkgWidgets::widget.singular')])
            ]);
        }

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
