<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\MetadatumRequest;
use Modules\PkgGapp\Services\MetadatumService;
use Modules\PkgGapp\Services\MetadataTypeService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\MetadatumExport;
use Modules\PkgGapp\App\Imports\MetadatumImport;
use Modules\Core\Services\ContextState;

class BaseMetadatumController extends AdminController
{
    protected $metadatumService;
    protected $metadataTypeService;

    public function __construct(MetadatumService $metadatumService, MetadataTypeService $metadataTypeService)
    {
        parent::__construct();
        $this->metadatumService = $metadatumService;
        $this->metadataTypeService = $metadataTypeService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $metadata_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('metadata_search', '')],
            $request->except(['metadata_search', 'page', 'sort'])
        );
    
        // Paginer les metadata
        $metadata_data = $this->metadatumService->paginate($metadata_params);
    
        // Récupérer les statistiques et les champs filtrables
        $metadata_stats = $this->metadatumService->getmetadatumStats();
        $metadata_filters = $this->metadatumService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::metadatum._table', compact('metadata_data', 'metadata_stats', 'metadata_filters'))->render();
        }
    
        return view('PkgGapp::metadatum.index', compact('metadata_data', 'metadata_stats', 'metadata_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemMetadatum = $this->metadatumService->createInstance();
        $metadataTypes = $this->metadataTypeService->all();


        if (request()->ajax()) {
            return view('PkgGapp::metadatum._fields', compact('itemMetadatum', 'metadataTypes'));
        }
        return view('PkgGapp::metadatum.create', compact('itemMetadatum', 'metadataTypes'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(MetadatumRequest $request)
    {
        $validatedData = $request->validated();
        $metadatum = $this->metadatumService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $metadatum,
                'modelName' => __('PkgGapp::metadatum.singular')])
            ]);
        }

        return redirect()->route('metadata.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $metadatum,
                'modelName' => __('PkgGapp::metadatum.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemMetadatum = $this->metadatumService->find($id);
        $metadataTypes = $this->metadataTypeService->all();


        if (request()->ajax()) {
            return view('PkgGapp::metadatum._fields', compact('itemMetadatum', 'metadataTypes'));
        }

        return view('PkgGapp::metadatum.show', compact('itemMetadatum'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemMetadatum = $this->metadatumService->find($id);
        $metadataTypes = $this->metadataTypeService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('metadatum_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::metadatum._fields', compact('itemMetadatum', 'metadataTypes'));
        }

        return view('PkgGapp::metadatum.edit', compact('itemMetadatum', 'metadataTypes'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(MetadatumRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $metadatum = $this->metadatumService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $metadatum,
                'modelName' =>  __('PkgGapp::metadatum.singular')])
            ]);
        }

        return redirect()->route('metadata.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $metadatum,
                'modelName' =>  __('PkgGapp::metadatum.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $metadatum = $this->metadatumService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $metadatum,
                'modelName' =>  __('PkgGapp::metadatum.singular')])
            ]);
        }

        return redirect()->route('metadata.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $metadatum,
                'modelName' =>  __('PkgGapp::metadatum.singular')
                ])
        );
    }

    public function export()
    {
        $metadata_data = $this->metadatumService->all();
        return Excel::download(new MetadatumExport($metadata_data), 'metadatum_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new MetadatumImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('metadata.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('metadata.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::metadatum.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getMetadata()
    {
        $metadata = $this->metadatumService->all();
        return response()->json($metadata);
    }
}
