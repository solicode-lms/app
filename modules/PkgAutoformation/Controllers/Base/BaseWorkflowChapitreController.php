<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\WorkflowChapitreService;
use Modules\Core\Services\SysColorService;
use Modules\PkgAutoformation\Services\EtatChapitreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\WorkflowChapitreRequest;
use Modules\PkgAutoformation\Models\WorkflowChapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\WorkflowChapitreExport;
use Modules\PkgAutoformation\App\Imports\WorkflowChapitreImport;
use Modules\Core\Services\ContextState;

class BaseWorkflowChapitreController extends AdminController
{
    protected $workflowChapitreService;
    protected $sysColorService;

    public function __construct(WorkflowChapitreService $workflowChapitreService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $workflowChapitreService;
        $this->workflowChapitreService = $workflowChapitreService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('workflowChapitre.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('workflowChapitre');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $workflowChapitres_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'workflowChapitres_search',
                $this->viewState->get("filter.workflowChapitre.workflowChapitres_search")
            )],
            $request->except(['workflowChapitres_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->workflowChapitreService->prepareDataForIndexView($workflowChapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutoformation::workflowChapitre._index', $workflowChapitre_compact_value)->render();
            }else{
                return view($workflowChapitre_partialViewName, $workflowChapitre_compact_value)->render();
            }
        }

        return view('PkgAutoformation::workflowChapitre.index', $workflowChapitre_compact_value);
    }
    /**
     */
    public function create() {


        $itemWorkflowChapitre = $this->workflowChapitreService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowChapitre._fields', compact('itemWorkflowChapitre', 'sysColors'));
        }
        return view('PkgAutoformation::workflowChapitre.create', compact('itemWorkflowChapitre', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $workflowChapitre_ids = $request->input('ids', []);

        if (!is_array($workflowChapitre_ids) || count($workflowChapitre_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemWorkflowChapitre = $this->workflowChapitreService->find($workflowChapitre_ids[0]);
         
 
        $sysColors = $this->sysColorService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemWorkflowChapitre = $this->workflowChapitreService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgAutoformation::workflowChapitre._fields', compact('bulkEdit', 'workflowChapitre_ids', 'itemWorkflowChapitre', 'sysColors'));
        }
        return view('PkgAutoformation::workflowChapitre.bulk-edit', compact('bulkEdit', 'workflowChapitre_ids', 'itemWorkflowChapitre', 'sysColors'));
    }
    /**
     */
    public function store(WorkflowChapitreRequest $request) {
        $validatedData = $request->validated();
        $workflowChapitre = $this->workflowChapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' => __('PkgAutoformation::workflowChapitre.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $workflowChapitre->id]
            );
        }

        return redirect()->route('workflowChapitres.edit',['workflowChapitre' => $workflowChapitre->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' => __('PkgAutoformation::workflowChapitre.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('workflowChapitre.edit_' . $id);


        $itemWorkflowChapitre = $this->workflowChapitreService->edit($id);


        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.etatChapitre.workflow_chapitre_id', $id);
        

        $etatChapitreService =  new EtatChapitreService();
        $etatChapitres_view_data = $etatChapitreService->prepareDataForIndexView();
        extract($etatChapitres_view_data);

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowChapitre._edit', array_merge(compact('itemWorkflowChapitre','sysColors'),$etatChapitre_compact_value));
        }

        return view('PkgAutoformation::workflowChapitre.edit', array_merge(compact('itemWorkflowChapitre','sysColors'),$etatChapitre_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('workflowChapitre.edit_' . $id);


        $itemWorkflowChapitre = $this->workflowChapitreService->edit($id);


        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.etatChapitre.workflow_chapitre_id', $id);
        

        $etatChapitreService =  new EtatChapitreService();
        $etatChapitres_view_data = $etatChapitreService->prepareDataForIndexView();
        extract($etatChapitres_view_data);

        if (request()->ajax()) {
            return view('PkgAutoformation::workflowChapitre._edit', array_merge(compact('itemWorkflowChapitre','sysColors'),$etatChapitre_compact_value));
        }

        return view('PkgAutoformation::workflowChapitre.edit', array_merge(compact('itemWorkflowChapitre','sysColors'),$etatChapitre_compact_value));


    }
    /**
     */
    public function update(WorkflowChapitreRequest $request, string $id) {

        $validatedData = $request->validated();
        $workflowChapitre = $this->workflowChapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' =>  __('PkgAutoformation::workflowChapitre.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $workflowChapitre->id]
            );
        }

        return redirect()->route('workflowChapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' =>  __('PkgAutoformation::workflowChapitre.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $workflowChapitre_ids = $request->input('workflowChapitre_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($workflowChapitre_ids) || count($workflowChapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($workflowChapitre_ids as $id) {
            $entity = $this->workflowChapitreService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->workflowChapitreService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->workflowChapitreService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $workflowChapitre = $this->workflowChapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' =>  __('PkgAutoformation::workflowChapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('workflowChapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $workflowChapitre,
                'modelName' =>  __('PkgAutoformation::workflowChapitre.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $workflowChapitre_ids = $request->input('ids', []);
        if (!is_array($workflowChapitre_ids) || count($workflowChapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($workflowChapitre_ids as $id) {
            $entity = $this->workflowChapitreService->find($id);
            $this->workflowChapitreService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($workflowChapitre_ids) . ' éléments',
            'modelName' => __('PkgAutoformation::workflowChapitre.plural')
        ]));
    }

    public function export($format)
    {
        $workflowChapitres_data = $this->workflowChapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WorkflowChapitreExport($workflowChapitres_data,'csv'), 'workflowChapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WorkflowChapitreExport($workflowChapitres_data,'xlsx'), 'workflowChapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WorkflowChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('workflowChapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('workflowChapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::workflowChapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWorkflowChapitres()
    {
        $workflowChapitres = $this->workflowChapitreService->all();
        return response()->json($workflowChapitres);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $workflowChapitre = $this->workflowChapitreService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWorkflowChapitre = $this->workflowChapitreService->dataCalcul($workflowChapitre);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWorkflowChapitre
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
        $workflowChapitreRequest = new WorkflowChapitreRequest();
        $fullRules = $workflowChapitreRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:workflow_chapitres,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.', 422);
        }
    
        $this->getService()->update($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}