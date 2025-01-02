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
use Modules\Core\Services\ContextState;

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
        $widgetType_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $widgetTypes_data = $this->widgetTypeService->paginate($widgetType_searchQuery);

        if ($request->ajax()) {
            return view('PkgWidgets::widgetType._table', compact('widgetTypes_data'))->render();
        }

        return view('PkgWidgets::widgetType.index', compact('widgetTypes_data','widgetType_searchQuery'));
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

        return view('PkgWidgets::widgetType.show', compact('itemWidgetType'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemWidgetType = $this->widgetTypeService->find($id);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('widgetType_id', $id);


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
        $widgetType = $this->widgetTypeService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')])
            ]);
        }

        return redirect()->route('widgetTypes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $widgetType = $this->widgetTypeService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')])
            ]);
        }

        return redirect()->route('widgetTypes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetType,
                'modelName' =>  __('PkgWidgets::widgetType.singular')
                ])
        );
    }

    public function export()
    {
        $widgetTypes_data = $this->widgetTypeService->all();
        return Excel::download(new WidgetTypeExport($widgetTypes_data), 'widgetType_export.xlsx');
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
            'modelNames' =>  __('PkgWidgets::widgetType.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgetTypes()
    {
        $widgetTypes = $this->widgetTypeService->all();
        return response()->json($widgetTypes);
    }
}
