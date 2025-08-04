<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\RealisationUaPrototypeService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\RealisationUaPrototypeRequest;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprentissage\App\Exports\RealisationUaPrototypeExport;
use Modules\PkgApprentissage\App\Imports\RealisationUaPrototypeImport;
use Modules\Core\Services\ContextState;

class BaseRealisationUaPrototypeController extends AdminController
{
    protected $realisationUaPrototypeService;
    protected $realisationTacheService;
    protected $realisationUaService;

    public function __construct(RealisationUaPrototypeService $realisationUaPrototypeService, RealisationTacheService $realisationTacheService, RealisationUaService $realisationUaService) {
        parent::__construct();
        $this->service  =  $realisationUaPrototypeService;
        $this->realisationUaPrototypeService = $realisationUaPrototypeService;
        $this->realisationTacheService = $realisationTacheService;
        $this->realisationUaService = $realisationUaService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationUaPrototype.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationUaPrototype');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $realisationUaPrototypes_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationUaPrototypes_search',
                $this->viewState->get("filter.realisationUaPrototype.realisationUaPrototypes_search")
            )],
            $request->except(['realisationUaPrototypes_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationUaPrototypeService->prepareDataForIndexView($realisationUaPrototypes_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::realisationUaPrototype._index', $realisationUaPrototype_compact_value)->render();
            }else{
                return view($realisationUaPrototype_partialViewName, $realisationUaPrototype_compact_value)->render();
            }
        }

        return view('PkgApprentissage::realisationUaPrototype.index', $realisationUaPrototype_compact_value);
    }
    /**
     */
    public function create() {


        $itemRealisationUaPrototype = $this->realisationUaPrototypeService->createInstance();
        

        $realisationTaches = $this->realisationTacheService->all();
        $realisationUas = $this->realisationUaService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaPrototype._fields', compact('bulkEdit' ,'itemRealisationUaPrototype', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationUaPrototype.create', compact('bulkEdit' ,'itemRealisationUaPrototype', 'realisationTaches', 'realisationUas'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationUaPrototype_ids = $request->input('ids', []);

        if (!is_array($realisationUaPrototype_ids) || count($realisationUaPrototype_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRealisationUaPrototype = $this->realisationUaPrototypeService->find($realisationUaPrototype_ids[0]);
         
 
        $realisationTaches = $this->realisationTacheService->all();
        $realisationUas = $this->realisationUaService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationUaPrototype = $this->realisationUaPrototypeService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaPrototype._fields', compact('bulkEdit', 'realisationUaPrototype_ids', 'itemRealisationUaPrototype', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationUaPrototype.bulk-edit', compact('bulkEdit', 'realisationUaPrototype_ids', 'itemRealisationUaPrototype', 'realisationTaches', 'realisationUas'));
    }
    /**
     */
    public function store(RealisationUaPrototypeRequest $request) {
        $validatedData = $request->validated();
        $realisationUaPrototype = $this->realisationUaPrototypeService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' => __('PkgApprentissage::realisationUaPrototype.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $realisationUaPrototype->id]
            );
        }

        return redirect()->route('realisationUaPrototypes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' => __('PkgApprentissage::realisationUaPrototype.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationUaPrototype.show_' . $id);

        $itemRealisationUaPrototype = $this->realisationUaPrototypeService->edit($id);


        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaPrototype._show', array_merge(compact('itemRealisationUaPrototype'),));
        }

        return view('PkgApprentissage::realisationUaPrototype.show', array_merge(compact('itemRealisationUaPrototype'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationUaPrototype.edit_' . $id);


        $itemRealisationUaPrototype = $this->realisationUaPrototypeService->edit($id);


        $realisationTaches = $this->realisationTacheService->all();
        $realisationUas = $this->realisationUaService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaPrototype._fields', array_merge(compact('bulkEdit' , 'itemRealisationUaPrototype','realisationTaches', 'realisationUas'),));
        }

        return view('PkgApprentissage::realisationUaPrototype.edit', array_merge(compact('bulkEdit' ,'itemRealisationUaPrototype','realisationTaches', 'realisationUas'),));


    }
    /**
     */
    public function update(RealisationUaPrototypeRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationUaPrototype = $this->realisationUaPrototypeService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' =>  __('PkgApprentissage::realisationUaPrototype.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $realisationUaPrototype->id]
            );
        }

        return redirect()->route('realisationUaPrototypes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' =>  __('PkgApprentissage::realisationUaPrototype.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $realisationUaPrototype_ids = $request->input('realisationUaPrototype_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($realisationUaPrototype_ids) || count($realisationUaPrototype_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($realisationUaPrototype_ids as $id) {
            $entity = $this->realisationUaPrototypeService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->realisationUaPrototypeService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->realisationUaPrototypeService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $realisationUaPrototype = $this->realisationUaPrototypeService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' =>  __('PkgApprentissage::realisationUaPrototype.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationUaPrototypes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationUaPrototype,
                'modelName' =>  __('PkgApprentissage::realisationUaPrototype.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationUaPrototype_ids = $request->input('ids', []);
        if (!is_array($realisationUaPrototype_ids) || count($realisationUaPrototype_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationUaPrototype_ids as $id) {
            $entity = $this->realisationUaPrototypeService->find($id);
            $this->realisationUaPrototypeService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationUaPrototype_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::realisationUaPrototype.plural')
        ]));
    }

    public function export($format)
    {
        $realisationUaPrototypes_data = $this->realisationUaPrototypeService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationUaPrototypeExport($realisationUaPrototypes_data,'csv'), 'realisationUaPrototype_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationUaPrototypeExport($realisationUaPrototypes_data,'xlsx'), 'realisationUaPrototype_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationUaPrototypeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationUaPrototypes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationUaPrototypes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::realisationUaPrototype.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationUaPrototypes()
    {
        $realisationUaPrototypes = $this->realisationUaPrototypeService->all();
        return response()->json($realisationUaPrototypes);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $realisationUaPrototype = $this->realisationUaPrototypeService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRealisationUaPrototype = $this->realisationUaPrototypeService->dataCalcul($realisationUaPrototype);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationUaPrototype
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
        $realisationUaPrototypeRequest = new RealisationUaPrototypeRequest();
        $fullRules = $realisationUaPrototypeRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_ua_prototypes,id'];
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