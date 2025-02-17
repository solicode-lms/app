<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EMetadataDefinitionService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EMetadataDefinitionRequest;
use Modules\PkgGapp\Models\EMetadataDefinition;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EMetadataDefinitionExport;
use Modules\PkgGapp\App\Imports\EMetadataDefinitionImport;
use Modules\Core\Services\ContextState;

class BaseEMetadataDefinitionController extends AdminController
{
    protected $eMetadataDefinitionService;

    public function __construct(EMetadataDefinitionService $eMetadataDefinitionService) {
        parent::__construct();
        $this->eMetadataDefinitionService = $eMetadataDefinitionService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('eMetadataDefinition.index');

        // Extraire les paramètres de recherche, page, et filtres
        $eMetadataDefinitions_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('eMetadataDefinitions_search', $this->viewState->get("filter.eMetadataDefinition.eMetadataDefinitions_search"))],
            $request->except(['eMetadataDefinitions_search', 'page', 'sort'])
        );

        // Paginer les eMetadataDefinitions
        $eMetadataDefinitions_data = $this->eMetadataDefinitionService->paginate($eMetadataDefinitions_params);

        // Récupérer les statistiques et les champs filtrables
        $eMetadataDefinitions_stats = $this->eMetadataDefinitionService->geteMetadataDefinitionStats();
        $eMetadataDefinitions_filters = $this->eMetadataDefinitionService->getFieldsFilterable();
        $eMetadataDefinition_instance =  $this->eMetadataDefinitionService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::eMetadataDefinition._table', compact('eMetadataDefinitions_data', 'eMetadataDefinitions_stats', 'eMetadataDefinitions_filters','eMetadataDefinition_instance'))->render();
        }

        return view('PkgGapp::eMetadataDefinition.index', compact('eMetadataDefinitions_data', 'eMetadataDefinitions_stats', 'eMetadataDefinitions_filters','eMetadataDefinition_instance'));
    }
    public function create() {
        $itemEMetadataDefinition = $this->eMetadataDefinitionService->createInstance();


        if (request()->ajax()) {
            return view('PkgGapp::eMetadataDefinition._fields', compact('itemEMetadataDefinition'));
        }
        return view('PkgGapp::eMetadataDefinition.create', compact('itemEMetadataDefinition'));
    }
    public function store(EMetadataDefinitionRequest $request) {
        $validatedData = $request->validated();
        $eMetadataDefinition = $this->eMetadataDefinitionService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' => __('PkgGapp::eMetadataDefinition.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $eMetadataDefinition->id]
            );
        }

        return redirect()->route('eMetadataDefinitions.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' => __('PkgGapp::eMetadataDefinition.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('eMetadataDefinition.edit_' . $id);

        $itemEMetadataDefinition = $this->eMetadataDefinitionService->find($id);
  


        if (request()->ajax()) {
            return view('PkgGapp::eMetadataDefinition._fields', compact('itemEMetadataDefinition'));
        }

        return view('PkgGapp::eMetadataDefinition.edit', compact('itemEMetadataDefinition'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('eMetadataDefinition.edit_' . $id);

        $itemEMetadataDefinition = $this->eMetadataDefinitionService->find($id);



        if (request()->ajax()) {
            return view('PkgGapp::eMetadataDefinition._fields', compact('itemEMetadataDefinition'));
        }

        return view('PkgGapp::eMetadataDefinition.edit', compact('itemEMetadataDefinition'));

    }
    public function update(EMetadataDefinitionRequest $request, string $id) {

        $validatedData = $request->validated();
        $eMetadataDefinition = $this->eMetadataDefinitionService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $eMetadataDefinition->id]
            );
        }

        return redirect()->route('eMetadataDefinitions.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $eMetadataDefinition = $this->eMetadataDefinitionService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('eMetadataDefinitions.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')
                ])
        );

    }

    public function export($format)
    {
        $eMetadataDefinitions_data = $this->eMetadataDefinitionService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EMetadataDefinitionExport($eMetadataDefinitions_data,'csv'), 'eMetadataDefinition_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EMetadataDefinitionExport($eMetadataDefinitions_data,'xlsx'), 'eMetadataDefinition_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EMetadataDefinitionImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eMetadataDefinitions.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eMetadataDefinitions.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eMetadataDefinition.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEMetadataDefinitions()
    {
        $eMetadataDefinitions = $this->eMetadataDefinitionService->all();
        return response()->json($eMetadataDefinitions);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $eMetadataDefinition = $this->eMetadataDefinitionService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEMetadataDefinition = $this->eMetadataDefinitionService->dataCalcul($eMetadataDefinition);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEMetadataDefinition
        ]);
    }
    

}
