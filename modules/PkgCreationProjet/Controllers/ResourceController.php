<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\ResourceRequest;
use Modules\PkgCreationProjet\Services\ResourceService;
use Modules\PkgCreationProjet\Services\ProjetService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\ResourceExport;
use Modules\PkgCreationProjet\App\Imports\ResourceImport;
use Modules\Core\Services\ContextState;

class ResourceController extends AdminController
{
    protected $resourceService;
    protected $projetService;

    public function __construct(ResourceService $resourceService, ProjetService $projetService)
    {
        parent::__construct();
        $this->resourceService = $resourceService;
        $this->projetService = $projetService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $resource_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $resources_data = $this->resourceService->paginate($resource_searchQuery);

        if ($request->ajax()) {
            return view('PkgCreationProjet::resource._table', compact('resources_data'))->render();
        }

        return view('PkgCreationProjet::resource.index', compact('resources_data','resource_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemResource = $this->resourceService->createInstance();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::resource._fields', compact('itemResource', 'projets'));
        }
        return view('PkgCreationProjet::resource.create', compact('itemResource', 'projets'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(ResourceRequest $request)
    {
        $validatedData = $request->validated();
        $resource = $this->resourceService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $resource,
                'modelName' => __('PkgCreationProjet::resource.singular')])
            ]);
        }

        return redirect()->route('resources.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $resource,
                'modelName' => __('PkgCreationProjet::resource.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemResource = $this->resourceService->find($id);
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::resource._fields', compact('itemResource', 'projets'));
        }

        return view('PkgCreationProjet::resource.show', compact('itemResource'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemResource = $this->resourceService->find($id);
        $projets = $this->projetService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('resource_id', $id);


        if (request()->ajax()) {
            return view('PkgCreationProjet::resource._fields', compact('itemResource', 'projets'));
        }

        return view('PkgCreationProjet::resource.edit', compact('itemResource', 'projets'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(ResourceRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $resource = $this->resourceService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $resource,
                'modelName' =>  __('PkgCreationProjet::resource.singular')])
            ]);
        }

        return redirect()->route('resources.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $resource,
                'modelName' =>  __('PkgCreationProjet::resource.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $resource = $this->resourceService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $resource,
                'modelName' =>  __('PkgCreationProjet::resource.singular')])
            ]);
        }

        return redirect()->route('resources.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $resource,
                'modelName' =>  __('PkgCreationProjet::resource.singular')
                ])
        );
    }

    public function export()
    {
        $resources_data = $this->resourceService->all();
        return Excel::download(new ResourceExport($resources_data), 'resource_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ResourceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('resources.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('resources.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::resource.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getResources()
    {
        $resources = $this->resourceService->all();
        return response()->json($resources);
    }
}
