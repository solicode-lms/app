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

    public function __construct(DependanceTacheService $dependanceTacheService, TacheService $tacheService, TypeDependanceTacheService $typeDependanceTacheService) {
        parent::__construct();
        $this->service  =  $dependanceTacheService;
        $this->dependanceTacheService = $dependanceTacheService;
        $this->tacheService = $tacheService;
        $this->typeDependanceTacheService = $typeDependanceTacheService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('dependanceTache.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('dependanceTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $dependanceTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'dependanceTaches_search',
                $this->viewState->get("filter.dependanceTache.dependanceTaches_search")
            )],
            $request->except(['dependanceTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->dependanceTacheService->prepareDataForIndexView($dependanceTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGestionTaches::dependanceTache._index', $dependanceTache_compact_value)->render();
            }else{
                return view($dependanceTache_partialViewName, $dependanceTache_compact_value)->render();
            }
        }

        return view('PkgGestionTaches::dependanceTache.index', $dependanceTache_compact_value);
    }
    /**
     */
    public function create() {


        $itemDependanceTache = $this->dependanceTacheService->createInstance();
        

        $taches = $this->tacheService->all();
        $typeDependanceTaches = $this->typeDependanceTacheService->all();
        $taches = $this->tacheService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgGestionTaches::dependanceTache._fields', compact('bulkEdit' ,'itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));
        }
        return view('PkgGestionTaches::dependanceTache.create', compact('bulkEdit' ,'itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $dependanceTache_ids = $request->input('ids', []);

        if (!is_array($dependanceTache_ids) || count($dependanceTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemDependanceTache = $this->dependanceTacheService->find($dependanceTache_ids[0]);
         
 
        $taches = $this->tacheService->all();
        $typeDependanceTaches = $this->typeDependanceTacheService->all();
        $taches = $this->tacheService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemDependanceTache = $this->dependanceTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGestionTaches::dependanceTache._fields', compact('bulkEdit', 'dependanceTache_ids', 'itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));
        }
        return view('PkgGestionTaches::dependanceTache.bulk-edit', compact('bulkEdit', 'dependanceTache_ids', 'itemDependanceTache', 'taches', 'taches', 'typeDependanceTaches'));
    }
    /**
     */
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
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('dependanceTache.show_' . $id);

        $itemDependanceTache = $this->dependanceTacheService->edit($id);


        if (request()->ajax()) {
            return view('PkgGestionTaches::dependanceTache._show', array_merge(compact('itemDependanceTache'),));
        }

        return view('PkgGestionTaches::dependanceTache.show', array_merge(compact('itemDependanceTache'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('dependanceTache.edit_' . $id);


        $itemDependanceTache = $this->dependanceTacheService->edit($id);


        $taches = $this->tacheService->all();
        $typeDependanceTaches = $this->typeDependanceTacheService->all();
        $taches = $this->tacheService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgGestionTaches::dependanceTache._fields', array_merge(compact('bulkEdit' , 'itemDependanceTache','taches', 'taches', 'typeDependanceTaches'),));
        }

        return view('PkgGestionTaches::dependanceTache.edit', array_merge(compact('bulkEdit' ,'itemDependanceTache','taches', 'taches', 'typeDependanceTaches'),));


    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $dependanceTache_ids = $request->input('dependanceTache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($dependanceTache_ids) || count($dependanceTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($dependanceTache_ids as $id) {
            $entity = $this->dependanceTacheService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->dependanceTacheService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->dependanceTacheService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $dependanceTache_ids = $request->input('ids', []);
        if (!is_array($dependanceTache_ids) || count($dependanceTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($dependanceTache_ids as $id) {
            $entity = $this->dependanceTacheService->find($id);
            $this->dependanceTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($dependanceTache_ids) . ' éléments',
            'modelName' => __('PkgGestionTaches::dependanceTache.plural')
        ]));
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
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $dependanceTacheRequest = new DependanceTacheRequest();
        $fullRules = $dependanceTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:dependance_taches,id'];
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