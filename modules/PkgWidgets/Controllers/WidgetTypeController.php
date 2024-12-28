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


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('searchValue', ''));
        $data = $this->widgetTypeService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgWidgets::widgetType._table', compact('data'))->render();
        }

        return view('PkgWidgets::widgetType.index', compact('data'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemWidgetType = $this->widgetTypeService->createInstance();

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._fields', compact('itemWidgetType'));
        }
        return view('PkgWidgets::widgetType.create', compact('itemWidgetType'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(WidgetTypeRequest $request)
    {
        $validatedData = $request->validated();
        $widgetType = $this->widgetTypeService->create($validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $widgetType,
                'modelName' => __('PkgWidgets::widgetType.singular')])
            ]);
        }

        return redirect()->route('widgetTypes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widgetType,
                'modelName' => __('PkgWidgets::widgetType.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemWidgetType = $this->widgetTypeService->find($id);

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._fields', compact('itemWidgetType'));
        }

        return view('PkgWidgets::widgettype.show', compact('itemWidgetType'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemWidgetType = $this->widgetTypeService->find($id);

        if (request()->ajax()) {
            return view('PkgWidgets::widgetType._fields', compact('itemWidgetType'));
        }

        return view('PkgWidgets::widgetType.edit', compact('itemWidgetType'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(WidgetTypeRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $widgettype = $this->widgetTypeService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgettype,
                'modelName' =>  __('PkgWidgets::widgettype.singular')])
            ]);
        }

        return redirect()->route('widgetTypes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgettype,
                'modelName' =>  __('PkgWidgets::widgettype.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $widgettype = $this->widgetTypeService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgettype,
                'modelName' =>  __('PkgWidgets::widgettype.singular')])
            ]);
        }

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
