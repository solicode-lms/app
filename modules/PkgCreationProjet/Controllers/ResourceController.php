<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\ResourceRequest;
use Modules\PkgCreationProjet\Services\ResourceService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\ResourceExport;
use Modules\PkgCreationProjet\App\Imports\ResourceImport;

class ResourceController extends AdminController
{
    protected $resourceService;

    public function __construct(ResourceService $resourceService)
    {
        parent::__construct();
        $this->resourceService = $resourceService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->resourceService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCreationProjet::resource._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCreationProjet::resource.index', compact('data'));
    }

    public function create()
    {
        $item = $this->resourceService->createInstance();
        return view('PkgCreationProjet::resource.create', compact('item'));
    }

    public function store(ResourceRequest $request)
    {
        $validatedData = $request->validated();
        $resource = $this->resourceService->create($validatedData);


        return redirect()->route('resources.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $resource,
            'modelName' => __('PkgCreationProjet::resource.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->resourceService->find($id);
        return view('PkgCreationProjet::resource.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->resourceService->find($id);
        return view('PkgCreationProjet::resource.edit', compact('item'));
    }

    public function update(ResourceRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $resource = $this->resourceService->update($id, $validatedData);



        return redirect()->route('resources.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $resource,
                'modelName' =>  __('PkgCreationProjet::resource.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $resource = $this->resourceService->destroy($id);
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
        $data = $this->resourceService->all();
        return Excel::download(new ResourceExport($data), 'resource_export.xlsx');
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
