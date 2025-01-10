<?php
 

namespace Modules\PkgGapp\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\RelationshipRequest;
use Modules\PkgGapp\Services\RelationshipService;
use Modules\PkgGapp\Services\IModelService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\RelationshipExport;
use Modules\PkgGapp\App\Imports\RelationshipImport;
use Modules\Core\Services\ContextState;

class BaseRelationshipController extends AdminController
{
    protected $relationshipService;
    protected $iModelService;

    public function __construct(RelationshipService $relationshipService, IModelService $iModelService, IModelService $iModelService)
    {
        parent::__construct();
        $this->relationshipService = $relationshipService;
        $this->iModelService = $iModelService;
        $this->iModelService = $iModelService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $relationships_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('relationships_search', '')],
            $request->except(['relationships_search', 'page', 'sort'])
        );
    
        // Paginer les relationships
        $relationships_data = $this->relationshipService->paginate($relationships_params);
    
        // Récupérer les statistiques et les champs filtrables
        $relationships_stats = $this->relationshipService->getrelationshipStats();
        $relationships_filters = $this->relationshipService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::relationship._table', compact('relationships_data', 'relationships_stats', 'relationships_filters'))->render();
        }
    
        return view('PkgGapp::relationship.index', compact('relationships_data', 'relationships_stats', 'relationships_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemRelationship = $this->relationshipService->createInstance();
        $iModels = $this->iModelService->all();
        $iModels = $this->iModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::relationship._fields', compact('itemRelationship', 'iModels', 'iModels'));
        }
        return view('PkgGapp::relationship.create', compact('itemRelationship', 'iModels', 'iModels'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(RelationshipRequest $request)
    {
        $validatedData = $request->validated();
        $relationship = $this->relationshipService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $relationship,
                'modelName' => __('PkgGapp::relationship.singular')])
            ]);
        }

        return redirect()->route('relationships.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $relationship,
                'modelName' => __('PkgGapp::relationship.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemRelationship = $this->relationshipService->find($id);
        $iModels = $this->iModelService->all();
        $iModels = $this->iModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::relationship._fields', compact('itemRelationship', 'iModels', 'iModels'));
        }

        return view('PkgGapp::relationship.show', compact('itemRelationship'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemRelationship = $this->relationshipService->find($id);
        $iModels = $this->iModelService->all();
        $iModels = $this->iModelService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('relationship_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::relationship._fields', compact('itemRelationship', 'iModels', 'iModels'));
        }

        return view('PkgGapp::relationship.edit', compact('itemRelationship', 'iModels', 'iModels'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(RelationshipRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $relationship = $this->relationshipService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $relationship,
                'modelName' =>  __('PkgGapp::relationship.singular')])
            ]);
        }

        return redirect()->route('relationships.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $relationship,
                'modelName' =>  __('PkgGapp::relationship.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $relationship = $this->relationshipService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $relationship,
                'modelName' =>  __('PkgGapp::relationship.singular')])
            ]);
        }

        return redirect()->route('relationships.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $relationship,
                'modelName' =>  __('PkgGapp::relationship.singular')
                ])
        );
    }

    public function export()
    {
        $relationships_data = $this->relationshipService->all();
        return Excel::download(new RelationshipExport($relationships_data), 'relationship_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new RelationshipImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('relationships.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('relationships.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::relationship.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRelationships()
    {
        $relationships = $this->relationshipService->all();
        return response()->json($relationships);
    }
}
