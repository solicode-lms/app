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
        



         // Extraire les paramètres de recherche, pagination, filtres
        $taches_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'taches_search',
                $this->viewState->get("filter.tache.taches_search")
            )],
            $request->except(['taches_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->tacheService->prepareDataForIndexView($taches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGestionTaches::tache._index', $tache_compact_value)->render();
            }else{
                return view($tache_partialViewName, $tache_compact_value)->render();
            }
        }

        return view('PkgGestionTaches::tache.index', $tache_compact_value);
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


        $itemTache = $this->tacheService->edit($id);

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
        $dependanceTaches_view_data = $dependanceTacheService->prepareDataForIndexView();
        extract($dependanceTaches_view_data);

        $this->viewState->set('scope.realisationTache.tache_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_view_data = $realisationTacheService->prepareDataForIndexView();
        extract($realisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgGestionTaches::tache._edit', array_merge(compact('itemTache','livrables', 'prioriteTaches', 'projets'),$dependanceTache_compact_value, $realisationTache_compact_value));
        }

        return view('PkgGestionTaches::tache.edit', array_merge(compact('itemTache','livrables', 'prioriteTaches', 'projets'),$dependanceTache_compact_value, $realisationTache_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('tache.edit_' . $id);


        $itemTache = $this->tacheService->edit($id);

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
        $dependanceTaches_view_data = $dependanceTacheService->prepareDataForIndexView();
        extract($dependanceTaches_view_data);

        $this->viewState->set('scope.realisationTache.tache_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_view_data = $realisationTacheService->prepareDataForIndexView();
        extract($realisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgGestionTaches::tache._edit', array_merge(compact('itemTache','livrables', 'prioriteTaches', 'projets'),$dependanceTache_compact_value, $realisationTache_compact_value));
        }

        return view('PkgGestionTaches::tache.edit', array_merge(compact('itemTache','livrables', 'prioriteTaches', 'projets'),$dependanceTache_compact_value, $realisationTache_compact_value));


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