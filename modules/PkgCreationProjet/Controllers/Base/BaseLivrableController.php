<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgRealisationProjets\Services\LivrablesRealisationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\LivrableRequest;
use Modules\PkgCreationProjet\Models\Livrable;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\App\Exports\LivrableExport;
use Modules\PkgCreationProjet\App\Imports\LivrableImport;
use Modules\Core\Services\ContextState;

class BaseLivrableController extends AdminController
{
    protected $livrableService;
    protected $tacheService;
    protected $natureLivrableService;
    protected $projetService;

    public function __construct(LivrableService $livrableService, TacheService $tacheService, NatureLivrableService $natureLivrableService, ProjetService $projetService) {
        parent::__construct();
        $this->service  =  $livrableService;
        $this->livrableService = $livrableService;
        $this->tacheService = $tacheService;
        $this->natureLivrableService = $natureLivrableService;
        $this->projetService = $projetService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('livrable.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('livrable');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.livrable.projet.formateur_id') == null){
           $this->viewState->init('filter.livrable.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $livrables_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'livrables_search',
                $this->viewState->get("filter.livrable.livrables_search")
            )],
            $request->except(['livrables_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->livrableService->prepareDataForIndexView($livrables_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationProjet::livrable._index', $livrable_compact_value)->render();
            }else{
                return view($livrable_partialViewName, $livrable_compact_value)->render();
            }
        }

        return view('PkgCreationProjet::livrable.index', $livrable_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.livrable.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemLivrable = $this->livrableService->createInstance();
        
        // scopeDataInEditContext
        $value = $itemLivrable->getNestedValue('projet_id');
        $key = 'scope.tache.projet_id';
        $this->viewState->set($key, $value);

        $natureLivrables = $this->natureLivrableService->all();
        $projets = $this->projetService->all();
        $taches = $this->tacheService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._fields', compact('bulkEdit' ,'itemLivrable', 'taches', 'natureLivrables', 'projets'));
        }
        return view('PkgCreationProjet::livrable.create', compact('bulkEdit' ,'itemLivrable', 'taches', 'natureLivrables', 'projets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $livrable_ids = $request->input('ids', []);

        if (!is_array($livrable_ids) || count($livrable_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.livrable.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
         $itemLivrable = $this->livrableService->find($livrable_ids[0]);
         
        // scopeDataInEditContext
        $value = $itemLivrable->getNestedValue('projet_id');
        $key = 'scope.tache.projet_id';
        $this->viewState->set($key, $value);
 
        $natureLivrables = $this->natureLivrableService->getAllForSelect($itemLivrable->natureLivrable);
        $projets = $this->projetService->getAllForSelect($itemLivrable->projet);
        $taches = $this->tacheService->getAllForSelect($itemLivrable->);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemLivrable = $this->livrableService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._fields', compact('bulkEdit', 'livrable_ids', 'itemLivrable', 'taches', 'natureLivrables', 'projets'));
        }
        return view('PkgCreationProjet::livrable.bulk-edit', compact('bulkEdit', 'livrable_ids', 'itemLivrable', 'taches', 'natureLivrables', 'projets'));
    }
    /**
     */
    public function store(LivrableRequest $request) {
        $validatedData = $request->validated();
        $livrable = $this->livrableService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $livrable,
                'modelName' => __('PkgCreationProjet::livrable.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $livrable->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $livrable,
                'modelName' => __('PkgCreationProjet::livrable.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('livrable.show_' . $id);

        $itemLivrable = $this->livrableService->edit($id);
        $this->authorize('view', $itemLivrable);


        $this->viewState->set('scope.livrablesRealisation.livrable_id', $id);
        

        $livrablesRealisationService =  new LivrablesRealisationService();
        $livrablesRealisations_view_data = $livrablesRealisationService->prepareDataForIndexView();
        extract($livrablesRealisations_view_data);

        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._show', array_merge(compact('itemLivrable'),$livrablesRealisation_compact_value));
        }

        return view('PkgCreationProjet::livrable.show', array_merge(compact('itemLivrable'),$livrablesRealisation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('livrable.edit_' . $id);


        $itemLivrable = $this->livrableService->edit($id);
        $this->authorize('edit', $itemLivrable);

        // scopeDataInEditContext
        $value = $itemLivrable->getNestedValue('projet_id');
        $key = 'scope.tache.projet_id';
        $this->viewState->set($key, $value);

        $natureLivrables = $this->natureLivrableService->getAllForSelect($itemLivrable->natureLivrable);
        $projets = $this->projetService->getAllForSelect($itemLivrable->projet);
        $taches = $this->tacheService->getAllForSelect($itemLivrable->);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._fields', array_merge(compact('bulkEdit' , 'itemLivrable','taches', 'natureLivrables', 'projets'),));
        }

        return view('PkgCreationProjet::livrable.edit', array_merge(compact('bulkEdit' ,'itemLivrable','taches', 'natureLivrables', 'projets'),));


    }
    /**
     */
    public function update(LivrableRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $livrable = $this->livrableService->find($id);
        $this->authorize('update', $livrable);

        $validatedData = $request->validated();
        $livrable = $this->livrableService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $livrable->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $livrable_ids = $request->input('livrable_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($livrable_ids) || count($livrable_ids) === 0) {
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
            $livrable_ids,
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
        $livrable = $this->livrableService->find($id);
        $this->authorize('delete', $livrable);

        $livrable = $this->livrableService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $livrable_ids = $request->input('ids', []);
        if (!is_array($livrable_ids) || count($livrable_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($livrable_ids as $id) {
            $entity = $this->livrableService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $livrable = $this->livrableService->find($id);
            $this->authorize('delete', $livrable);
            $this->livrableService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($livrable_ids) . ' éléments',
            'modelName' => __('PkgCreationProjet::livrable.plural')
        ]));
    }

    public function export($format)
    {
        $livrables_data = $this->livrableService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new LivrableExport($livrables_data,'csv'), 'livrable_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new LivrableExport($livrables_data,'xlsx'), 'livrable_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new LivrableImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('livrables.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('livrables.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::livrable.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLivrables()
    {
        $livrables = $this->livrableService->all();
        return response()->json($livrables);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Livrable) par ID, en format JSON.
     */
    public function getLivrable(Request $request, $id)
    {
        try {
            $livrable = $this->livrableService->find($id);
            return response()->json($livrable);
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
        $updatedLivrable = $this->livrableService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedLivrable],
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
        $livrableRequest = new LivrableRequest();
        $fullRules = $livrableRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:livrables,id'];
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