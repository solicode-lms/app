<?php


namespace Modules\PkgGapp\Controllers\Base;

use Illuminate\Database\Eloquent\Scope;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\MetadataTypeRequest;
use Modules\PkgGapp\Services\MetadataTypeService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\MetadataTypeExport;
use Modules\PkgGapp\App\Imports\MetadataTypeImport;
use Modules\Core\Services\ContextState;
use Modules\PkgGapp\App\Enums\MetadataScope;
use Modules\PkgGapp\App\Enums\MetaDataValueType;

class BaseMetadataTypeController extends AdminController
{
    protected $metadataTypeService;

    public function __construct(MetadataTypeService $metadataTypeService)
    {
        parent::__construct();
        $this->metadataTypeService = $metadataTypeService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $metadataTypes_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('metadataTypes_search', '')],
            $request->except(['metadataTypes_search', 'page', 'sort'])
        );
    
        // Paginer les metadataTypes
        $metadataTypes_data = $this->metadataTypeService->paginate($metadataTypes_params);
    
        // Récupérer les statistiques et les champs filtrables
        $metadataTypes_stats = $this->metadataTypeService->getmetadataTypeStats();
        $metadataTypes_filters = $this->metadataTypeService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::metadataType._table', compact('metadataTypes_data', 'metadataTypes_stats', 'metadataTypes_filters'))->render();
        }
    
        return view('PkgGapp::metadataType.index', compact('metadataTypes_data', 'metadataTypes_stats', 'metadataTypes_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {

        $itemMetadataType = $this->metadataTypeService->createInstance();

        $metaDataValueTypeCases = \Modules\PkgGapp\App\Enums\MetaDataValueType::cases();
        $metadataScopeCases = \Modules\PkgGapp\App\Enums\MetadataScope::cases();
        
        if (request()->ajax()) {
            return view('PkgGapp::metadataType._fields', compact('itemMetadataType','metaDataValueTypeCases','metadataScopeCases'));
        }
        return view('PkgGapp::metadataType.create', compact('itemMetadataType','metaDataValueTypeCases','metadataScopeCases'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(MetadataTypeRequest $request)
    {
        $validatedData = $request->validated();
        $metadataType = $this->metadataTypeService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $metadataType,
                'modelName' => __('PkgGapp::metadataType.singular')])
            ]);
        }

        return redirect()->route('metadataTypes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $metadataType,
                'modelName' => __('PkgGapp::metadataType.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemMetadataType = $this->metadataTypeService->find($id);


        if (request()->ajax()) {
            return view('PkgGapp::metadataType._fields', compact('itemMetadataType'));
        }

        return view('PkgGapp::metadataType.show', compact('itemMetadataType'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemMetadataType = $this->metadataTypeService->find($id);
       
        $metaDataValueTypeCases = \Modules\PkgGapp\App\Enums\MetaDataValueType::cases();
        $metadataScopeCases = \Modules\PkgGapp\App\Enums\MetadataScope::cases();
        
      
        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('metadataType_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::metadataType._fields', compact('itemMetadataType','metaDataValueTypeCases','metadataScopeCases'));
        }

        return view('PkgGapp::metadataType.edit', compact('itemMetadataType','metaDataValueTypeCases','metadataScopeCases'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(MetadataTypeRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $metadataType = $this->metadataTypeService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $metadataType,
                'modelName' =>  __('PkgGapp::metadataType.singular')])
            ]);
        }

        return redirect()->route('metadataTypes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $metadataType,
                'modelName' =>  __('PkgGapp::metadataType.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $metadataType = $this->metadataTypeService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $metadataType,
                'modelName' =>  __('PkgGapp::metadataType.singular')])
            ]);
        }

        return redirect()->route('metadataTypes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $metadataType,
                'modelName' =>  __('PkgGapp::metadataType.singular')
                ])
        );
    }

    public function export()
    {
        $metadataTypes_data = $this->metadataTypeService->all();
        return Excel::download(new MetadataTypeExport($metadataTypes_data), 'metadataType_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new MetadataTypeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('metadataTypes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('metadataTypes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::metadataType.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getMetadataTypes()
    {
        $metadataTypes = $this->metadataTypeService->all();
        return response()->json($metadataTypes);
    }
}
