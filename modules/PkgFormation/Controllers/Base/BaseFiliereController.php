<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\ModuleService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgSessions\Services\SessionFormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\FiliereRequest;
use Modules\PkgFormation\Models\Filiere;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgFormation\App\Exports\FiliereExport;
use Modules\PkgFormation\App\Imports\FiliereImport;
use Modules\Core\Services\ContextState;

class BaseFiliereController extends AdminController
{
    protected $filiereService;

    public function __construct(FiliereService $filiereService) {
        parent::__construct();
        $this->service  =  $filiereService;
        $this->filiereService = $filiereService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('filiere.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('filiere');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $filieres_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'filieres_search',
                $this->viewState->get("filter.filiere.filieres_search")
            )],
            $request->except(['filieres_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->filiereService->prepareDataForIndexView($filieres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgFormation::filiere._index', $filiere_compact_value)->render();
            }else{
                return view($filiere_partialViewName, $filiere_compact_value)->render();
            }
        }

        return view('PkgFormation::filiere.index', $filiere_compact_value);
    }
    /**
     */
    public function create() {


        $itemFiliere = $this->filiereService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgFormation::filiere._fields', compact('bulkEdit' ,'itemFiliere'));
        }
        return view('PkgFormation::filiere.create', compact('bulkEdit' ,'itemFiliere'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $filiere_ids = $request->input('ids', []);

        if (!is_array($filiere_ids) || count($filiere_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemFiliere = $this->filiereService->find($filiere_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemFiliere = $this->filiereService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgFormation::filiere._fields', compact('bulkEdit', 'filiere_ids', 'itemFiliere'));
        }
        return view('PkgFormation::filiere.bulk-edit', compact('bulkEdit', 'filiere_ids', 'itemFiliere'));
    }
    /**
     */
    public function store(FiliereRequest $request) {
        $validatedData = $request->validated();
        $filiere = $this->filiereService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $filiere,
                'modelName' => __('PkgFormation::filiere.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $filiere->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('filieres.edit', ['filiere' => $filiere->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $filiere,
                'modelName' => __('PkgFormation::filiere.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('filiere.show_' . $id);

        $itemFiliere = $this->filiereService->edit($id);


        $this->viewState->set('scope.groupe.filiere_id', $id);
        

        $groupeService =  new GroupeService();
        $groupes_view_data = $groupeService->prepareDataForIndexView();
        extract($groupes_view_data);

        $this->viewState->set('scope.module.filiere_id', $id);
        

        $moduleService =  new ModuleService();
        $modules_view_data = $moduleService->prepareDataForIndexView();
        extract($modules_view_data);

        $this->viewState->set('scope.projet.filiere_id', $id);
        

        $projetService =  new ProjetService();
        $projets_view_data = $projetService->prepareDataForIndexView();
        extract($projets_view_data);

        $this->viewState->set('scope.sessionFormation.filiere_id', $id);
        

        $sessionFormationService =  new SessionFormationService();
        $sessionFormations_view_data = $sessionFormationService->prepareDataForIndexView();
        extract($sessionFormations_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::filiere._show', array_merge(compact('itemFiliere'),$groupe_compact_value, $module_compact_value, $projet_compact_value, $sessionFormation_compact_value));
        }

        return view('PkgFormation::filiere.show', array_merge(compact('itemFiliere'),$groupe_compact_value, $module_compact_value, $projet_compact_value, $sessionFormation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('filiere.edit_' . $id);


        $itemFiliere = $this->filiereService->edit($id);




        $this->viewState->set('scope.groupe.filiere_id', $id);
        

        $groupeService =  new GroupeService();
        $groupes_view_data = $groupeService->prepareDataForIndexView();
        extract($groupes_view_data);

        $this->viewState->set('scope.module.filiere_id', $id);
        

        $moduleService =  new ModuleService();
        $modules_view_data = $moduleService->prepareDataForIndexView();
        extract($modules_view_data);

        $this->viewState->set('scope.projet.filiere_id', $id);
        

        $projetService =  new ProjetService();
        $projets_view_data = $projetService->prepareDataForIndexView();
        extract($projets_view_data);

        $this->viewState->set('scope.sessionFormation.filiere_id', $id);
        

        $sessionFormationService =  new SessionFormationService();
        $sessionFormations_view_data = $sessionFormationService->prepareDataForIndexView();
        extract($sessionFormations_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgFormation::filiere._edit', array_merge(compact('bulkEdit' , 'itemFiliere',),$groupe_compact_value, $module_compact_value, $projet_compact_value, $sessionFormation_compact_value));
        }

        return view('PkgFormation::filiere.edit', array_merge(compact('bulkEdit' ,'itemFiliere',),$groupe_compact_value, $module_compact_value, $projet_compact_value, $sessionFormation_compact_value));


    }
    /**
     */
    public function update(FiliereRequest $request, string $id) {

        $validatedData = $request->validated();
        $filiere = $this->filiereService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $filiere->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $filiere_ids = $request->input('filiere_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($filiere_ids) || count($filiere_ids) === 0) {
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
            Auth::id(),
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $filiere_ids,
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

        $filiere = $this->filiereService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $filiere_ids = $request->input('ids', []);
        if (!is_array($filiere_ids) || count($filiere_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($filiere_ids as $id) {
            $entity = $this->filiereService->find($id);
            $this->filiereService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($filiere_ids) . ' éléments',
            'modelName' => __('PkgFormation::filiere.plural')
        ]));
    }

    public function export($format)
    {
        $filieres_data = $this->filiereService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new FiliereExport($filieres_data,'csv'), 'filiere_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new FiliereExport($filieres_data,'xlsx'), 'filiere_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new FiliereImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('filieres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('filieres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::filiere.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFilieres()
    {
        $filieres = $this->filiereService->all();
        return response()->json($filieres);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Filiere) par ID, en format JSON.
     */
    public function getFiliere(Request $request, $id)
    {
        try {
            $filiere = $this->filiereService->find($id);
            return response()->json($filiere);
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
        $updatedFiliere = $this->filiereService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedFiliere],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
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
        $filiereRequest = new FiliereRequest();
        $fullRules = $filiereRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:filieres,id'];
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