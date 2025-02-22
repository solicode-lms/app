<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EModelService;
use Modules\PkgGapp\Services\EPackageService;
use Modules\PkgGapp\Services\EDataFieldService;
use Modules\PkgGapp\Services\EMetadatumService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EModelRequest;
use Modules\PkgGapp\Models\EModel;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EModelExport;
use Modules\PkgGapp\App\Imports\EModelImport;
use Modules\Core\Services\ContextState;

class BaseEModelController extends AdminController
{
    protected $eModelService;
    protected $ePackageService;

    public function __construct(EModelService $eModelService, EPackageService $ePackageService) {
        parent::__construct();
        $this->eModelService = $eModelService;
        $this->ePackageService = $ePackageService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('eModel.index');

        // Extraire les paramètres de recherche, page, et filtres
        $eModels_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('eModels_search', $this->viewState->get("filter.eModel.eModels_search"))],
            $request->except(['eModels_search', 'page', 'sort'])
        );

        // Paginer les eModels
        $eModels_data = $this->eModelService->paginate($eModels_params);

        // Récupérer les statistiques et les champs filtrables
        $eModels_stats = $this->eModelService->geteModelStats();
        $eModels_filters = $this->eModelService->getFieldsFilterable();
        $eModel_instance =  $this->eModelService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::eModel._table', compact('eModels_data', 'eModels_stats', 'eModels_filters','eModel_instance'))->render();
        }

        return view('PkgGapp::eModel.index', compact('eModels_data', 'eModels_stats', 'eModels_filters','eModel_instance'));
    }
    public function create() {
        $itemEModel = $this->eModelService->createInstance();
        
        $ePackages = $this->ePackageService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eModel._fields', compact('itemEModel', 'ePackages'));
        }
        return view('PkgGapp::eModel.create', compact('itemEModel', 'ePackages'));
    }
    public function store(EModelRequest $request) {
        $validatedData = $request->validated();
        $eModel = $this->eModelService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $eModel,
                'modelName' => __('PkgGapp::eModel.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $eModel->id]
            );
        }

        return redirect()->route('eModels.edit',['eModel' => $eModel->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eModel,
                'modelName' => __('PkgGapp::eModel.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('eModel.edit_' . $id);
     
        $itemEModel = $this->eModelService->find($id);
  
        $ePackages = $this->ePackageService->all();

        $this->viewState->set('scope.eDataField.e_model_id', $id);
        $eDataFieldService =  new EDataFieldService();
        $eDataFields_data =  $itemEModel->eDataFields()->paginate(10);
        $eDataFields_stats = $eDataFieldService->geteDataFieldStats();
        $eDataFields_filters = $eDataFieldService->getFieldsFilterable();
        $eDataField_instance =  $eDataFieldService->createInstance();
        $this->viewState->set('scope.eMetadatum.e_model_id', $id);
        $eMetadatumService =  new EMetadatumService();
        $eMetadata_data =  $itemEModel->eMetadata()->paginate(10);
        $eMetadata_stats = $eMetadatumService->geteMetadatumStats();
        $eMetadata_filters = $eMetadatumService->getFieldsFilterable();
        $eMetadatum_instance =  $eMetadatumService->createInstance();

        if (request()->ajax()) {
            return view('PkgGapp::eModel._edit', compact('itemEModel', 'ePackages', 'eDataFields_data', 'eMetadata_data', 'eDataFields_stats', 'eMetadata_stats', 'eDataFields_filters', 'eMetadata_filters', 'eDataField_instance', 'eMetadatum_instance'));
        }

        return view('PkgGapp::eModel.edit', compact('itemEModel', 'ePackages', 'eDataFields_data', 'eMetadata_data', 'eDataFields_stats', 'eMetadata_stats', 'eDataFields_filters', 'eMetadata_filters', 'eDataField_instance', 'eMetadatum_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('eModel.edit_' . $id);

        $itemEModel = $this->eModelService->find($id);

        $ePackages = $this->ePackageService->all();


        $this->viewState->set('scope.eDataField.e_model_id', $id);
        $eDataFieldService =  new EDataFieldService();
        $eDataFields_data =  $itemEModel->eDataFields()->paginate(10);
        $eDataFields_stats = $eDataFieldService->geteDataFieldStats();
        $eDataFields_filters = $eDataFieldService->getFieldsFilterable();
        $eDataField_instance =  $eDataFieldService->createInstance();

        $this->viewState->set('scope.eMetadatum.e_model_id', $id);
        $eMetadatumService =  new EMetadatumService();
        $eMetadata_data =  $itemEModel->eMetadata()->paginate(10);
        $eMetadata_stats = $eMetadatumService->geteMetadatumStats();
        $eMetadata_filters = $eMetadatumService->getFieldsFilterable();
        $eMetadatum_instance =  $eMetadatumService->createInstance();

        if (request()->ajax()) {
            return view('PkgGapp::eModel._edit', compact('itemEModel', 'ePackages', 'eDataFields_data', 'eMetadata_data', 'eDataFields_stats', 'eMetadata_stats', 'eDataFields_filters', 'eMetadata_filters', 'eDataField_instance', 'eMetadatum_instance'));
        }

        return view('PkgGapp::eModel.edit', compact('itemEModel', 'ePackages', 'eDataFields_data', 'eMetadata_data', 'eDataFields_stats', 'eMetadata_stats', 'eDataFields_filters', 'eMetadata_filters', 'eDataField_instance', 'eMetadatum_instance'));

    }
    public function update(EModelRequest $request, string $id) {

        $validatedData = $request->validated();
        $eModel = $this->eModelService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $eModel->id]
            );
        }

        return redirect()->route('eModels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $eModel = $this->eModelService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('eModels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')
                ])
        );

    }

    public function export($format)
    {
        $eModels_data = $this->eModelService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EModelExport($eModels_data,'csv'), 'eModel_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EModelExport($eModels_data,'xlsx'), 'eModel_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EModelImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eModels.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eModels.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eModel.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEModels()
    {
        $eModels = $this->eModelService->all();
        return response()->json($eModels);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $eModel = $this->eModelService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEModel = $this->eModelService->dataCalcul($eModel);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEModel
        ]);
    }
    

}
