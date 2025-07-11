<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Controllers\Base;
use Modules\PkgRealisationTache\Services\TypeDependanceTacheService;
use Modules\PkgRealisationTache\Services\DependanceTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationTache\App\Requests\TypeDependanceTacheRequest;
use Modules\PkgRealisationTache\Models\TypeDependanceTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationTache\App\Exports\TypeDependanceTacheExport;
use Modules\PkgRealisationTache\App\Imports\TypeDependanceTacheImport;
use Modules\Core\Services\ContextState;

class BaseTypeDependanceTacheController extends AdminController
{
    protected $typeDependanceTacheService;

    public function __construct(TypeDependanceTacheService $typeDependanceTacheService) {
        parent::__construct();
        $this->service  =  $typeDependanceTacheService;
        $this->typeDependanceTacheService = $typeDependanceTacheService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('typeDependanceTache.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('typeDependanceTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $typeDependanceTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'typeDependanceTaches_search',
                $this->viewState->get("filter.typeDependanceTache.typeDependanceTaches_search")
            )],
            $request->except(['typeDependanceTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->typeDependanceTacheService->prepareDataForIndexView($typeDependanceTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationTache::typeDependanceTache._index', $typeDependanceTache_compact_value)->render();
            }else{
                return view($typeDependanceTache_partialViewName, $typeDependanceTache_compact_value)->render();
            }
        }

        return view('PkgRealisationTache::typeDependanceTache.index', $typeDependanceTache_compact_value);
    }
    /**
     */
    public function create() {


        $itemTypeDependanceTache = $this->typeDependanceTacheService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationTache::typeDependanceTache._fields', compact('bulkEdit' ,'itemTypeDependanceTache'));
        }
        return view('PkgRealisationTache::typeDependanceTache.create', compact('bulkEdit' ,'itemTypeDependanceTache'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $typeDependanceTache_ids = $request->input('ids', []);

        if (!is_array($typeDependanceTache_ids) || count($typeDependanceTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemTypeDependanceTache = $this->typeDependanceTacheService->find($typeDependanceTache_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemTypeDependanceTache = $this->typeDependanceTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationTache::typeDependanceTache._fields', compact('bulkEdit', 'typeDependanceTache_ids', 'itemTypeDependanceTache'));
        }
        return view('PkgRealisationTache::typeDependanceTache.bulk-edit', compact('bulkEdit', 'typeDependanceTache_ids', 'itemTypeDependanceTache'));
    }
    /**
     */
    public function store(TypeDependanceTacheRequest $request) {
        $validatedData = $request->validated();
        $typeDependanceTache = $this->typeDependanceTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' => __('PkgRealisationTache::typeDependanceTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $typeDependanceTache->id]
            );
        }

        return redirect()->route('typeDependanceTaches.edit',['typeDependanceTache' => $typeDependanceTache->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' => __('PkgRealisationTache::typeDependanceTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('typeDependanceTache.show_' . $id);

        $itemTypeDependanceTache = $this->typeDependanceTacheService->edit($id);


        $this->viewState->set('scope.dependanceTache.type_dependance_tache_id', $id);
        

        $dependanceTacheService =  new DependanceTacheService();
        $dependanceTaches_view_data = $dependanceTacheService->prepareDataForIndexView();
        extract($dependanceTaches_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationTache::typeDependanceTache._show', array_merge(compact('itemTypeDependanceTache'),$dependanceTache_compact_value));
        }

        return view('PkgRealisationTache::typeDependanceTache.show', array_merge(compact('itemTypeDependanceTache'),$dependanceTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('typeDependanceTache.edit_' . $id);


        $itemTypeDependanceTache = $this->typeDependanceTacheService->edit($id);




        $this->viewState->set('scope.dependanceTache.type_dependance_tache_id', $id);
        

        $dependanceTacheService =  new DependanceTacheService();
        $dependanceTaches_view_data = $dependanceTacheService->prepareDataForIndexView();
        extract($dependanceTaches_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgRealisationTache::typeDependanceTache._edit', array_merge(compact('bulkEdit' , 'itemTypeDependanceTache',),$dependanceTache_compact_value));
        }

        return view('PkgRealisationTache::typeDependanceTache.edit', array_merge(compact('bulkEdit' ,'itemTypeDependanceTache',),$dependanceTache_compact_value));


    }
    /**
     */
    public function update(TypeDependanceTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $typeDependanceTache = $this->typeDependanceTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' =>  __('PkgRealisationTache::typeDependanceTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $typeDependanceTache->id]
            );
        }

        return redirect()->route('typeDependanceTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' =>  __('PkgRealisationTache::typeDependanceTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $typeDependanceTache_ids = $request->input('typeDependanceTache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($typeDependanceTache_ids) || count($typeDependanceTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($typeDependanceTache_ids as $id) {
            $entity = $this->typeDependanceTacheService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->typeDependanceTacheService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->typeDependanceTacheService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $typeDependanceTache = $this->typeDependanceTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' =>  __('PkgRealisationTache::typeDependanceTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('typeDependanceTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $typeDependanceTache,
                'modelName' =>  __('PkgRealisationTache::typeDependanceTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $typeDependanceTache_ids = $request->input('ids', []);
        if (!is_array($typeDependanceTache_ids) || count($typeDependanceTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($typeDependanceTache_ids as $id) {
            $entity = $this->typeDependanceTacheService->find($id);
            $this->typeDependanceTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($typeDependanceTache_ids) . ' éléments',
            'modelName' => __('PkgRealisationTache::typeDependanceTache.plural')
        ]));
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
            'modelNames' =>  __('PkgRealisationTache::typeDependanceTache.plural')
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
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $typeDependanceTacheRequest = new TypeDependanceTacheRequest();
        $fullRules = $typeDependanceTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:type_dependance_taches,id'];
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