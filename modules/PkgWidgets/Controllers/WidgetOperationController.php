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
use Modules\Core\Services\ContextState;

class WidgetOperationController extends AdminController
{
    protected $widgetOperationService;

    public function __construct(WidgetOperationService $widgetOperationService)
    {
        parent::__construct();
        $this->widgetOperationService = $widgetOperationService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $widgetOperation_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $widgetOperations_data = $this->widgetOperationService->paginate($widgetOperation_searchQuery);

        if ($request->ajax()) {
            return view('PkgWidgets::widgetOperation._table', compact('widgetOperations_data'))->render();
        }

        return view('PkgWidgets::widgetOperation.index', compact('widgetOperations_data','widgetOperation_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemWidgetOperation = $this->widgetOperationService->createInstance();


        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._fields', compact('itemWidgetOperation'));
        }
        return view('PkgWidgets::widgetOperation.create', compact('itemWidgetOperation'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(WidgetOperationRequest $request)
    {
        $validatedData = $request->validated();
        $widgetOperation = $this->widgetOperationService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' => __('PkgWidgets::widgetOperation.singular')])
            ]);
        }

        return redirect()->route('widgetOperations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' => __('PkgWidgets::widgetOperation.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemWidgetOperation = $this->widgetOperationService->find($id);


        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._fields', compact('itemWidgetOperation'));
        }

        return view('PkgWidgets::widgetOperation.show', compact('itemWidgetOperation'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemWidgetOperation = $this->widgetOperationService->find($id);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('widgetOperation_id', $id);


        if (request()->ajax()) {
            return view('PkgWidgets::widgetOperation._fields', compact('itemWidgetOperation'));
        }

        return view('PkgWidgets::widgetOperation.edit', compact('itemWidgetOperation'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(WidgetOperationRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $widgetOperation = $this->widgetOperationService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')])
            ]);
        }

        return redirect()->route('widgetOperations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $widgetOperation = $this->widgetOperationService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')])
            ]);
        }

        return redirect()->route('widgetOperations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetOperation,
                'modelName' =>  __('PkgWidgets::widgetOperation.singular')
                ])
        );
    }

    public function export()
    {
        $widgetOperations_data = $this->widgetOperationService->all();
        return Excel::download(new WidgetOperationExport($widgetOperations_data), 'widgetOperation_export.xlsx');
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
            'modelNames' =>  __('PkgWidgets::widgetOperation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgetOperations()
    {
        $widgetOperations = $this->widgetOperationService->all();
        return response()->json($widgetOperations);
    }
}
