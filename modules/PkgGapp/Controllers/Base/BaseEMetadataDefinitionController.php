<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EMetadataDefinitionService;
use Modules\PkgGapp\Services\EMetadatumService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\EMetadataDefinitionRequest;
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
        // Extraire les paramètres de recherche, page, et filtres
        $eMetadataDefinitions_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('eMetadataDefinitions_search', '')],
            $request->except(['eMetadataDefinitions_search', 'page', 'sort'])
        );

        // Paginer les eMetadataDefinitions
        $eMetadataDefinitions_data = $this->eMetadataDefinitionService->paginate($eMetadataDefinitions_params);

        // Récupérer les statistiques et les champs filtrables
        $eMetadataDefinitions_stats = $this->eMetadataDefinitionService->geteMetadataDefinitionStats();
        $eMetadataDefinitions_filters = $this->eMetadataDefinitionService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::eMetadataDefinition._table', compact('eMetadataDefinitions_data', 'eMetadataDefinitions_stats', 'eMetadataDefinitions_filters'))->render();
        }

        return view('PkgGapp::eMetadataDefinition.index', compact('eMetadataDefinitions_data', 'eMetadataDefinitions_stats', 'eMetadataDefinitions_filters'));
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
            return response()->json(['success' => true, 
            'entity_id' => $eMetadataDefinition->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' => __('PkgGapp::eMetadataDefinition.singular')])
            ]);
        }

        return redirect()->route('eMetadataDefinitions.edit',['eMetadataDefinition' => $eMetadataDefinition->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' => __('PkgGapp::eMetadataDefinition.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('e_metadata_definition_id', $id);
        
        $itemEMetadataDefinition = $this->eMetadataDefinitionService->find($id);
        $eMetadatumService =  new EMetadatumService();
        $eMetadata_data =  $itemEMetadataDefinition->eMetadata()->paginate(10);
        $eMetadata_stats = $eMetadatumService->geteMetadatumStats();
        $eMetadata_filters = $eMetadatumService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgGapp::eMetadataDefinition._edit', compact('itemEMetadataDefinition', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters'));
        }

        return view('PkgGapp::eMetadataDefinition.edit', compact('itemEMetadataDefinition', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('e_metadata_definition_id', $id);
        
        $itemEMetadataDefinition = $this->eMetadataDefinitionService->find($id);
        $eMetadatumService =  new EMetadatumService();
        $eMetadata_data =  $itemEMetadataDefinition->eMetadata()->paginate(10);
        $eMetadata_stats = $eMetadatumService->geteMetadatumStats();
        $eMetadata_filters = $eMetadatumService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgGapp::eMetadataDefinition._edit', compact('itemEMetadataDefinition', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters'));
        }

        return view('PkgGapp::eMetadataDefinition.edit', compact('itemEMetadataDefinition', 'eMetadata_data', 'eMetadata_stats', 'eMetadata_filters'));

    }
    public function update(EMetadataDefinitionRequest $request, string $id) {

        $validatedData = $request->validated();
        $eMetadataDefinition = $this->eMetadataDefinitionService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')])
            ]);
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
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')])
            ]);
        }

        return redirect()->route('eMetadataDefinitions.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')
                ])
        );

    }

    public function export()
    {
        $eMetadataDefinitions_data = $this->eMetadataDefinitionService->all();
        return Excel::download(new EMetadataDefinitionExport($eMetadataDefinitions_data), 'eMetadataDefinition_export.xlsx');
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

}
