<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\FieldTypeRequest;
use Modules\PkgGapp\Services\FieldTypeService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\FieldTypeExport;
use Modules\PkgGapp\App\Imports\FieldTypeImport;
use Modules\Core\Services\ContextState;

class BaseFieldTypeController extends AdminController
{
    protected $fieldTypeService;

    public function __construct(FieldTypeService $fieldTypeService)
    {
        parent::__construct();
        $this->fieldTypeService = $fieldTypeService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $fieldTypes_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('fieldTypes_search', '')],
            $request->except(['fieldTypes_search', 'page', 'sort'])
        );
    
        // Paginer les fieldTypes
        $fieldTypes_data = $this->fieldTypeService->paginate($fieldTypes_params);
    
        // Récupérer les statistiques et les champs filtrables
        $fieldTypes_stats = $this->fieldTypeService->getfieldTypeStats();
        $fieldTypes_filters = $this->fieldTypeService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::fieldType._table', compact('fieldTypes_data', 'fieldTypes_stats', 'fieldTypes_filters'))->render();
        }
    
        return view('PkgGapp::fieldType.index', compact('fieldTypes_data', 'fieldTypes_stats', 'fieldTypes_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemFieldType = $this->fieldTypeService->createInstance();


        if (request()->ajax()) {
            return view('PkgGapp::fieldType._fields', compact('itemFieldType'));
        }
        return view('PkgGapp::fieldType.create', compact('itemFieldType'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(FieldTypeRequest $request)
    {
        $validatedData = $request->validated();
        $fieldType = $this->fieldTypeService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $fieldType,
                'modelName' => __('PkgGapp::fieldType.singular')])
            ]);
        }

        return redirect()->route('fieldTypes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $fieldType,
                'modelName' => __('PkgGapp::fieldType.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemFieldType = $this->fieldTypeService->find($id);


        if (request()->ajax()) {
            return view('PkgGapp::fieldType._fields', compact('itemFieldType'));
        }

        return view('PkgGapp::fieldType.show', compact('itemFieldType'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemFieldType = $this->fieldTypeService->find($id);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('fieldType_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::fieldType._fields', compact('itemFieldType'));
        }

        return view('PkgGapp::fieldType.edit', compact('itemFieldType'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(FieldTypeRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $fieldType = $this->fieldTypeService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $fieldType,
                'modelName' =>  __('PkgGapp::fieldType.singular')])
            ]);
        }

        return redirect()->route('fieldTypes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $fieldType,
                'modelName' =>  __('PkgGapp::fieldType.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $fieldType = $this->fieldTypeService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $fieldType,
                'modelName' =>  __('PkgGapp::fieldType.singular')])
            ]);
        }

        return redirect()->route('fieldTypes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $fieldType,
                'modelName' =>  __('PkgGapp::fieldType.singular')
                ])
        );
    }

    public function export()
    {
        $fieldTypes_data = $this->fieldTypeService->all();
        return Excel::download(new FieldTypeExport($fieldTypes_data), 'fieldType_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new FieldTypeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('fieldTypes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('fieldTypes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::fieldType.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFieldTypes()
    {
        $fieldTypes = $this->fieldTypeService->all();
        return response()->json($fieldTypes);
    }
}
