<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\DependanceTacheService;
use Modules\PkgGestionTaches\Services\TacheService;
use Modules\PkgGestionTaches\Services\TypeDependanceTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\DependanceTacheRequest;
use Modules\PkgGestionTaches\Models\DependanceTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\DependanceTacheExport;
use Modules\PkgGestionTaches\App\Imports\DependanceTacheImport;
use Modules\Core\Services\ContextState;

class BaseDependanceTacheController extends AdminController
{
    protected $dependanceTacheService;
    protected $tacheService;
    protected $typeDependanceTacheService;

    public function __construct(DependanceTacheService $dependanceTacheService, TacheService $tacheService, TacheService $tacheService, TypeDependanceTacheService $typeDependanceTacheService) {
        parent::__construct();
        $this->dependanceTacheService = $dependanceTacheService;
        $this->tacheService = $tacheService;
        $this->typeDependanceTacheService = $typeDependanceTacheService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('dependanceTache.index');


        // Extraire les paramètres de recherche, page, et filtres
        $dependanceTaches_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('dependanceTaches_search', $this->viewState->get("filter.dependanceTache.dependanceTaches_search"))],
            $request->except(['dependanceTaches_search', 'page', 'sort'])
        );

        // Paginer les dependanceTaches
        $dependanceTaches_data = $this->dependanceTacheService->paginate($dependanceTaches_params);

        // Récupérer les statistiques et les champs filtrables
        $dependanceTaches_stats = $this->dependanceTacheService->getdependanceTacheStats();
        $this->viewState->set('stats.dependanceTache.stats'  , $dependanceTaches_stats);
        $dependanceTaches_filters = $this->dependanceTacheService->getFieldsFilterable();
        $dependanceTache_instance =  $this->dependanceTacheService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGestionTaches::dependanceTache._table', compact('dependanceTaches_data', 'dependanceTaches_stats', 'dependanceTaches_filters','dependanceTache_instance'))->render();
        }

        return view('PkgGestionTaches::dependanceTache.index', compact('dependanceTaches_data', 'dependanceTaches_stats', 'dependanceTaches_filters','dependanceTache_instance'));
    }
    public function create() {


        $itemDependanceTache = $this->dependanceTacheService->createInstance();
        

        $taches = $this->tacheService->all();
        $taches = $this->tacheService->all();
        $typeDependanceTaches = $this->typeDependanceTacheService->all();

        if (request()->ajax()) {
            return view('PkgGestionTaches::dependanceTache._fields', compact('itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));
        }
        return view('PkgGestionTaches::dependanceTache.create', compact('itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));
    }
    public function store(DependanceTacheRequest $request) {
        $validatedData = $request->validated();
        $dependanceTache = $this->dependanceTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $dependanceTache,
                'modelName' => __('PkgGestionTaches::dependanceTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $dependanceTache->id]
            );
        }

        return redirect()->route('dependanceTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $dependanceTache,
                'modelName' => __('PkgGestionTaches::dependanceTache.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('dependanceTache.edit_' . $id);


        $itemDependanceTache = $this->dependanceTacheService->find($id);


        $taches = $this->tacheService->all();
        $taches = $this->tacheService->all();
        $typeDependanceTaches = $this->typeDependanceTacheService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::dependanceTache._fields', compact('itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));
        }

        return view('PkgGestionTaches::dependanceTache.edit', compact('itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('dependanceTache.edit_' . $id);


        $itemDependanceTache = $this->dependanceTacheService->find($id);


        $taches = $this->tacheService->all();
        $taches = $this->tacheService->all();
        $typeDependanceTaches = $this->typeDependanceTacheService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::dependanceTache._fields', compact('itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));
        }

        return view('PkgGestionTaches::dependanceTache.edit', compact('itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));

    }
    public function update(DependanceTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $dependanceTache = $this->dependanceTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $dependanceTache,
                'modelName' =>  __('PkgGestionTaches::dependanceTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $dependanceTache->id]
            );
        }

        return redirect()->route('dependanceTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $dependanceTache,
                'modelName' =>  __('PkgGestionTaches::dependanceTache.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $dependanceTache = $this->dependanceTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $dependanceTache,
                'modelName' =>  __('PkgGestionTaches::dependanceTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('dependanceTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $dependanceTache,
                'modelName' =>  __('PkgGestionTaches::dependanceTache.singular')
                ])
        );

    }

    public function export($format)
    {
        $dependanceTaches_data = $this->dependanceTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new DependanceTacheExport($dependanceTaches_data,'csv'), 'dependanceTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new DependanceTacheExport($dependanceTaches_data,'xlsx'), 'dependanceTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new DependanceTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('dependanceTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('dependanceTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::dependanceTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getDependanceTaches()
    {
        $dependanceTaches = $this->dependanceTacheService->all();
        return response()->json($dependanceTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $dependanceTache = $this->dependanceTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedDependanceTache = $this->dependanceTacheService->dataCalcul($dependanceTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedDependanceTache
        ]);
    }
    

}
