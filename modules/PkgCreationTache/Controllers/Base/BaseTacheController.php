<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationTache\Controllers\Base;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgCreationTache\Services\PrioriteTacheService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgRealisationTache\Services\DependanceTacheService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationTache\App\Requests\TacheRequest;
use Modules\PkgCreationTache\Models\Tache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationTache\App\Exports\TacheExport;
use Modules\PkgCreationTache\App\Imports\TacheImport;
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

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('tache.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('tache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $taches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'taches_search',
                $this->viewState->get("filter.tache.taches_search")
            )],
            $request->except(['taches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->tacheService->prepareDataForIndexView($taches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationTache::tache._index', $tache_compact_value)->render();
            }else{
                return view($tache_partialViewName, $tache_compact_value)->render();
            }
        }

        return view('PkgCreationTache::tache.index', $tache_compact_value);
    }
    /**
     */
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

        $prioriteTaches = $this->prioriteTacheService->all();
        $projets = $this->projetService->all();
        $livrables = $this->livrableService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationTache::tache._fields', compact('bulkEdit' ,'itemTache', 'livrables', 'prioriteTaches', 'projets'));
        }
        return view('PkgCreationTache::tache.create', compact('bulkEdit' ,'itemTache', 'livrables', 'prioriteTaches', 'projets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $tache_ids = $request->input('ids', []);

        if (!is_array($tache_ids) || count($tache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemTache = $this->tacheService->find($tache_ids[0]);
         
        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet.formateur_id');
        $key = 'scope.prioriteTache.formateur_id';
        $this->viewState->set($key, $value);
        // scopeDataInEditContext
        $value = $itemTache->getNestedValue('projet_id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);
 
        $prioriteTaches = $this->prioriteTacheService->all();
        $projets = $this->projetService->all();
        $livrables = $this->livrableService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemTache = $this->tacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationTache::tache._fields', compact('bulkEdit', 'tache_ids', 'itemTache', 'livrables', 'prioriteTaches', 'projets'));
        }
        return view('PkgCreationTache::tache.bulk-edit', compact('bulkEdit', 'tache_ids', 'itemTache', 'livrables', 'prioriteTaches', 'projets'));
    }
    /**
     */
    public function store(TacheRequest $request) {
        $validatedData = $request->validated();
        $tache = $this->tacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $tache,
                'modelName' => __('PkgCreationTache::tache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $tache->id]
            );
        }

        return redirect()->route('taches.edit',['tache' => $tache->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $tache,
                'modelName' => __('PkgCreationTache::tache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('tache.show_' . $id);

        $itemTache = $this->tacheService->edit($id);


        if (request()->ajax()) {
            return view('PkgCreationTache::tache._show', array_merge(compact('itemTache'),));
        }

        return view('PkgCreationTache::tache.show', array_merge(compact('itemTache'),));

    }
    /**
     */
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

        $prioriteTaches = $this->prioriteTacheService->all();
        $projets = $this->projetService->all();
        $livrables = $this->livrableService->all();


        $this->viewState->set('scope.dependanceTache.tache_id', $id);
        

        $dependanceTacheService =  new DependanceTacheService();
        $dependanceTaches_view_data = $dependanceTacheService->prepareDataForIndexView();
        extract($dependanceTaches_view_data);

        $this->viewState->set('scope.realisationTache.tache_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_view_data = $realisationTacheService->prepareDataForIndexView();
        extract($realisationTaches_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationTache::tache._edit', array_merge(compact('bulkEdit' , 'itemTache','livrables', 'prioriteTaches', 'projets'),$dependanceTache_compact_value, $realisationTache_compact_value));
        }

        return view('PkgCreationTache::tache.edit', array_merge(compact('bulkEdit' ,'itemTache','livrables', 'prioriteTaches', 'projets'),$dependanceTache_compact_value, $realisationTache_compact_value));


    }
    /**
     */
    public function update(TacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $tache = $this->tacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgCreationTache::tache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $tache->id]
            );
        }

        return redirect()->route('taches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgCreationTache::tache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $tache_ids = $request->input('tache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($tache_ids) || count($tache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($tache_ids as $id) {
            $entity = $this->tacheService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->tacheService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->tacheService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $tache = $this->tacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgCreationTache::tache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('taches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $tache,
                'modelName' =>  __('PkgCreationTache::tache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $tache_ids = $request->input('ids', []);
        if (!is_array($tache_ids) || count($tache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($tache_ids as $id) {
            $entity = $this->tacheService->find($id);
            $this->tacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($tache_ids) . ' éléments',
            'modelName' => __('PkgCreationTache::tache.plural')
        ]));
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
            'modelNames' =>  __('PkgCreationTache::tache.plural')
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
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $tacheRequest = new TacheRequest();
        $fullRules = $tacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:taches,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}