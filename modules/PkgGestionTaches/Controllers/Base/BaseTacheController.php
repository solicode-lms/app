<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\TacheService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgGestionTaches\Services\PrioriteTacheService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgGestionTaches\Services\DependanceTacheService;
use Modules\PkgGestionTaches\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\TacheRequest;
use Modules\PkgGestionTaches\Models\Tache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\TacheExport;
use Modules\PkgGestionTaches\App\Imports\TacheImport;
use Modules\Core\Services\ContextState;

class BaseTacheController extends AdminController
{
    protected $tacheService;
    protected $livrableService;
    protected $prioriteTacheService;
    protected $projetService;

    public function __construct(TacheService $tacheService, LivrableService $livrableService, PrioriteTacheService $prioriteTacheService, ProjetService $projetService) {
        parent::__construct();
        $this->service  =  $tacheService;
        $this->tacheService = $tacheService;
        $this->livrableService = $livrableService;
        $this->prioriteTacheService = $prioriteTacheService;
        $this->projetService = $projetService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('tache.index');



        // Extraire les paramètres de recherche, page, et filtres
        $taches_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('taches_search', $this->viewState->get("filter.tache.taches_search"))],
            $request->except(['taches_search', 'page', 'sort'])
        );

        // Paginer les taches
        $taches_data = $this->tacheService->paginate($taches_params);

        // Récupérer les statistiques et les champs filtrables
        $taches_stats = $this->tacheService->gettacheStats();
        $this->viewState->set('stats.tache.stats'  , $taches_stats);
        $taches_filters = $this->tacheService->getFieldsFilterable();
        $tache_instance =  $this->tacheService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGestionTaches::tache._table', compact('taches_data', 'taches_stats', 'taches_filters','tache_instance'))->render();
        }

        return view('PkgGestionTaches::tache.index', compact('taches_data', 'taches_stats', 'taches_filters','tache_instance'));
    }
    public function create() {


        $itemTache = $this->tacheService->createInstance();
        
        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet.formateur_id');
        $key = 'scope.prioriteTache.formateur_id';
        $this->viewState->set($key, $value);
        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet_id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $projets = $this->projetService->all();
        $prioriteTaches = $this->prioriteTacheService->all();
        $livrables = $this->livrableService->all();

        if (request()->ajax()) {
            return view('PkgGestionTaches::tache._fields', compact('itemTache', 'livrables', 'prioriteTaches', 'projets'));
        }
        return view('PkgGestionTaches::tache.create', compact('itemTache', 'livrables', 'prioriteTaches', 'projets'));
    }
    public function store(TacheRequest $request) {
        $validatedData = $request->validated();
        $tache = $this->tacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $tache,
                'modelName' => __('PkgGestionTaches::tache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $tache->id]
            );
        }

        return redirect()->route('taches.edit',['tache' => $tache->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $tache,
                'modelName' => __('PkgGestionTaches::tache.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('tache.edit_' . $id);


        $itemTache = $this->tacheService->find($id);

        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet.formateur_id');
        $key = 'scope.prioriteTache.formateur_id';
        $this->viewState->set($key, $value);
        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet_id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $projets = $this->projetService->all();
        $prioriteTaches = $this->prioriteTacheService->all();
        $livrables = $this->livrableService->all();
        

        $this->viewState->set('scope.dependanceTache.tache_id', $id);


        $dependanceTacheService =  new DependanceTacheService();
        $dependanceTaches_data =  $dependanceTacheService->paginate();
        $dependanceTaches_stats = $dependanceTacheService->getdependanceTacheStats();
        $dependanceTaches_filters = $dependanceTacheService->getFieldsFilterable();
        $dependanceTache_instance =  $dependanceTacheService->createInstance();

        $this->viewState->set('scope.realisationTache.tache_id', $id);


        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_data =  $realisationTacheService->paginate();
        $realisationTaches_stats = $realisationTacheService->getrealisationTacheStats();
        $realisationTaches_filters = $realisationTacheService->getFieldsFilterable();
        $realisationTache_instance =  $realisationTacheService->createInstance();

        if (request()->ajax()) {
            return view('PkgGestionTaches::tache._edit', compact('itemTache', 'livrables', 'prioriteTaches', 'projets', 'dependanceTaches_data', 'realisationTaches_data', 'dependanceTaches_stats', 'realisationTaches_stats', 'dependanceTaches_filters', 'realisationTaches_filters', 'dependanceTache_instance', 'realisationTache_instance'));
        }

        return view('PkgGestionTaches::tache.edit', compact('itemTache', 'livrables', 'prioriteTaches', 'projets', 'dependanceTaches_data', 'realisationTaches_data', 'dependanceTaches_stats', 'realisationTaches_stats', 'dependanceTaches_filters', 'realisationTaches_filters', 'dependanceTache_instance', 'realisationTache_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('tache.edit_' . $id);


        $itemTache = $this->tacheService->find($id);

        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet.formateur_id');
        $key = 'scope.prioriteTache.formateur_id';
        $this->viewState->set($key, $value);
        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet_id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $projets = $this->projetService->all();
        $prioriteTaches = $this->prioriteTacheService->all();
        $livrables = $this->livrableService->all();


        $this->viewState->set('scope.dependanceTache.tache_id', $id);
        

        $dependanceTacheService =  new DependanceTacheService();
        $dependanceTaches_data =  $dependanceTacheService->paginate();
        $dependanceTaches_stats = $dependanceTacheService->getdependanceTacheStats();
        $this->viewState->set('stats.dependanceTache.stats'  , $dependanceTaches_stats);
        $dependanceTaches_filters = $dependanceTacheService->getFieldsFilterable();
        $dependanceTache_instance =  $dependanceTacheService->createInstance();

        $this->viewState->set('scope.realisationTache.tache_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_data =  $realisationTacheService->paginate();
        $realisationTaches_stats = $realisationTacheService->getrealisationTacheStats();
        $this->viewState->set('stats.realisationTache.stats'  , $realisationTaches_stats);
        $realisationTaches_filters = $realisationTacheService->getFieldsFilterable();
        $realisationTache_instance =  $realisationTacheService->createInstance();

        if (request()->ajax()) {
            return view('PkgGestionTaches::tache._edit', compact('itemTache', 'livrables', 'prioriteTaches', 'projets', 'dependanceTaches_data', 'realisationTaches_data', 'dependanceTaches_stats', 'realisationTaches_stats', 'dependanceTaches_filters', 'realisationTaches_filters', 'dependanceTache_instance', 'realisationTache_instance'));
        }

        return view('PkgGestionTaches::tache.edit', compact('itemTache', 'livrables', 'prioriteTaches', 'projets', 'dependanceTaches_data', 'realisationTaches_data', 'dependanceTaches_stats', 'realisationTaches_stats', 'dependanceTaches_filters', 'realisationTaches_filters', 'dependanceTache_instance', 'realisationTache_instance'));

    }
    public function update(TacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $tache = $this->tacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgGestionTaches::tache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $tache->id]
            );
        }

        return redirect()->route('taches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgGestionTaches::tache.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $tache = $this->tacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgGestionTaches::tache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('taches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgGestionTaches::tache.singular')
                ])
        );

    }

    public function export($format)
    {
        $taches_data = $this->tacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new TacheExport($taches_data,'csv'), 'tache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new TacheExport($taches_data,'xlsx'), 'tache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new TacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('taches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('taches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::tache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTaches()
    {
        $taches = $this->tacheService->all();
        return response()->json($taches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $tache = $this->tacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedTache = $this->tacheService->dataCalcul($tache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedTache
        ]);
    }
    

}
