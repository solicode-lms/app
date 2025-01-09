<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\DataFieldRequest;
use Modules\PkgGapp\Services\DataFieldService;
use Modules\PkgGapp\Services\FieldTypeService;
use Modules\PkgGapp\Services\IModelService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\DataFieldExport;
use Modules\PkgGapp\App\Imports\DataFieldImport;
use Modules\Core\Services\ContextState;

class BaseDataFieldController extends AdminController
{
    protected $dataFieldService;
    protected $fieldTypeService;
    protected $iModelService;

    public function __construct(DataFieldService $dataFieldService, FieldTypeService $fieldTypeService, IModelService $iModelService)
    {
        parent::__construct();
        $this->dataFieldService = $dataFieldService;
        $this->fieldTypeService = $fieldTypeService;
        $this->iModelService = $iModelService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $dataFields_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('dataFields_search', '')],
            $request->except(['dataFields_search', 'page', 'sort'])
        );
    
        // Paginer les dataFields
        $dataFields_data = $this->dataFieldService->paginate($dataFields_params);
    
        // Récupérer les statistiques et les champs filtrables
        $dataFields_stats = $this->dataFieldService->getdataFieldStats();
        $dataFields_filters = $this->dataFieldService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::dataField._table', compact('dataFields_data', 'dataFields_stats', 'dataFields_filters'))->render();
        }
    
        return view('PkgGapp::dataField.index', compact('dataFields_data', 'dataFields_stats', 'dataFields_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemDataField = $this->dataFieldService->createInstance();
        $fieldTypes = $this->fieldTypeService->all();
        $iModels = $this->iModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::dataField._fields', compact('itemDataField', 'fieldTypes', 'iModels'));
        }
        return view('PkgGapp::dataField.create', compact('itemDataField', 'fieldTypes', 'iModels'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(DataFieldRequest $request)
    {
        $validatedData = $request->validated();
        $dataField = $this->dataFieldService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $dataField,
                'modelName' => __('PkgGapp::dataField.singular')])
            ]);
        }

        return redirect()->route('dataFields.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $dataField,
                'modelName' => __('PkgGapp::dataField.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemDataField = $this->dataFieldService->find($id);
        $fieldTypes = $this->fieldTypeService->all();
        $iModels = $this->iModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::dataField._fields', compact('itemDataField', 'fieldTypes', 'iModels'));
        }

        return view('PkgGapp::dataField.show', compact('itemDataField'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemDataField = $this->dataFieldService->find($id);
        $fieldTypes = $this->fieldTypeService->all();
        $iModels = $this->iModelService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('dataField_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::dataField._fields', compact('itemDataField', 'fieldTypes', 'iModels'));
        }

        return view('PkgGapp::dataField.edit', compact('itemDataField', 'fieldTypes', 'iModels'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(DataFieldRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $dataField = $this->dataFieldService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $dataField,
                'modelName' =>  __('PkgGapp::dataField.singular')])
            ]);
        }

        return redirect()->route('dataFields.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $dataField,
                'modelName' =>  __('PkgGapp::dataField.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $dataField = $this->dataFieldService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $dataField,
                'modelName' =>  __('PkgGapp::dataField.singular')])
            ]);
        }

        return redirect()->route('dataFields.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $dataField,
                'modelName' =>  __('PkgGapp::dataField.singular')
                ])
        );
    }

    public function export()
    {
        $dataFields_data = $this->dataFieldService->all();
        return Excel::download(new DataFieldExport($dataFields_data), 'dataField_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new DataFieldImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('dataFields.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('dataFields.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::dataField.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getDataFields()
    {
        $dataFields = $this->dataFieldService->all();
        return response()->json($dataFields);
    }
}
