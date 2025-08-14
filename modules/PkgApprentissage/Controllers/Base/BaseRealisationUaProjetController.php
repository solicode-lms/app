<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\RealisationUaProjetService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\RealisationUaProjetRequest;
use Modules\PkgApprentissage\Models\RealisationUaProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\RealisationUaProjetExport;
use Modules\PkgApprentissage\App\Imports\RealisationUaProjetImport;
use Modules\Core\Services\ContextState;

class BaseRealisationUaProjetController extends AdminController
{
    protected $realisationUaProjetService;
    protected $realisationTacheService;
    protected $realisationUaService;

    public function __construct(RealisationUaProjetService $realisationUaProjetService, RealisationTacheService $realisationTacheService, RealisationUaService $realisationUaService) {
        parent::__construct();
        $this->service  =  $realisationUaProjetService;
        $this->realisationUaProjetService = $realisationUaProjetService;
        $this->realisationTacheService = $realisationTacheService;
        $this->realisationUaService = $realisationUaService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationUaProjet.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationUaProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $realisationUaProjets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationUaProjets_search',
                $this->viewState->get("filter.realisationUaProjet.realisationUaProjets_search")
            )],
            $request->except(['realisationUaProjets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationUaProjetService->prepareDataForIndexView($realisationUaProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::realisationUaProjet._index', $realisationUaProjet_compact_value)->render();
            }else{
                return view($realisationUaProjet_partialViewName, $realisationUaProjet_compact_value)->render();
            }
        }

        return view('PkgApprentissage::realisationUaProjet.index', $realisationUaProjet_compact_value);
    }
    /**
     */
    public function create() {


        $itemRealisationUaProjet = $this->realisationUaProjetService->createInstance();
        

        $realisationTaches = $this->realisationTacheService->all();
        $realisationUas = $this->realisationUaService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaProjet._fields', compact('bulkEdit' ,'itemRealisationUaProjet', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationUaProjet.create', compact('bulkEdit' ,'itemRealisationUaProjet', 'realisationTaches', 'realisationUas'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationUaProjet_ids = $request->input('ids', []);

        if (!is_array($realisationUaProjet_ids) || count($realisationUaProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRealisationUaProjet = $this->realisationUaProjetService->find($realisationUaProjet_ids[0]);
         
 
        $realisationTaches = $this->realisationTacheService->all();
        $realisationUas = $this->realisationUaService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationUaProjet = $this->realisationUaProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaProjet._fields', compact('bulkEdit', 'realisationUaProjet_ids', 'itemRealisationUaProjet', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationUaProjet.bulk-edit', compact('bulkEdit', 'realisationUaProjet_ids', 'itemRealisationUaProjet', 'realisationTaches', 'realisationUas'));
    }
    /**
     */
    public function store(RealisationUaProjetRequest $request) {
        $validatedData = $request->validated();
        $realisationUaProjet = $this->realisationUaProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationUaProjet,
                'modelName' => __('PkgApprentissage::realisationUaProjet.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationUaProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('realisationUaProjets.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationUaProjet,
                'modelName' => __('PkgApprentissage::realisationUaProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationUaProjet.show_' . $id);

        $itemRealisationUaProjet = $this->realisationUaProjetService->edit($id);


        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaProjet._show', array_merge(compact('itemRealisationUaProjet'),));
        }

        return view('PkgApprentissage::realisationUaProjet.show', array_merge(compact('itemRealisationUaProjet'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationUaProjet.edit_' . $id);


        $itemRealisationUaProjet = $this->realisationUaProjetService->edit($id);


        $realisationTaches = $this->realisationTacheService->all();
        $realisationUas = $this->realisationUaService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUaProjet._fields', array_merge(compact('bulkEdit' , 'itemRealisationUaProjet','realisationTaches', 'realisationUas'),));
        }

        return view('PkgApprentissage::realisationUaProjet.edit', array_merge(compact('bulkEdit' ,'itemRealisationUaProjet','realisationTaches', 'realisationUas'),));


    }
    /**
     */
    public function update(RealisationUaProjetRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationUaProjet = $this->realisationUaProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationUaProjet,
                'modelName' =>  __('PkgApprentissage::realisationUaProjet.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationUaProjet->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('realisationUaProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationUaProjet,
                'modelName' =>  __('PkgApprentissage::realisationUaProjet.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $realisationUaProjet_ids = $request->input('realisationUaProjet_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($realisationUaProjet_ids) || count($realisationUaProjet_ids) === 0) {
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
            $realisationUaProjet_ids,
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

        $realisationUaProjet = $this->realisationUaProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationUaProjet,
                'modelName' =>  __('PkgApprentissage::realisationUaProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('realisationUaProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationUaProjet,
                'modelName' =>  __('PkgApprentissage::realisationUaProjet.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationUaProjet_ids = $request->input('ids', []);
        if (!is_array($realisationUaProjet_ids) || count($realisationUaProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationUaProjet_ids as $id) {
            $entity = $this->realisationUaProjetService->find($id);
            $this->realisationUaProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationUaProjet_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::realisationUaProjet.plural')
        ]));
    }

    public function export($format)
    {
        $realisationUaProjets_data = $this->realisationUaProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationUaProjetExport($realisationUaProjets_data,'csv'), 'realisationUaProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationUaProjetExport($realisationUaProjets_data,'xlsx'), 'realisationUaProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationUaProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationUaProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationUaProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::realisationUaProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationUaProjets()
    {
        $realisationUaProjets = $this->realisationUaProjetService->all();
        return response()->json($realisationUaProjets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (RealisationUaProjet) par ID, en format JSON.
     */
    public function getRealisationUaProjet(Request $request, $id)
    {
        try {
            $realisationUaProjet = $this->realisationUaProjetService->find($id);
            return response()->json($realisationUaProjet);
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
        $updatedRealisationUaProjet = $this->realisationUaProjetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedRealisationUaProjet],
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
        $realisationUaProjetRequest = new RealisationUaProjetRequest();
        $fullRules = $realisationUaProjetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_ua_projets,id'];
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