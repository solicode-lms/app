<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\RealisationTacheService;
use Modules\PkgGestionTaches\Services\EtatRealisationTacheService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgGestionTaches\Services\TacheService;
use Modules\PkgGestionTaches\Services\HistoriqueRealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\RealisationTacheRequest;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\RealisationTacheExport;
use Modules\PkgGestionTaches\App\Imports\RealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseRealisationTacheController extends AdminController
{
    protected $realisationTacheService;
    protected $etatRealisationTacheService;
    protected $realisationProjetService;
    protected $tacheService;

    public function __construct(RealisationTacheService $realisationTacheService, EtatRealisationTacheService $etatRealisationTacheService, RealisationProjetService $realisationProjetService, TacheService $tacheService) {
        parent::__construct();
        $this->service  =  $realisationTacheService;
        $this->realisationTacheService = $realisationTacheService;
        $this->etatRealisationTacheService = $etatRealisationTacheService;
        $this->realisationProjetService = $realisationProjetService;
        $this->tacheService = $tacheService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('realisationTache.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.realisationTache.realisationProjet.affectationProjet.projet.formateur_id') == null){
           $this->viewState->init('filter.realisationTache.realisationProjet.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('filter.realisationTache.realisationProjet.apprenant_id') == null){
           $this->viewState->init('filter.realisationTache.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $realisationTaches_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'realisationTaches_search',
                $this->viewState->get("filter.realisationTache.realisationTaches_search")
            )],
            $request->except(['realisationTaches_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationTacheService->prepareDataForIndexView($realisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($realisationTache_partialViewName, $realisationTache_compact_value)->render();
        }

        return view('PkgGestionTaches::realisationTache.index', $realisationTache_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationTache.realisationProjet.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationTache.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemRealisationTache = $this->realisationTacheService->createInstance();
        
        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);

        $taches = $this->tacheService->all();
        $realisationProjets = $this->realisationProjetService->all();
        $etatRealisationTaches = $this->etatRealisationTacheService->all();

        if (request()->ajax()) {
            return view('PkgGestionTaches::realisationTache._fields', compact('itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'taches'));
        }
        return view('PkgGestionTaches::realisationTache.create', compact('itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'taches'));
    }
    public function store(RealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $realisationTache = $this->realisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationTache,
                'modelName' => __('PkgGestionTaches::realisationTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $realisationTache->id]
            );
        }

        return redirect()->route('realisationTaches.edit',['realisationTache' => $realisationTache->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationTache,
                'modelName' => __('PkgGestionTaches::realisationTache.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('realisationTache.edit_' . $id);


        $itemRealisationTache = $this->realisationTacheService->edit($id);
        $this->authorize('view', $itemRealisationTache);

        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);

        $taches = $this->tacheService->all();
        $realisationProjets = $this->realisationProjetService->all();
        $etatRealisationTaches = $this->etatRealisationTacheService->all();


        $this->viewState->set('scope.historiqueRealisationTache.realisation_tache_id', $id);
        

        $historiqueRealisationTacheService =  new HistoriqueRealisationTacheService();
        $historiqueRealisationTaches_view_data = $historiqueRealisationTacheService->prepareDataForIndexView();
        extract($historiqueRealisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgGestionTaches::realisationTache._edit', array_merge(compact('itemRealisationTache','etatRealisationTaches', 'realisationProjets', 'taches'),$historiqueRealisationTache_compact_value));
        }

        return view('PkgGestionTaches::realisationTache.edit', array_merge(compact('itemRealisationTache','etatRealisationTaches', 'realisationProjets', 'taches'),$historiqueRealisationTache_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationTache.edit_' . $id);


        $itemRealisationTache = $this->realisationTacheService->edit($id);
        $this->authorize('edit', $itemRealisationTache);

        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);

        $taches = $this->tacheService->all();
        $realisationProjets = $this->realisationProjetService->all();
        $etatRealisationTaches = $this->etatRealisationTacheService->all();


        $this->viewState->set('scope.historiqueRealisationTache.realisation_tache_id', $id);
        

        $historiqueRealisationTacheService =  new HistoriqueRealisationTacheService();
        $historiqueRealisationTaches_view_data = $historiqueRealisationTacheService->prepareDataForIndexView();
        extract($historiqueRealisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgGestionTaches::realisationTache._edit', array_merge(compact('itemRealisationTache','etatRealisationTaches', 'realisationProjets', 'taches'),$historiqueRealisationTache_compact_value));
        }

        return view('PkgGestionTaches::realisationTache.edit', array_merge(compact('itemRealisationTache','etatRealisationTaches', 'realisationProjets', 'taches'),$historiqueRealisationTache_compact_value));


    }
    public function update(RealisationTacheRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationTache = $this->realisationTacheService->find($id);
        $this->authorize('update', $realisationTache);

        $validatedData = $request->validated();
        $realisationTache = $this->realisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationTache,
                'modelName' =>  __('PkgGestionTaches::realisationTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $realisationTache->id]
            );
        }

        return redirect()->route('realisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationTache,
                'modelName' =>  __('PkgGestionTaches::realisationTache.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationTache = $this->realisationTacheService->find($id);
        $this->authorize('delete', $realisationTache);

        $realisationTache = $this->realisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationTache,
                'modelName' =>  __('PkgGestionTaches::realisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationTache,
                'modelName' =>  __('PkgGestionTaches::realisationTache.singular')
                ])
        );

    }

    public function export($format)
    {
        $realisationTaches_data = $this->realisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationTacheExport($realisationTaches_data,'csv'), 'realisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationTacheExport($realisationTaches_data,'xlsx'), 'realisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::realisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationTaches()
    {
        $realisationTaches = $this->realisationTacheService->all();
        return response()->json($realisationTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $realisationTache = $this->realisationTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRealisationTache = $this->realisationTacheService->dataCalcul($realisationTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationTache
        ]);
    }
    

}