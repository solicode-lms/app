<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\LabelRealisationTacheService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\Core\Services\SysColorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\LabelRealisationTacheRequest;
use Modules\PkgGestionTaches\Models\LabelRealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\LabelRealisationTacheExport;
use Modules\PkgGestionTaches\App\Imports\LabelRealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseLabelRealisationTacheController extends AdminController
{
    protected $labelRealisationTacheService;
    protected $formateurService;
    protected $sysColorService;

    public function __construct(LabelRealisationTacheService $labelRealisationTacheService, FormateurService $formateurService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $labelRealisationTacheService;
        $this->labelRealisationTacheService = $labelRealisationTacheService;
        $this->formateurService = $formateurService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('labelRealisationTache.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('labelRealisationTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.labelRealisationTache.formateur_id') == null){
           $this->viewState->init('scope.labelRealisationTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $labelRealisationTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'labelRealisationTaches_search',
                $this->viewState->get("filter.labelRealisationTache.labelRealisationTaches_search")
            )],
            $request->except(['labelRealisationTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->labelRealisationTacheService->prepareDataForIndexView($labelRealisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGestionTaches::labelRealisationTache._index', $labelRealisationTache_compact_value)->render();
            }else{
                return view($labelRealisationTache_partialViewName, $labelRealisationTache_compact_value)->render();
            }
        }

        return view('PkgGestionTaches::labelRealisationTache.index', $labelRealisationTache_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.labelRealisationTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemLabelRealisationTache = $this->labelRealisationTacheService->createInstance();
        

        $formateurs = $this->formateurService->all();
        $sysColors = $this->sysColorService->all();

        if (request()->ajax()) {
            return view('PkgGestionTaches::labelRealisationTache._fields', compact('itemLabelRealisationTache', 'formateurs', 'sysColors'));
        }
        return view('PkgGestionTaches::labelRealisationTache.create', compact('itemLabelRealisationTache', 'formateurs', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $labelRealisationTache_ids = $request->input('ids', []);

        if (!is_array($labelRealisationTache_ids) || count($labelRealisationTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.labelRealisationTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
         $itemLabelRealisationTache = $this->labelRealisationTacheService->find($labelRealisationTache_ids[0]);
         
 
        $formateurs = $this->formateurService->all();
        $sysColors = $this->sysColorService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemLabelRealisationTache = $this->labelRealisationTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGestionTaches::labelRealisationTache._fields', compact('bulkEdit', 'labelRealisationTache_ids', 'itemLabelRealisationTache', 'formateurs', 'sysColors'));
        }
        return view('PkgGestionTaches::labelRealisationTache.bulk-edit', compact('bulkEdit', 'labelRealisationTache_ids', 'itemLabelRealisationTache', 'formateurs', 'sysColors'));
    }
    /**
     */
    public function store(LabelRealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $labelRealisationTache = $this->labelRealisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' => __('PkgGestionTaches::labelRealisationTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $labelRealisationTache->id]
            );
        }

        return redirect()->route('labelRealisationTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' => __('PkgGestionTaches::labelRealisationTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('labelRealisationTache.show_' . $id);

        $itemLabelRealisationTache = $this->labelRealisationTacheService->edit($id);
        $this->authorize('view', $itemLabelRealisationTache);


        if (request()->ajax()) {
            return view('PkgGestionTaches::labelRealisationTache._show', array_merge(compact('itemLabelRealisationTache'),));
        }

        return view('PkgGestionTaches::labelRealisationTache.show', array_merge(compact('itemLabelRealisationTache'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('labelRealisationTache.edit_' . $id);


        $itemLabelRealisationTache = $this->labelRealisationTacheService->edit($id);
        $this->authorize('edit', $itemLabelRealisationTache);


        $formateurs = $this->formateurService->all();
        $sysColors = $this->sysColorService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgGestionTaches::labelRealisationTache._fields', array_merge(compact('bulkEdit' , 'itemLabelRealisationTache','formateurs', 'sysColors'),));
        }

        return view('PkgGestionTaches::labelRealisationTache.edit', array_merge(compact('itemLabelRealisationTache','formateurs', 'sysColors'),));


    }
    /**
     */
    public function update(LabelRealisationTacheRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $labelRealisationTache = $this->labelRealisationTacheService->find($id);
        $this->authorize('update', $labelRealisationTache);

        $validatedData = $request->validated();
        $labelRealisationTache = $this->labelRealisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' =>  __('PkgGestionTaches::labelRealisationTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $labelRealisationTache->id]
            );
        }

        return redirect()->route('labelRealisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' =>  __('PkgGestionTaches::labelRealisationTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $labelRealisationTache_ids = $request->input('labelRealisationTache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($labelRealisationTache_ids) || count($labelRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($labelRealisationTache_ids as $id) {
            $entity = $this->labelRealisationTacheService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->labelRealisationTacheService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->labelRealisationTacheService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $labelRealisationTache = $this->labelRealisationTacheService->find($id);
        $this->authorize('delete', $labelRealisationTache);

        $labelRealisationTache = $this->labelRealisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' =>  __('PkgGestionTaches::labelRealisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('labelRealisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' =>  __('PkgGestionTaches::labelRealisationTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $labelRealisationTache_ids = $request->input('ids', []);
        if (!is_array($labelRealisationTache_ids) || count($labelRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($labelRealisationTache_ids as $id) {
            $entity = $this->labelRealisationTacheService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $labelRealisationTache = $this->labelRealisationTacheService->find($id);
            $this->authorize('delete', $labelRealisationTache);
            $this->labelRealisationTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($labelRealisationTache_ids) . ' éléments',
            'modelName' => __('PkgGestionTaches::labelRealisationTache.plural')
        ]));
    }

    public function export($format)
    {
        $labelRealisationTaches_data = $this->labelRealisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new LabelRealisationTacheExport($labelRealisationTaches_data,'csv'), 'labelRealisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new LabelRealisationTacheExport($labelRealisationTaches_data,'xlsx'), 'labelRealisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new LabelRealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('labelRealisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('labelRealisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::labelRealisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLabelRealisationTaches()
    {
        $labelRealisationTaches = $this->labelRealisationTacheService->all();
        return response()->json($labelRealisationTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $labelRealisationTache = $this->labelRealisationTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedLabelRealisationTache = $this->labelRealisationTacheService->dataCalcul($labelRealisationTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedLabelRealisationTache
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
        $labelRealisationTacheRequest = new LabelRealisationTacheRequest();
        $fullRules = $labelRealisationTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:label_realisation_taches,id'];
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