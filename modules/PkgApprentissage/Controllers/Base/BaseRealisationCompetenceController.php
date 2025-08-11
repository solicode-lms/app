<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\RealisationCompetenceService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgApprentissage\Services\EtatRealisationCompetenceService;
use Modules\PkgApprentissage\Services\RealisationModuleService;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\RealisationCompetenceRequest;
use Modules\PkgApprentissage\Models\RealisationCompetence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\RealisationCompetenceExport;
use Modules\PkgApprentissage\App\Imports\RealisationCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseRealisationCompetenceController extends AdminController
{
    protected $realisationCompetenceService;
    protected $apprenantService;
    protected $competenceService;
    protected $etatRealisationCompetenceService;
    protected $realisationModuleService;

    public function __construct(RealisationCompetenceService $realisationCompetenceService, ApprenantService $apprenantService, CompetenceService $competenceService, EtatRealisationCompetenceService $etatRealisationCompetenceService, RealisationModuleService $realisationModuleService) {
        parent::__construct();
        $this->service  =  $realisationCompetenceService;
        $this->realisationCompetenceService = $realisationCompetenceService;
        $this->apprenantService = $apprenantService;
        $this->competenceService = $competenceService;
        $this->etatRealisationCompetenceService = $etatRealisationCompetenceService;
        $this->realisationModuleService = $realisationModuleService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationCompetence.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationCompetence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.realisationCompetence.apprenant.groupes.formateurs.user_id') == null){
           $this->viewState->init('scope.realisationCompetence.apprenant.groupes.formateurs.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('scope.realisationCompetence.apprenant_id') == null){
           $this->viewState->init('scope.realisationCompetence.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $realisationCompetences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationCompetences_search',
                $this->viewState->get("filter.realisationCompetence.realisationCompetences_search")
            )],
            $request->except(['realisationCompetences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationCompetenceService->prepareDataForIndexView($realisationCompetences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::realisationCompetence._index', $realisationCompetence_compact_value)->render();
            }else{
                return view($realisationCompetence_partialViewName, $realisationCompetence_compact_value)->render();
            }
        }

        return view('PkgApprentissage::realisationCompetence.index', $realisationCompetence_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationCompetence.apprenant.groupes.formateurs.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationCompetence.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemRealisationCompetence = $this->realisationCompetenceService->createInstance();
        

        $competences = $this->competenceService->all();
        $realisationModules = $this->realisationModuleService->all();
        $apprenants = $this->apprenantService->all();
        $etatRealisationCompetences = $this->etatRealisationCompetenceService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationCompetence._fields', compact('bulkEdit' ,'itemRealisationCompetence', 'apprenants', 'competences', 'etatRealisationCompetences', 'realisationModules'));
        }
        return view('PkgApprentissage::realisationCompetence.create', compact('bulkEdit' ,'itemRealisationCompetence', 'apprenants', 'competences', 'etatRealisationCompetences', 'realisationModules'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationCompetence_ids = $request->input('ids', []);

        if (!is_array($realisationCompetence_ids) || count($realisationCompetence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationCompetence.apprenant.groupes.formateurs.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationCompetence.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
 
         $itemRealisationCompetence = $this->realisationCompetenceService->find($realisationCompetence_ids[0]);
         
 
        $competences = $this->competenceService->all();
        $realisationModules = $this->realisationModuleService->all();
        $apprenants = $this->apprenantService->all();
        $etatRealisationCompetences = $this->etatRealisationCompetenceService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationCompetence = $this->realisationCompetenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationCompetence._fields', compact('bulkEdit', 'realisationCompetence_ids', 'itemRealisationCompetence', 'apprenants', 'competences', 'etatRealisationCompetences', 'realisationModules'));
        }
        return view('PkgApprentissage::realisationCompetence.bulk-edit', compact('bulkEdit', 'realisationCompetence_ids', 'itemRealisationCompetence', 'apprenants', 'competences', 'etatRealisationCompetences', 'realisationModules'));
    }
    /**
     */
    public function store(RealisationCompetenceRequest $request) {
        $validatedData = $request->validated();
        $realisationCompetence = $this->realisationCompetenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationCompetence,
                'modelName' => __('PkgApprentissage::realisationCompetence.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationCompetence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('realisationCompetences.edit', ['realisationCompetence' => $realisationCompetence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationCompetence,
                'modelName' => __('PkgApprentissage::realisationCompetence.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationCompetence.show_' . $id);

        $itemRealisationCompetence = $this->realisationCompetenceService->edit($id);
        $this->authorize('view', $itemRealisationCompetence);


        $this->viewState->set('scope.realisationMicroCompetence.realisation_competence_id', $id);
        

        $realisationMicroCompetenceService =  new RealisationMicroCompetenceService();
        $realisationMicroCompetences_view_data = $realisationMicroCompetenceService->prepareDataForIndexView();
        extract($realisationMicroCompetences_view_data);

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationCompetence._show', array_merge(compact('itemRealisationCompetence'),$realisationMicroCompetence_compact_value));
        }

        return view('PkgApprentissage::realisationCompetence.show', array_merge(compact('itemRealisationCompetence'),$realisationMicroCompetence_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationCompetence.edit_' . $id);


        $itemRealisationCompetence = $this->realisationCompetenceService->edit($id);
        $this->authorize('edit', $itemRealisationCompetence);


        $competences = $this->competenceService->all();
        $realisationModules = $this->realisationModuleService->all();
        $apprenants = $this->apprenantService->all();
        $etatRealisationCompetences = $this->etatRealisationCompetenceService->all();


        $this->viewState->set('scope.realisationMicroCompetence.realisation_competence_id', $id);
        

        $realisationMicroCompetenceService =  new RealisationMicroCompetenceService();
        $realisationMicroCompetences_view_data = $realisationMicroCompetenceService->prepareDataForIndexView();
        extract($realisationMicroCompetences_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationCompetence._edit', array_merge(compact('bulkEdit' , 'itemRealisationCompetence','apprenants', 'competences', 'etatRealisationCompetences', 'realisationModules'),$realisationMicroCompetence_compact_value));
        }

        return view('PkgApprentissage::realisationCompetence.edit', array_merge(compact('bulkEdit' ,'itemRealisationCompetence','apprenants', 'competences', 'etatRealisationCompetences', 'realisationModules'),$realisationMicroCompetence_compact_value));


    }
    /**
     */
    public function update(RealisationCompetenceRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationCompetence = $this->realisationCompetenceService->find($id);
        $this->authorize('update', $realisationCompetence);

        $validatedData = $request->validated();
        $realisationCompetence = $this->realisationCompetenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationCompetence,
                'modelName' =>  __('PkgApprentissage::realisationCompetence.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationCompetence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('realisationCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationCompetence,
                'modelName' =>  __('PkgApprentissage::realisationCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $realisationCompetence_ids = $request->input('realisationCompetence_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($realisationCompetence_ids) || count($realisationCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }

        // 🔹 Récupérer les valeurs de ces champs
        $valeursChamps = [];
        foreach ($champsCoches as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob",$this->service->modelName,$this->service->moduleName);
         
        dispatch(new BulkEditJob(
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $realisationCompetence_ids,
            $champsCoches,
            $valeursChamps
        ));

       
        return JsonResponseHelper::success(
             __('Mise à jour en masse effectuée avec succès.'),
                ['traitement_token' => $jobManager->getToken()]
        );

    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationCompetence = $this->realisationCompetenceService->find($id);
        $this->authorize('delete', $realisationCompetence);

        $realisationCompetence = $this->realisationCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationCompetence,
                'modelName' =>  __('PkgApprentissage::realisationCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationCompetence,
                'modelName' =>  __('PkgApprentissage::realisationCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationCompetence_ids = $request->input('ids', []);
        if (!is_array($realisationCompetence_ids) || count($realisationCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationCompetence_ids as $id) {
            $entity = $this->realisationCompetenceService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $realisationCompetence = $this->realisationCompetenceService->find($id);
            $this->authorize('delete', $realisationCompetence);
            $this->realisationCompetenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationCompetence_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::realisationCompetence.plural')
        ]));
    }

    public function export($format)
    {
        $realisationCompetences_data = $this->realisationCompetenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationCompetenceExport($realisationCompetences_data,'csv'), 'realisationCompetence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationCompetenceExport($realisationCompetences_data,'xlsx'), 'realisationCompetence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::realisationCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationCompetences()
    {
        $realisationCompetences = $this->realisationCompetenceService->all();
        return response()->json($realisationCompetences);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (RealisationCompetence) par ID, en format JSON.
     */
    public function getRealisationCompetence(Request $request, $id)
    {
        try {
            $realisationCompetence = $this->realisationCompetenceService->find($id);
            return response()->json($realisationCompetence);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Entité non trouvée ou erreur.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    public function dataCalcul(Request $request)
    {
        $data = $request->all();

        // Traitement métier personnalisé (ne modifie pas la base)
        $updatedRealisationCompetence = $this->realisationCompetenceService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationCompetence
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
        $realisationCompetenceRequest = new RealisationCompetenceRequest();
        $fullRules = $realisationCompetenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_competences,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(
             __('Mise à jour réussie.'),
                array_merge(
                    ['entity_id' => $validated['id']],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
        );
    }
}