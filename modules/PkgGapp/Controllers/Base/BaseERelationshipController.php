<?php


namespace Modules\PkgGapp\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\ERelationshipRequest;
use Modules\PkgGapp\Services\ERelationshipService;
use Modules\PkgGapp\Services\EModelService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\ERelationshipExport;
use Modules\PkgGapp\App\Imports\ERelationshipImport;
use Modules\Core\Services\ContextState;

class BaseERelationshipController extends AdminController
{
    protected $eRelationshipService;
    protected $eModelService;

    public function __construct(ERelationshipService $eRelationshipService, EModelService $eModelService)
    {
        parent::__construct();
        $this->eRelationshipService = $eRelationshipService;
        $this->eModelService = $eModelService;
        $this->eModelService = $eModelService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $eRelationships_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('eRelationships_search', '')],
            $request->except(['eRelationships_search', 'page', 'sort'])
        );
    
        // Paginer les eRelationships
        $eRelationships_data = $this->eRelationshipService->paginate($eRelationships_params);
    
        // Récupérer les statistiques et les champs filtrables
        $eRelationships_stats = $this->eRelationshipService->geteRelationshipStats();
        $eRelationships_filters = $this->eRelationshipService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::eRelationship._table', compact('eRelationships_data', 'eRelationships_stats', 'eRelationships_filters'))->render();
        }
    
        return view('PkgGapp::eRelationship.index', compact('eRelationships_data', 'eRelationships_stats', 'eRelationships_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemERelationship = $this->eRelationshipService->createInstance();
        $eModels = $this->eModelService->all();
        $eModels = $this->eModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eRelationship._fields', compact('itemERelationship', 'eModels', 'eModels'));
        }
        return view('PkgGapp::eRelationship.create', compact('itemERelationship', 'eModels', 'eModels'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(ERelationshipRequest $request)
    {
        $validatedData = $request->validated();
        $eRelationship = $this->eRelationshipService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $eRelationship,
                'modelName' => __('PkgGapp::eRelationship.singular')])
            ]);
        }

        return redirect()->route('eRelationships.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eRelationship,
                'modelName' => __('PkgGapp::eRelationship.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemERelationship = $this->eRelationshipService->find($id);
        $eModels = $this->eModelService->all();
        $eModels = $this->eModelService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eRelationship._fields', compact('itemERelationship', 'eModels', 'eModels'));
        }

        return view('PkgGapp::eRelationship.show', compact('itemERelationship'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemERelationship = $this->eRelationshipService->find($id);
        $eModels = $this->eModelService->all();
        $eModels = $this->eModelService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('eRelationship_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::eRelationship._fields', compact('itemERelationship', 'eModels', 'eModels'));
        }

        return view('PkgGapp::eRelationship.edit', compact('itemERelationship', 'eModels', 'eModels'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(ERelationshipRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $eRelationship = $this->eRelationshipService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $eRelationship,
                'modelName' =>  __('PkgGapp::eRelationship.singular')])
            ]);
        }

        return redirect()->route('eRelationships.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eRelationship,
                'modelName' =>  __('PkgGapp::eRelationship.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $eRelationship = $this->eRelationshipService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eRelationship,
                'modelName' =>  __('PkgGapp::eRelationship.singular')])
            ]);
        }

        return redirect()->route('eRelationships.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eRelationship,
                'modelName' =>  __('PkgGapp::eRelationship.singular')
                ])
        );
    }

    public function export()
    {
        $eRelationships_data = $this->eRelationshipService->all();
        return Excel::download(new ERelationshipExport($eRelationships_data), 'eRelationship_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ERelationshipImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eRelationships.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eRelationships.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eRelationship.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getERelationships()
    {
        $eRelationships = $this->eRelationshipService->all();
        return response()->json($eRelationships);
    }
}
