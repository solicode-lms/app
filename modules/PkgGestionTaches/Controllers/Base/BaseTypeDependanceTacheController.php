<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\TypeDependanceTacheService;
use Modules\PkgGestionTaches\Services\DependanceTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\TypeDependanceTacheRequest;
use Modules\PkgGestionTaches\Models\TypeDependanceTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\TypeDependanceTacheExport;
use Modules\PkgGestionTaches\App\Imports\TypeDependanceTacheImport;
use Modules\Core\Services\ContextState;

class BaseTypeDependanceTacheController extends AdminController
{
    protected $typeDependanceTacheService;

    public function __construct(TypeDependanceTacheService $typeDependanceTacheService) {
        parent::__construct();
        $this->service  =  $typeDependanceTacheService;
        $this->typeDependanceTacheService = $typeDependanceTacheService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('typeDependanceTache.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $typeDependanceTaches_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'typeDependanceTaches_search',
                $this->viewState->get("filter.typeDependanceTache.typeDependanceTaches_search")
            )],
            $request->except(['typeDependanceTaches_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->typeDependanceTacheService->prepareDataForIndexView($typeDependanceTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($typeDependanceTache_partialViewName, $typeDependanceTache_compact_value)->render();
        }

        return view('PkgGestionTaches::typeDependanceTache.index', $typeDependanceTache_compact_value);
    }
    public function create() {


        $itemTypeDependanceTache = $this->typeDependanceTacheService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgGestionTaches::typeDependanceTache._fields', compact('itemTypeDependanceTache'));
        }
        return view('PkgGestionTaches::typeDependanceTache.create', compact('itemTypeDependanceTache'));
    }
    public function store(TypeDependanceTacheRequest $request) {
        $validatedData = $request->validated();
        $typeDependanceTache = $this->typeDependanceTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' => __('PkgGestionTaches::typeDependanceTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $typeDependanceTache->id]
            );
        }

        return redirect()->route('typeDependanceTaches.edit',['typeDependanceTache' => $typeDependanceTache->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' => __('PkgGestionTaches::typeDependanceTache.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('typeDependanceTache.edit_' . $id);


        $itemTypeDependanceTache = $this->typeDependanceTacheService->edit($id);




        $this->viewState->set('scope.dependanceTache.type_dependance_tache_id', $id);
        

        $dependanceTacheService =  new DependanceTacheService();
        $dependanceTaches_view_data = $dependanceTacheService->prepareDataForIndexView();
        extract($dependanceTaches_view_data);

        if (request()->ajax()) {
            return view('PkgGestionTaches::typeDependanceTache._edit', array_merge(compact('itemTypeDependanceTache',),$dependanceTache_compact_value));
        }

        return view('PkgGestionTaches::typeDependanceTache.edit', array_merge(compact('itemTypeDependanceTache',),$dependanceTache_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('typeDependanceTache.edit_' . $id);


        $itemTypeDependanceTache = $this->typeDependanceTacheService->edit($id);




        $this->viewState->set('scope.dependanceTache.type_dependance_tache_id', $id);
        

        $dependanceTacheService =  new DependanceTacheService();
        $dependanceTaches_view_data = $dependanceTacheService->prepareDataForIndexView();
        extract($dependanceTaches_view_data);

        if (request()->ajax()) {
            return view('PkgGestionTaches::typeDependanceTache._edit', array_merge(compact('itemTypeDependanceTache',),$dependanceTache_compact_value));
        }

        return view('PkgGestionTaches::typeDependanceTache.edit', array_merge(compact('itemTypeDependanceTache',),$dependanceTache_compact_value));


    }
    public function update(TypeDependanceTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $typeDependanceTache = $this->typeDependanceTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' =>  __('PkgGestionTaches::typeDependanceTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $typeDependanceTache->id]
            );
        }

        return redirect()->route('typeDependanceTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' =>  __('PkgGestionTaches::typeDependanceTache.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $typeDependanceTache = $this->typeDependanceTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' =>  __('PkgGestionTaches::typeDependanceTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('typeDependanceTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' =>  __('PkgGestionTaches::typeDependanceTache.singular')
                ])
        );

    }

    public function export($format)
    {
        $typeDependanceTaches_data = $this->typeDependanceTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new TypeDependanceTacheExport($typeDependanceTaches_data,'csv'), 'typeDependanceTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new TypeDependanceTacheExport($typeDependanceTaches_data,'xlsx'), 'typeDependanceTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new TypeDependanceTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('typeDependanceTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('typeDependanceTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::typeDependanceTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTypeDependanceTaches()
    {
        $typeDependanceTaches = $this->typeDependanceTacheService->all();
        return response()->json($typeDependanceTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $typeDependanceTache = $this->typeDependanceTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedTypeDependanceTache = $this->typeDependanceTacheService->dataCalcul($typeDependanceTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedTypeDependanceTache
        ]);
    }
    

}