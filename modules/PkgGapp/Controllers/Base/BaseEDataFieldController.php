<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EDataFieldService;
use Modules\PkgGapp\Services\EModelService;
use Modules\PkgGapp\Services\EMetadatumService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\EDataFieldRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EDataFieldExport;
use Modules\PkgGapp\App\Imports\EDataFieldImport;
use Modules\Core\Services\ContextState;

class BaseEDataFieldController extends AdminController
{
    protected $eDataFieldService;
    protected $eModelService;

    public function __construct(EDataFieldService $eDataFieldService, EModelService $eModelService) {
        parent::__construct();
        $this->eDataFieldService = $eDataFieldService;
        $this->eModelService = $eModelService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $eDataFields_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('eDataFields_search', '')],
            $request->except(['eDataFields_search', 'page', 'sort'])
        );

        // Paginer les eDataFields
        $eDataFields_data = $this->eDataFieldService->paginate($eDataFields_params);

        // Récupérer les statistiques et les champs filtrables
        $eDataFields_stats = $this->eDataFieldService->geteDataFieldStats();
        $eDataFields_filters = $this->eDataFieldService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::eDataField._table', compact('eDataFields_data', 'eDataFields_stats', 'eDataFields_filters'))->render();
        }

        return view('PkgGapp::eDataField.index', compact('eDataFields_data', 'eDataFields_stats', 'eDataFields_filters'));
    }
    public function create() {
        $itemEDataField = $this->eDataFieldService->createInstance();
        $eModels = $this->eModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eDataField._fields', compact('itemEDataField', 'eModels'));
        }
        return view('PkgGapp::eDataField.create', compact('itemEDataField', 'eModels'));
    }
    public function store(EDataFieldRequest $request) {
        $validatedData = $request->validated();
        $eDataField = $this->eDataFieldService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $eDataField,
                'modelName' => __('PkgGapp::eDataField.singular')])
            ]);
        }

        return redirect()->route('eDataFields.edit',['eDataField' => $eDataField->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eDataField,
                'modelName' => __('PkgGapp::eDataField.singular')
            ])
        );
    }
    public function show(string $id) {
        $itemEDataField = $this->eDataFieldService->find($id);
        $eModels = $this->eModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eDataField._fields', compact('itemEDataField', 'eModels'));
        }

        return view('PkgGapp::eDataField.show', compact('itemEDataField'));

    }
    public function edit(string $id) {

        $itemEDataField = $this->eDataFieldService->find($id);
        $eModels = $this->eModelService->all();
        $eMetadatumService =  new EMetadatumService();
        $eMetadata_data =  $itemEDataField->eMetadata()->paginate(10);
        $eMetadata_stats = $eMetadatumService->geteMetadatumStats();
        $eMetadata_filters = $eMetadatumService->getFieldsFilterable();
        

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('eDataField_id', $id);
        $this->contextState->set('object_id', $id);
        $this->contextState->set('object_type', "Modules\\PkgGapp\\Models\\EDataField");


        if (request()->ajax()) {
            return view('PkgGapp::eDataField._fields', compact('itemEDataField', 'eModels', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters'));
        }

        return view('PkgGapp::eDataField.edit', compact('itemEDataField', 'eModels', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters'));

    }
    public function update(EDataFieldRequest $request, string $id) {

        $validatedData = $request->validated();
        $eDataField = $this->eDataFieldService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')])
            ]);
        }

        return redirect()->route('eDataFields.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $eDataField = $this->eDataFieldService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')])
            ]);
        }

        return redirect()->route('eDataFields.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')
                ])
        );

    }

    public function export()
    {
        $eDataFields_data = $this->eDataFieldService->all();
        return Excel::download(new EDataFieldExport($eDataFields_data), 'eDataField_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new EDataFieldImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eDataFields.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eDataFields.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eDataField.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEDataFields()
    {
        $eDataFields = $this->eDataFieldService->all();
        return response()->json($eDataFields);
    }

}
