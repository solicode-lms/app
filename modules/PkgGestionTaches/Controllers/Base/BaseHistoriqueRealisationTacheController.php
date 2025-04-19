<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\HistoriqueRealisationTacheService;
use Modules\PkgGestionTaches\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\HistoriqueRealisationTacheRequest;
use Modules\PkgGestionTaches\Models\HistoriqueRealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\HistoriqueRealisationTacheExport;
use Modules\PkgGestionTaches\App\Imports\HistoriqueRealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseHistoriqueRealisationTacheController extends AdminController
{
    protected $historiqueRealisationTacheService;
    protected $realisationTacheService;

    public function __construct(HistoriqueRealisationTacheService $historiqueRealisationTacheService, RealisationTacheService $realisationTacheService) {
        parent::__construct();
        $this->service  =  $historiqueRealisationTacheService;
        $this->historiqueRealisationTacheService = $historiqueRealisationTacheService;
        $this->realisationTacheService = $realisationTacheService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('historiqueRealisationTache.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('historiqueRealisationTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $historiqueRealisationTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'historiqueRealisationTaches_search',
                $this->viewState->get("filter.historiqueRealisationTache.historiqueRealisationTaches_search")
            )],
            $request->except(['historiqueRealisationTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->historiqueRealisationTacheService->prepareDataForIndexView($historiqueRealisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGestionTaches::historiqueRealisationTache._index', $historiqueRealisationTache_compact_value)->render();
            }else{
                return view($historiqueRealisationTache_partialViewName, $historiqueRealisationTache_compact_value)->render();
            }
        }

        return view('PkgGestionTaches::historiqueRealisationTache.index', $historiqueRealisationTache_compact_value);
    }
    /**
     */
    public function create() {


        $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->createInstance();
        

        $realisationTaches = $this->realisationTacheService->all();

        if (request()->ajax()) {
            return view('PkgGestionTaches::historiqueRealisationTache._fields', compact('itemHistoriqueRealisationTache', 'realisationTaches'));
        }
        return view('PkgGestionTaches::historiqueRealisationTache.create', compact('itemHistoriqueRealisationTache', 'realisationTaches'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $historiqueRealisationTache_ids = $request->input('ids', []);

        if (!is_array($historiqueRealisationTache_ids) || count($historiqueRealisationTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->find($historiqueRealisationTache_ids[0]);
         
 
        $realisationTaches = $this->realisationTacheService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGestionTaches::historiqueRealisationTache._fields', compact('bulkEdit', 'historiqueRealisationTache_ids', 'itemHistoriqueRealisationTache', 'realisationTaches'));
        }
        return view('PkgGestionTaches::historiqueRealisationTache.bulk-edit', compact('bulkEdit', 'historiqueRealisationTache_ids', 'itemHistoriqueRealisationTache', 'realisationTaches'));
    }
    /**
     */
    public function store(HistoriqueRealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $historiqueRealisationTache = $this->historiqueRealisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' => __('PkgGestionTaches::historiqueRealisationTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $historiqueRealisationTache->id]
            );
        }

        return redirect()->route('historiqueRealisationTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' => __('PkgGestionTaches::historiqueRealisationTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('historiqueRealisationTache.edit_' . $id);


        $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->edit($id);


        $realisationTaches = $this->realisationTacheService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::historiqueRealisationTache._fields', array_merge(compact('itemHistoriqueRealisationTache','realisationTaches'),));
        }

        return view('PkgGestionTaches::historiqueRealisationTache.edit', array_merge(compact('itemHistoriqueRealisationTache','realisationTaches'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('historiqueRealisationTache.edit_' . $id);


        $itemHistoriqueRealisationTache = $this->historiqueRealisationTacheService->edit($id);


        $realisationTaches = $this->realisationTacheService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::historiqueRealisationTache._fields', array_merge(compact('itemHistoriqueRealisationTache','realisationTaches'),));
        }

        return view('PkgGestionTaches::historiqueRealisationTache.edit', array_merge(compact('itemHistoriqueRealisationTache','realisationTaches'),));


    }
    /**
     */
    public function update(HistoriqueRealisationTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $historiqueRealisationTache = $this->historiqueRealisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' =>  __('PkgGestionTaches::historiqueRealisationTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $historiqueRealisationTache->id]
            );
        }

        return redirect()->route('historiqueRealisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' =>  __('PkgGestionTaches::historiqueRealisationTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $historiqueRealisationTache_ids = $request->input('historiqueRealisationTache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($historiqueRealisationTache_ids) || count($historiqueRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($historiqueRealisationTache_ids as $id) {
            $entity = $this->historiqueRealisationTacheService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->historiqueRealisationTacheService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->historiqueRealisationTacheService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $historiqueRealisationTache = $this->historiqueRealisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' =>  __('PkgGestionTaches::historiqueRealisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('historiqueRealisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $historiqueRealisationTache,
                'modelName' =>  __('PkgGestionTaches::historiqueRealisationTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $historiqueRealisationTache_ids = $request->input('ids', []);
        if (!is_array($historiqueRealisationTache_ids) || count($historiqueRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($historiqueRealisationTache_ids as $id) {
            $entity = $this->historiqueRealisationTacheService->find($id);
            $this->historiqueRealisationTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($historiqueRealisationTache_ids) . ' éléments',
            'modelName' => __('PkgGestionTaches::historiqueRealisationTache.plural')
        ]));
    }

    public function export($format)
    {
        $historiqueRealisationTaches_data = $this->historiqueRealisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new HistoriqueRealisationTacheExport($historiqueRealisationTaches_data,'csv'), 'historiqueRealisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new HistoriqueRealisationTacheExport($historiqueRealisationTaches_data,'xlsx'), 'historiqueRealisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new HistoriqueRealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('historiqueRealisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('historiqueRealisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::historiqueRealisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getHistoriqueRealisationTaches()
    {
        $historiqueRealisationTaches = $this->historiqueRealisationTacheService->all();
        return response()->json($historiqueRealisationTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $historiqueRealisationTache = $this->historiqueRealisationTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedHistoriqueRealisationTache = $this->historiqueRealisationTacheService->dataCalcul($historiqueRealisationTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedHistoriqueRealisationTache
        ]);
    }
    

}