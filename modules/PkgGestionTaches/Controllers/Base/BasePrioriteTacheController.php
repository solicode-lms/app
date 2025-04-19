<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\PrioriteTacheService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\PrioriteTacheRequest;
use Modules\PkgGestionTaches\Models\PrioriteTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\PrioriteTacheExport;
use Modules\PkgGestionTaches\App\Imports\PrioriteTacheImport;
use Modules\Core\Services\ContextState;

class BasePrioriteTacheController extends AdminController
{
    protected $prioriteTacheService;
    protected $formateurService;

    public function __construct(PrioriteTacheService $prioriteTacheService, FormateurService $formateurService) {
        parent::__construct();
        $this->service  =  $prioriteTacheService;
        $this->prioriteTacheService = $prioriteTacheService;
        $this->formateurService = $formateurService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('prioriteTache.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('prioriteTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.prioriteTache.formateur_id') == null){
           $this->viewState->init('scope.prioriteTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $prioriteTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'prioriteTaches_search',
                $this->viewState->get("filter.prioriteTache.prioriteTaches_search")
            )],
            $request->except(['prioriteTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->prioriteTacheService->prepareDataForIndexView($prioriteTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGestionTaches::prioriteTache._index', $prioriteTache_compact_value)->render();
            }else{
                return view($prioriteTache_partialViewName, $prioriteTache_compact_value)->render();
            }
        }

        return view('PkgGestionTaches::prioriteTache.index', $prioriteTache_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.prioriteTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemPrioriteTache = $this->prioriteTacheService->createInstance();
        

        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgGestionTaches::prioriteTache._fields', compact('itemPrioriteTache', 'formateurs'));
        }
        return view('PkgGestionTaches::prioriteTache.create', compact('itemPrioriteTache', 'formateurs'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $prioriteTache_ids = $request->input('ids', []);

        if (!is_array($prioriteTache_ids) || count($prioriteTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.prioriteTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
         $itemPrioriteTache = $this->prioriteTacheService->find($prioriteTache_ids[0]);
         
 
        $formateurs = $this->formateurService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemPrioriteTache = $this->prioriteTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGestionTaches::prioriteTache._fields', compact('bulkEdit', 'prioriteTache_ids', 'itemPrioriteTache', 'formateurs'));
        }
        return view('PkgGestionTaches::prioriteTache.bulk-edit', compact('bulkEdit', 'prioriteTache_ids', 'itemPrioriteTache', 'formateurs'));
    }
    /**
     */
    public function store(PrioriteTacheRequest $request) {
        $validatedData = $request->validated();
        $prioriteTache = $this->prioriteTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' => __('PkgGestionTaches::prioriteTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $prioriteTache->id]
            );
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' => __('PkgGestionTaches::prioriteTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('prioriteTache.edit_' . $id);


        $itemPrioriteTache = $this->prioriteTacheService->edit($id);
        $this->authorize('view', $itemPrioriteTache);


        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::prioriteTache._fields', array_merge(compact('itemPrioriteTache','formateurs'),));
        }

        return view('PkgGestionTaches::prioriteTache.edit', array_merge(compact('itemPrioriteTache','formateurs'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('prioriteTache.edit_' . $id);


        $itemPrioriteTache = $this->prioriteTacheService->edit($id);
        $this->authorize('edit', $itemPrioriteTache);


        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::prioriteTache._fields', array_merge(compact('itemPrioriteTache','formateurs'),));
        }

        return view('PkgGestionTaches::prioriteTache.edit', array_merge(compact('itemPrioriteTache','formateurs'),));


    }
    /**
     */
    public function update(PrioriteTacheRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $prioriteTache = $this->prioriteTacheService->find($id);
        $this->authorize('update', $prioriteTache);

        $validatedData = $request->validated();
        $prioriteTache = $this->prioriteTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgGestionTaches::prioriteTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $prioriteTache->id]
            );
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgGestionTaches::prioriteTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $prioriteTache_ids = $request->input('prioriteTache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($prioriteTache_ids) || count($prioriteTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($prioriteTache_ids as $id) {
            $entity = $this->prioriteTacheService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->prioriteTacheService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->prioriteTacheService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $prioriteTache = $this->prioriteTacheService->find($id);
        $this->authorize('delete', $prioriteTache);

        $prioriteTache = $this->prioriteTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgGestionTaches::prioriteTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgGestionTaches::prioriteTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $prioriteTache_ids = $request->input('ids', []);
        if (!is_array($prioriteTache_ids) || count($prioriteTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($prioriteTache_ids as $id) {
            $entity = $this->prioriteTacheService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $prioriteTache = $this->prioriteTacheService->find($id);
            $this->authorize('delete', $prioriteTache);
            $this->prioriteTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($prioriteTache_ids) . ' éléments',
            'modelName' => __('PkgGestionTaches::prioriteTache.plural')
        ]));
    }

    public function export($format)
    {
        $prioriteTaches_data = $this->prioriteTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new PrioriteTacheExport($prioriteTaches_data,'csv'), 'prioriteTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new PrioriteTacheExport($prioriteTaches_data,'xlsx'), 'prioriteTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new PrioriteTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('prioriteTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::prioriteTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getPrioriteTaches()
    {
        $prioriteTaches = $this->prioriteTacheService->all();
        return response()->json($prioriteTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $prioriteTache = $this->prioriteTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedPrioriteTache = $this->prioriteTacheService->dataCalcul($prioriteTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedPrioriteTache
        ]);
    }
    

}