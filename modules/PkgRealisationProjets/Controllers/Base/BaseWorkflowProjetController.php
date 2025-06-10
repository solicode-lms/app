<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\WorkflowProjetService;
use Modules\Core\Services\SysColorService;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\WorkflowProjetRequest;
use Modules\PkgRealisationProjets\Models\WorkflowProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\WorkflowProjetExport;
use Modules\PkgRealisationProjets\App\Imports\WorkflowProjetImport;
use Modules\Core\Services\ContextState;

class BaseWorkflowProjetController extends AdminController
{
    protected $workflowProjetService;
    protected $sysColorService;

    public function __construct(WorkflowProjetService $workflowProjetService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $workflowProjetService;
        $this->workflowProjetService = $workflowProjetService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('workflowProjet.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('workflowProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $workflowProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'workflowProjets_search',
                $this->viewState->get("filter.workflowProjet.workflowProjets_search")
            )],
            $request->except(['workflowProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->workflowProjetService->prepareDataForIndexView($workflowProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationProjets::workflowProjet._index', $workflowProjet_compact_value)->render();
            }else{
                return view($workflowProjet_partialViewName, $workflowProjet_compact_value)->render();
            }
        }

        return view('PkgRealisationProjets::workflowProjet.index', $workflowProjet_compact_value);
    }
    /**
     */
    public function create() {


        $itemWorkflowProjet = $this->workflowProjetService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::workflowProjet._fields', compact('itemWorkflowProjet', 'sysColors'));
        }
        return view('PkgRealisationProjets::workflowProjet.create', compact('itemWorkflowProjet', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $workflowProjet_ids = $request->input('ids', []);

        if (!is_array($workflowProjet_ids) || count($workflowProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemWorkflowProjet = $this->workflowProjetService->find($workflowProjet_ids[0]);
         
 
        $sysColors = $this->sysColorService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemWorkflowProjet = $this->workflowProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationProjets::workflowProjet._fields', compact('bulkEdit', 'workflowProjet_ids', 'itemWorkflowProjet', 'sysColors'));
        }
        return view('PkgRealisationProjets::workflowProjet.bulk-edit', compact('bulkEdit', 'workflowProjet_ids', 'itemWorkflowProjet', 'sysColors'));
    }
    /**
     */
    public function store(WorkflowProjetRequest $request) {
        $validatedData = $request->validated();
        $workflowProjet = $this->workflowProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $workflowProjet,
                'modelName' => __('PkgRealisationProjets::workflowProjet.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $workflowProjet->id]
            );
        }

        return redirect()->route('workflowProjets.edit',['workflowProjet' => $workflowProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $workflowProjet,
                'modelName' => __('PkgRealisationProjets::workflowProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('workflowProjet.show_' . $id);

        $itemWorkflowProjet = $this->workflowProjetService->edit($id);


        $this->viewState->set('scope.etatsRealisationProjet.workflow_projet_id', $id);
        

        $etatsRealisationProjetService =  new EtatsRealisationProjetService();
        $etatsRealisationProjets_view_data = $etatsRealisationProjetService->prepareDataForIndexView();
        extract($etatsRealisationProjets_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationProjets::workflowProjet._show', array_merge(compact('itemWorkflowProjet'),$etatsRealisationProjet_compact_value));
        }

        return view('PkgRealisationProjets::workflowProjet.show', array_merge(compact('itemWorkflowProjet'),$etatsRealisationProjet_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('workflowProjet.edit_' . $id);


        $itemWorkflowProjet = $this->workflowProjetService->edit($id);


        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.etatsRealisationProjet.workflow_projet_id', $id);
        

        $etatsRealisationProjetService =  new EtatsRealisationProjetService();
        $etatsRealisationProjets_view_data = $etatsRealisationProjetService->prepareDataForIndexView();
        extract($etatsRealisationProjets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgRealisationProjets::workflowProjet._edit', array_merge(compact('bulkEdit' , 'itemWorkflowProjet','sysColors'),$etatsRealisationProjet_compact_value));
        }

        return view('PkgRealisationProjets::workflowProjet.edit', array_merge(compact('itemWorkflowProjet','sysColors'),$etatsRealisationProjet_compact_value));


    }
    /**
     */
    public function update(WorkflowProjetRequest $request, string $id) {

        $validatedData = $request->validated();
        $workflowProjet = $this->workflowProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $workflowProjet,
                'modelName' =>  __('PkgRealisationProjets::workflowProjet.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $workflowProjet->id]
            );
        }

        return redirect()->route('workflowProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $workflowProjet,
                'modelName' =>  __('PkgRealisationProjets::workflowProjet.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $workflowProjet_ids = $request->input('workflowProjet_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($workflowProjet_ids) || count($workflowProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($workflowProjet_ids as $id) {
            $entity = $this->workflowProjetService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->workflowProjetService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->workflowProjetService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $workflowProjet = $this->workflowProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowProjet,
                'modelName' =>  __('PkgRealisationProjets::workflowProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('workflowProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowProjet,
                'modelName' =>  __('PkgRealisationProjets::workflowProjet.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $workflowProjet_ids = $request->input('ids', []);
        if (!is_array($workflowProjet_ids) || count($workflowProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($workflowProjet_ids as $id) {
            $entity = $this->workflowProjetService->find($id);
            $this->workflowProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($workflowProjet_ids) . ' éléments',
            'modelName' => __('PkgRealisationProjets::workflowProjet.plural')
        ]));
    }

    public function export($format)
    {
        $workflowProjets_data = $this->workflowProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WorkflowProjetExport($workflowProjets_data,'csv'), 'workflowProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WorkflowProjetExport($workflowProjets_data,'xlsx'), 'workflowProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WorkflowProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('workflowProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('workflowProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::workflowProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWorkflowProjets()
    {
        $workflowProjets = $this->workflowProjetService->all();
        return response()->json($workflowProjets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $workflowProjet = $this->workflowProjetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWorkflowProjet = $this->workflowProjetService->dataCalcul($workflowProjet);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWorkflowProjet
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
        $workflowProjetRequest = new WorkflowProjetRequest();
        $fullRules = $workflowProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:workflow_projets,id'];
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