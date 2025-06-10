<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\EtatFormationService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\Core\Services\SysColorService;
use Modules\PkgAutoformation\Services\WorkflowFormationService;
use Modules\PkgAutoformation\Services\RealisationFormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\EtatFormationRequest;
use Modules\PkgAutoformation\Models\EtatFormation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\EtatFormationExport;
use Modules\PkgAutoformation\App\Imports\EtatFormationImport;
use Modules\Core\Services\ContextState;

class BaseEtatFormationController extends AdminController
{
    protected $etatFormationService;
    protected $formateurService;
    protected $sysColorService;
    protected $workflowFormationService;

    public function __construct(EtatFormationService $etatFormationService, FormateurService $formateurService, SysColorService $sysColorService, WorkflowFormationService $workflowFormationService) {
        parent::__construct();
        $this->service  =  $etatFormationService;
        $this->etatFormationService = $etatFormationService;
        $this->formateurService = $formateurService;
        $this->sysColorService = $sysColorService;
        $this->workflowFormationService = $workflowFormationService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('etatFormation.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('etatFormation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.etatFormation.formateur_id') == null){
           $this->viewState->init('scope.etatFormation.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $etatFormations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatFormations_search',
                $this->viewState->get("filter.etatFormation.etatFormations_search")
            )],
            $request->except(['etatFormations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatFormationService->prepareDataForIndexView($etatFormations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutoformation::etatFormation._index', $etatFormation_compact_value)->render();
            }else{
                return view($etatFormation_partialViewName, $etatFormation_compact_value)->render();
            }
        }

        return view('PkgAutoformation::etatFormation.index', $etatFormation_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.etatFormation.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemEtatFormation = $this->etatFormationService->createInstance();
        

        $workflowFormations = $this->workflowFormationService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgAutoformation::etatFormation._fields', compact('itemEtatFormation', 'formateurs', 'sysColors', 'workflowFormations'));
        }
        return view('PkgAutoformation::etatFormation.create', compact('itemEtatFormation', 'formateurs', 'sysColors', 'workflowFormations'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatFormation_ids = $request->input('ids', []);

        if (!is_array($etatFormation_ids) || count($etatFormation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.etatFormation.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
         $itemEtatFormation = $this->etatFormationService->find($etatFormation_ids[0]);
         
 
        $workflowFormations = $this->workflowFormationService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatFormation = $this->etatFormationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgAutoformation::etatFormation._fields', compact('bulkEdit', 'etatFormation_ids', 'itemEtatFormation', 'formateurs', 'sysColors', 'workflowFormations'));
        }
        return view('PkgAutoformation::etatFormation.bulk-edit', compact('bulkEdit', 'etatFormation_ids', 'itemEtatFormation', 'formateurs', 'sysColors', 'workflowFormations'));
    }
    /**
     */
    public function store(EtatFormationRequest $request) {
        $validatedData = $request->validated();
        $etatFormation = $this->etatFormationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatFormation,
                'modelName' => __('PkgAutoformation::etatFormation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $etatFormation->id]
            );
        }

        return redirect()->route('etatFormations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatFormation,
                'modelName' => __('PkgAutoformation::etatFormation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatFormation.show_' . $id);

        $itemEtatFormation = $this->etatFormationService->edit($id);
        $this->authorize('view', $itemEtatFormation);


        $this->viewState->set('scope.realisationFormation.etat_formation_id', $id);
        

        $realisationFormationService =  new RealisationFormationService();
        $realisationFormations_view_data = $realisationFormationService->prepareDataForIndexView();
        extract($realisationFormations_view_data);

        if (request()->ajax()) {
            return view('PkgAutoformation::etatFormation._show', array_merge(compact('itemEtatFormation'),$realisationFormation_compact_value));
        }

        return view('PkgAutoformation::etatFormation.show', array_merge(compact('itemEtatFormation'),$realisationFormation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatFormation.edit_' . $id);


        $itemEtatFormation = $this->etatFormationService->edit($id);
        $this->authorize('edit', $itemEtatFormation);


        $workflowFormations = $this->workflowFormationService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgAutoformation::etatFormation._fields', array_merge(compact('bulkEdit' , 'itemEtatFormation','formateurs', 'sysColors', 'workflowFormations'),));
        }

        return view('PkgAutoformation::etatFormation.edit', array_merge(compact('bulkEdit' ,'itemEtatFormation','formateurs', 'sysColors', 'workflowFormations'),));


    }
    /**
     */
    public function update(EtatFormationRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatFormation = $this->etatFormationService->find($id);
        $this->authorize('update', $etatFormation);

        $validatedData = $request->validated();
        $etatFormation = $this->etatFormationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatFormation,
                'modelName' =>  __('PkgAutoformation::etatFormation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $etatFormation->id]
            );
        }

        return redirect()->route('etatFormations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatFormation,
                'modelName' =>  __('PkgAutoformation::etatFormation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $etatFormation_ids = $request->input('etatFormation_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($etatFormation_ids) || count($etatFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($etatFormation_ids as $id) {
            $entity = $this->etatFormationService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->etatFormationService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->etatFormationService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatFormation = $this->etatFormationService->find($id);
        $this->authorize('delete', $etatFormation);

        $etatFormation = $this->etatFormationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatFormation,
                'modelName' =>  __('PkgAutoformation::etatFormation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('etatFormations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatFormation,
                'modelName' =>  __('PkgAutoformation::etatFormation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatFormation_ids = $request->input('ids', []);
        if (!is_array($etatFormation_ids) || count($etatFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatFormation_ids as $id) {
            $entity = $this->etatFormationService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $etatFormation = $this->etatFormationService->find($id);
            $this->authorize('delete', $etatFormation);
            $this->etatFormationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatFormation_ids) . ' éléments',
            'modelName' => __('PkgAutoformation::etatFormation.plural')
        ]));
    }

    public function export($format)
    {
        $etatFormations_data = $this->etatFormationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatFormationExport($etatFormations_data,'csv'), 'etatFormation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatFormationExport($etatFormations_data,'xlsx'), 'etatFormation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatFormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatFormations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatFormations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::etatFormation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatFormations()
    {
        $etatFormations = $this->etatFormationService->all();
        return response()->json($etatFormations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $etatFormation = $this->etatFormationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEtatFormation = $this->etatFormationService->dataCalcul($etatFormation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEtatFormation
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
        $etatFormationRequest = new EtatFormationRequest();
        $fullRules = $etatFormationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_formations,id'];
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