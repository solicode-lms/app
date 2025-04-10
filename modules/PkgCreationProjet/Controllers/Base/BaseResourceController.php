<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\ResourceService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\ResourceRequest;
use Modules\PkgCreationProjet\Models\Resource;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\ResourceExport;
use Modules\PkgCreationProjet\App\Imports\ResourceImport;
use Modules\Core\Services\ContextState;

class BaseResourceController extends AdminController
{
    protected $resourceService;
    protected $projetService;

    public function __construct(ResourceService $resourceService, ProjetService $projetService) {
        parent::__construct();
        $this->service  =  $resourceService;
        $this->resourceService = $resourceService;
        $this->projetService = $projetService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('resource.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.resource.projet.formateur_id') == null){
           $this->viewState->init('filter.resource.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $resources_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'resources_search',
                $this->viewState->get("filter.resource.resources_search")
            )],
            $request->except(['resources_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->resourceService->prepareDataForIndexView($resources_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($resource_partialViewName, $resource_compact_value)->render();
        }

        return view('PkgCreationProjet::resource.index', $resource_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.resource.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemResource = $this->resourceService->createInstance();
        

        $projets = $this->projetService->all();

        if (request()->ajax()) {
            return view('PkgCreationProjet::resource._fields', compact('itemResource', 'projets'));
        }
        return view('PkgCreationProjet::resource.create', compact('itemResource', 'projets'));
    }
    public function store(ResourceRequest $request) {
        $validatedData = $request->validated();
        $resource = $this->resourceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $resource,
                'modelName' => __('PkgCreationProjet::resource.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $resource->id]
            );
        }

        return redirect()->route('resources.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $resource,
                'modelName' => __('PkgCreationProjet::resource.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('resource.edit_' . $id);


        $itemResource = $this->resourceService->edit($id);
        $this->authorize('view', $itemResource);


        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::resource._fields', array_merge(compact('itemResource','projets'),));
        }

        return view('PkgCreationProjet::resource.edit', array_merge(compact('itemResource','projets'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('resource.edit_' . $id);


        $itemResource = $this->resourceService->edit($id);
        $this->authorize('edit', $itemResource);


        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::resource._fields', array_merge(compact('itemResource','projets'),));
        }

        return view('PkgCreationProjet::resource.edit', array_merge(compact('itemResource','projets'),));


    }
    public function update(ResourceRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $resource = $this->resourceService->find($id);
        $this->authorize('update', $resource);

        $validatedData = $request->validated();
        $resource = $this->resourceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $resource,
                'modelName' =>  __('PkgCreationProjet::resource.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $resource->id]
            );
        }

        return redirect()->route('resources.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $resource,
                'modelName' =>  __('PkgCreationProjet::resource.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $resource = $this->resourceService->find($id);
        $this->authorize('delete', $resource);

        $resource = $this->resourceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $resource,
                'modelName' =>  __('PkgCreationProjet::resource.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('resources.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $resource,
                'modelName' =>  __('PkgCreationProjet::resource.singular')
                ])
        );

    }

    public function export($format)
    {
        $resources_data = $this->resourceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ResourceExport($resources_data,'csv'), 'resource_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ResourceExport($resources_data,'xlsx'), 'resource_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $resource = $this->resourceService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedResource = $this->resourceService->dataCalcul($resource);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedResource
        ]);
    }
    

}