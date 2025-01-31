<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EMetadatumService;
use Modules\PkgGapp\Services\EDataFieldService;
use Modules\PkgGapp\Services\EMetadataDefinitionService;
use Modules\PkgGapp\Services\EModelService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\EMetadatumRequest;
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
        // Extraire les paramètres de recherche, page, et filtres
        $eMetadata_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('eMetadata_search', '')],
            $request->except(['eMetadata_search', 'page', 'sort'])
        );

        // Paginer les eMetadata
        $eMetadata_data = $this->eMetadatumService->paginate($eMetadata_params);

        // Récupérer les statistiques et les champs filtrables
        $eMetadata_stats = $this->eMetadatumService->geteMetadatumStats();
        $eMetadata_filters = $this->eMetadatumService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::eMetadatum._table', compact('eMetadata_data', 'eMetadata_stats', 'eMetadata_filters'))->render();
        }

        return view('PkgGapp::eMetadatum.index', compact('eMetadata_data', 'eMetadata_stats', 'eMetadata_filters'));
    }
    public function create() {
        $itemEMetadatum = $this->eMetadatumService->createInstance();
        $eDataFields = $this->eDataFieldService->all();
        $eMetadataDefinitions = $this->eMetadataDefinitionService->all();
        $eModels = $this->eModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._fields', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
        }
        return view('PkgGapp::eMetadatum.create', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
    }
    public function store(EMetadatumRequest $request) {
        $validatedData = $request->validated();
        $eMetadatum = $this->eMetadatumService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'e_metadatum_id' => $eMetadatum->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' => __('PkgGapp::eMetadatum.singular')])
            ]);
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
        $itemEMetadatum = $this->eMetadatumService->find($id);
        $eDataFields = $this->eDataFieldService->all();
        $eMetadataDefinitions = $this->eMetadataDefinitionService->all();
        $eModels = $this->eModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._fields', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
        }

        return view('PkgGapp::eMetadatum.show', compact('itemEMetadatum'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('e_metadatum_id', $id);
        
        $itemEMetadatum = $this->eMetadatumService->find($id);
        $eDataFields = $this->eDataFieldService->all();
        $eMetadataDefinitions = $this->eMetadataDefinitionService->all();
        $eModels = $this->eModelService->all();

        if (request()->ajax()) {
            return view('PkgGapp::eMetadatum._fields', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));
        }

        return view('PkgGapp::eMetadatum.edit', compact('itemEMetadatum', 'eDataFields', 'eMetadataDefinitions', 'eModels'));

    }
    public function update(EMetadatumRequest $request, string $id) {

        $validatedData = $request->validated();
        $eMetadatum = $this->eMetadatumService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')])
            ]);
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
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')])
            ]);
        }

        return redirect()->route('eMetadata.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadatum,
                'modelName' =>  __('PkgGapp::eMetadatum.singular')
                ])
        );

    }

    public function export()
    {
        $eMetadata_data = $this->eMetadatumService->all();
        return Excel::download(new EMetadatumExport($eMetadata_data), 'eMetadatum_export.xlsx');
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

}
