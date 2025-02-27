<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EMetadatumService;
use Modules\PkgGapp\Services\EDataFieldService;
use Modules\PkgGapp\Services\EMetadataDefinitionService;
use Modules\PkgGapp\Services\EModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EMetadatumRequest;
use Modules\PkgGapp\Models\EMetadatum;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EMetadatumExport;
use Modules\PkgGapp\App\Imports\EMetadatumImport;
use Modules\Core\Services\ContextState;

class BaseEMetadatumController extends AdminController
{
    protected $eMetadatumService;
    protected $eDataFieldService;
    protected $eMetadataDefinitionService;
    protected $eModelService;

    public function __construct(EMetadatumService $eMetadatumService, EDataFieldService $eDataFieldService, EMetadataDefinitionService $eMetadataDefinitionService, EModelService $eModelService) {
        parent::__construct();
        $this->eMetadatumService = $eMetadatumService;
        $this->eDataFieldService = $eDataFieldService;
        $this->eMetadataDefinitionService = $eMetadataDefinitionService;
        $this->eModelService = $eModelService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('eMetadatum.index');


        // Extraire les paramètres de recherche, page, et filtres
        $eMetadata_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('eMetadata_search', $this->viewState->get("filter.eMetadatum.eMetadata_search"))],
            $request->except(['eMetadata_search', 'page', 'sort'])
        );

        // Paginer les eMetadata
        $eMetadata_data = $this->eMetadatumService->paginate($eMetadata_params);

        // Récupérer les statistiques et les champs filtrables
        $eMetadata_stats = $this->eMetadatumService->geteMetadatumStats();
        $this->viewState->set('stats.eMetadatum.stats'  , $eMetadata_stats);
        $eMetadata_filters = $this->eMetadatumService->getFieldsFilterable();
        $eMetadatum_instance =  $this->eMetadatumService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::eMetadatum._table', compact('eMetadata_data', 'eMetadata_stats', 'eMetadata_filters','eMetadatum_instance'))->render();
        }

        return view('PkgGapp::eMetadatum.index', compact('eMetadata_data', 'eMetadata_stats', 'eMetadata_filters','eMetadatum_instance'));
    }
    public function create() {


        $itemEMetadatum = $this->eMetadatumService->createInstance();
        

        $eModels = $this->eModelService->all();
        $eDataFields = $this->eDataFieldService->all();
        $eMetadataDefinitions = $this->eMetadataDefinitionService->all();

        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._fields', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
        }
        return view('PkgGapp::eMetadatum.create', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
    }
    public function store(EMetadatumRequest $request) {
        $validatedData = $request->validated();
        $eMetadatum = $this->eMetadatumService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' => __('PkgGapp::eMetadatum.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $eMetadatum->id]
            );
        }

        return redirect()->route('eMetadata.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' => __('PkgGapp::eMetadatum.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('eMetadatum.edit_' . $id);


        $itemEMetadatum = $this->eMetadatumService->find($id);


        $eModels = $this->eModelService->all();
        $eDataFields = $this->eDataFieldService->all();
        $eMetadataDefinitions = $this->eMetadataDefinitionService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._fields', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
        }

        return view('PkgGapp::eMetadatum.edit', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('eMetadatum.edit_' . $id);


        $itemEMetadatum = $this->eMetadatumService->find($id);


        $eModels = $this->eModelService->all();
        $eDataFields = $this->eDataFieldService->all();
        $eMetadataDefinitions = $this->eMetadataDefinitionService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._fields', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
        }

        return view('PkgGapp::eMetadatum.edit', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));

    }
    public function update(EMetadatumRequest $request, string $id) {

        $validatedData = $request->validated();
        $eMetadatum = $this->eMetadatumService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $eMetadatum->id]
            );
        }

        return redirect()->route('eMetadata.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $eMetadatum = $this->eMetadatumService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('eMetadata.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')
                ])
        );

    }

    public function export($format)
    {
        $eMetadata_data = $this->eMetadatumService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EMetadatumExport($eMetadata_data,'csv'), 'eMetadatum_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EMetadatumExport($eMetadata_data,'xlsx'), 'eMetadatum_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EMetadatumImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eMetadata.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eMetadata.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eMetadatum.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEMetadata()
    {
        $eMetadata = $this->eMetadatumService->all();
        return response()->json($eMetadata);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $eMetadatum = $this->eMetadatumService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEMetadatum = $this->eMetadatumService->dataCalcul($eMetadatum);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEMetadatum
        ]);
    }
    

}
