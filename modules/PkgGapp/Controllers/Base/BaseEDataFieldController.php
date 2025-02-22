<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EDataFieldService;
use Modules\PkgGapp\Services\EModelService;
use Modules\PkgGapp\Services\ERelationshipService;
use Modules\PkgGapp\Services\EMetadatumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EDataFieldRequest;
use Modules\PkgGapp\Models\EDataField;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EDataFieldExport;
use Modules\PkgGapp\App\Imports\EDataFieldImport;
use Modules\Core\Services\ContextState;

class BaseEDataFieldController extends AdminController
{
    protected $eDataFieldService;
    protected $eModelService;
    protected $eRelationshipService;

    public function __construct(EDataFieldService $eDataFieldService, EModelService $eModelService, ERelationshipService $eRelationshipService) {
        parent::__construct();
        $this->eDataFieldService = $eDataFieldService;
        $this->eModelService = $eModelService;
        $this->eRelationshipService = $eRelationshipService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('eDataField.index');


        // Extraire les paramètres de recherche, page, et filtres
        $eDataFields_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('eDataFields_search', $this->viewState->get("filter.eDataField.eDataFields_search"))],
            $request->except(['eDataFields_search', 'page', 'sort'])
        );

        // Paginer les eDataFields
        $eDataFields_data = $this->eDataFieldService->paginate($eDataFields_params);

        // Récupérer les statistiques et les champs filtrables
        $eDataFields_stats = $this->eDataFieldService->geteDataFieldStats();
        $eDataFields_filters = $this->eDataFieldService->getFieldsFilterable();
        $eDataField_instance =  $this->eDataFieldService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::eDataField._table', compact('eDataFields_data', 'eDataFields_stats', 'eDataFields_filters','eDataField_instance'))->render();
        }

        return view('PkgGapp::eDataField.index', compact('eDataFields_data', 'eDataFields_stats', 'eDataFields_filters','eDataField_instance'));
    }
    public function create() {


        $itemEDataField = $this->eDataFieldService->createInstance();
        
        $eModels = $this->eModelService->all();
        $eRelationships = $this->eRelationshipService->all();

        if (request()->ajax()) {
            return view('PkgGapp::eDataField._fields', compact('itemEDataField', 'eModels', 'eRelationships'));
        }
        return view('PkgGapp::eDataField.create', compact('itemEDataField', 'eModels', 'eRelationships'));
    }
    public function store(EDataFieldRequest $request) {
        $validatedData = $request->validated();
        $eDataField = $this->eDataFieldService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $eDataField,
                'modelName' => __('PkgGapp::eDataField.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $eDataField->id]
            );
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

        $this->viewState->setContextKey('eDataField.edit_' . $id);

        $itemEDataField = $this->eDataFieldService->find($id);

        $eModels = $this->eModelService->all();
        $eRelationships = $this->eRelationshipService->all();


        $this->viewState->set('scope.eMetadatum.e_data_field_id', $id);
        $eMetadatumService =  new EMetadatumService();
        $eMetadata_data =  $itemEDataField->eMetadata()->paginate(10);
        $eMetadata_stats = $eMetadatumService->geteMetadatumStats();
        $eMetadata_filters = $eMetadatumService->getFieldsFilterable();
        $eMetadatum_instance =  $eMetadatumService->createInstance();

        if (request()->ajax()) {
            return view('PkgGapp::eDataField._edit', compact('itemEDataField', 'eModels', 'eRelationships', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters', 'eMetadatum_instance'));
        }

        return view('PkgGapp::eDataField.edit', compact('itemEDataField', 'eModels', 'eRelationships', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters', 'eMetadatum_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('eDataField.edit_' . $id);

        $itemEDataField = $this->eDataFieldService->find($id);

        $eModels = $this->eModelService->all();
        $eRelationships = $this->eRelationshipService->all();


        $this->viewState->set('scope.eMetadatum.e_data_field_id', $id);
        $eMetadatumService =  new EMetadatumService();
        $eMetadata_data =  $itemEDataField->eMetadata()->paginate(10);
        $eMetadata_stats = $eMetadatumService->geteMetadatumStats();
        $eMetadata_filters = $eMetadatumService->getFieldsFilterable();
        $eMetadatum_instance =  $eMetadatumService->createInstance();

        if (request()->ajax()) {
            return view('PkgGapp::eDataField._edit', compact('itemEDataField', 'eModels', 'eRelationships', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters', 'eMetadatum_instance'));
        }

        return view('PkgGapp::eDataField.edit', compact('itemEDataField', 'eModels', 'eRelationships', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters', 'eMetadatum_instance'));

    }
    public function update(EDataFieldRequest $request, string $id) {

        $validatedData = $request->validated();
        $eDataField = $this->eDataFieldService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $eDataField->id]
            );
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
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('eDataFields.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eDataField,
                'modelName' =>  __('PkgGapp::eDataField.singular')
                ])
        );

    }

    public function export($format)
    {
        $eDataFields_data = $this->eDataFieldService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EDataFieldExport($eDataFields_data,'csv'), 'eDataField_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EDataFieldExport($eDataFields_data,'xlsx'), 'eDataField_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $eDataField = $this->eDataFieldService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEDataField = $this->eDataFieldService->dataCalcul($eDataField);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEDataField
        ]);
    }
    

}
