<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\EtatRealisationTacheService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\Core\Services\SysColorService;
use Modules\PkgGestionTaches\Services\WorkflowTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\EtatRealisationTacheRequest;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\EtatRealisationTacheExport;
use Modules\PkgGestionTaches\App\Imports\EtatRealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseEtatRealisationTacheController extends AdminController
{
    protected $etatRealisationTacheService;
    protected $formateurService;
    protected $sysColorService;
    protected $workflowTacheService;

    public function __construct(EtatRealisationTacheService $etatRealisationTacheService, FormateurService $formateurService, SysColorService $sysColorService, WorkflowTacheService $workflowTacheService) {
        parent::__construct();
        $this->service  =  $etatRealisationTacheService;
        $this->etatRealisationTacheService = $etatRealisationTacheService;
        $this->formateurService = $formateurService;
        $this->sysColorService = $sysColorService;
        $this->workflowTacheService = $workflowTacheService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('etatRealisationTache.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('etatRealisationTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.etatRealisationTache.formateur_id') == null){
           $this->viewState->init('scope.etatRealisationTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $etatRealisationTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatRealisationTaches_search',
                $this->viewState->get("filter.etatRealisationTache.etatRealisationTaches_search")
            )],
            $request->except(['etatRealisationTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatRealisationTacheService->prepareDataForIndexView($etatRealisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGestionTaches::etatRealisationTache._index', $etatRealisationTache_compact_value)->render();
            }else{
                return view($etatRealisationTache_partialViewName, $etatRealisationTache_compact_value)->render();
            }
        }

        return view('PkgGestionTaches::etatRealisationTache.index', $etatRealisationTache_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.etatRealisationTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemEtatRealisationTache = $this->etatRealisationTacheService->createInstance();
        

        $workflowTaches = $this->workflowTacheService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgGestionTaches::etatRealisationTache._fields', compact('bulkEdit' ,'itemEtatRealisationTache', 'formateurs', 'sysColors', 'workflowTaches'));
        }
        return view('PkgGestionTaches::etatRealisationTache.create', compact('bulkEdit' ,'itemEtatRealisationTache', 'formateurs', 'sysColors', 'workflowTaches'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatRealisationTache_ids = $request->input('ids', []);

        if (!is_array($etatRealisationTache_ids) || count($etatRealisationTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.etatRealisationTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
         $itemEtatRealisationTache = $this->etatRealisationTacheService->find($etatRealisationTache_ids[0]);
         
 
        $workflowTaches = $this->workflowTacheService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatRealisationTache = $this->etatRealisationTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGestionTaches::etatRealisationTache._fields', compact('bulkEdit', 'etatRealisationTache_ids', 'itemEtatRealisationTache', 'formateurs', 'sysColors', 'workflowTaches'));
        }
        return view('PkgGestionTaches::etatRealisationTache.bulk-edit', compact('bulkEdit', 'etatRealisationTache_ids', 'itemEtatRealisationTache', 'formateurs', 'sysColors', 'workflowTaches'));
    }
    /**
     */
    public function store(EtatRealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $etatRealisationTache = $this->etatRealisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationTache,
                'modelName' => __('PkgGestionTaches::etatRealisationTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $etatRealisationTache->id]
            );
        }

        return redirect()->route('etatRealisationTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationTache,
                'modelName' => __('PkgGestionTaches::etatRealisationTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatRealisationTache.show_' . $id);

        $itemEtatRealisationTache = $this->etatRealisationTacheService->edit($id);
        $this->authorize('view', $itemEtatRealisationTache);


        if (request()->ajax()) {
            return view('PkgGestionTaches::etatRealisationTache._show', array_merge(compact('itemEtatRealisationTache'),));
        }

        return view('PkgGestionTaches::etatRealisationTache.show', array_merge(compact('itemEtatRealisationTache'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatRealisationTache.edit_' . $id);


        $itemEtatRealisationTache = $this->etatRealisationTacheService->edit($id);
        $this->authorize('edit', $itemEtatRealisationTache);


        $workflowTaches = $this->workflowTacheService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgGestionTaches::etatRealisationTache._fields', array_merge(compact('bulkEdit' , 'itemEtatRealisationTache','formateurs', 'sysColors', 'workflowTaches'),));
        }

        return view('PkgGestionTaches::etatRealisationTache.edit', array_merge(compact('bulkEdit' ,'itemEtatRealisationTache','formateurs', 'sysColors', 'workflowTaches'),));


    }
    /**
     */
    public function update(EtatRealisationTacheRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatRealisationTache = $this->etatRealisationTacheService->find($id);
        $this->authorize('update', $etatRealisationTache);

        $validatedData = $request->validated();
        $etatRealisationTache = $this->etatRealisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationTache,
                'modelName' =>  __('PkgGestionTaches::etatRealisationTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $etatRealisationTache->id]
            );
        }

        return redirect()->route('etatRealisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationTache,
                'modelName' =>  __('PkgGestionTaches::etatRealisationTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $etatRealisationTache_ids = $request->input('etatRealisationTache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($etatRealisationTache_ids) || count($etatRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($etatRealisationTache_ids as $id) {
            $entity = $this->etatRealisationTacheService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->etatRealisationTacheService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->etatRealisationTacheService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatRealisationTache = $this->etatRealisationTacheService->find($id);
        $this->authorize('delete', $etatRealisationTache);

        $etatRealisationTache = $this->etatRealisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationTache,
                'modelName' =>  __('PkgGestionTaches::etatRealisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('etatRealisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationTache,
                'modelName' =>  __('PkgGestionTaches::etatRealisationTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatRealisationTache_ids = $request->input('ids', []);
        if (!is_array($etatRealisationTache_ids) || count($etatRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatRealisationTache_ids as $id) {
            $entity = $this->etatRealisationTacheService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $etatRealisationTache = $this->etatRealisationTacheService->find($id);
            $this->authorize('delete', $etatRealisationTache);
            $this->etatRealisationTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatRealisationTache_ids) . ' éléments',
            'modelName' => __('PkgGestionTaches::etatRealisationTache.plural')
        ]));
    }

    public function export($format)
    {
        $etatRealisationTaches_data = $this->etatRealisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatRealisationTacheExport($etatRealisationTaches_data,'csv'), 'etatRealisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatRealisationTacheExport($etatRealisationTaches_data,'xlsx'), 'etatRealisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatRealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatRealisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatRealisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::etatRealisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatRealisationTaches()
    {
        $etatRealisationTaches = $this->etatRealisationTacheService->all();
        return response()->json($etatRealisationTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $etatRealisationTache = $this->etatRealisationTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEtatRealisationTache = $this->etatRealisationTacheService->dataCalcul($etatRealisationTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEtatRealisationTache
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
        $etatRealisationTacheRequest = new EtatRealisationTacheRequest();
        $fullRules = $etatRealisationTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_realisation_taches,id'];
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